<?php

namespace App\Http\Controllers\VCenter;

use App\Helpers\DatabaseConnectionHelper;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ResourceHistory;
use App\Models\Server;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HydrateDatabaseController extends Controller
{
    public static function hydrateDatabase($diskFile)
    {
        /* register a generic project for servers that do not have information in the annotations */
        $project_generic = Project::find('999999');
        if (!$project_generic) {
            $project = HydrateDatabaseController::registerProject('999999', 'GENERIC PROJECT');
        }

        $servers_registered = [];

        foreach ($diskFile as $vcenter) {

            $connection = DatabaseConnectionHelper::setConnection($vcenter);
            $vms = $connection->select('SELECT
                                            vpx_vm.id,vpx_vm.dns_name,vpx_non_orm_vm_config_info."name",vpx_vm.num_vcpu,
                                            vpx_vm.aggr_unshared_storage_space, vpx_vm.aggr_uncommited_storage_space,
                                            vpx_vm.power_state,vpx_non_orm_vm_config_info.hardware_memory AS memory_ram,
                                            vpx_non_orm_vm_config_info.annotation,vpx_datastore.name AS disk_name
                                        FROM vpx_vm
                                        JOIN vpx_non_orm_vm_config_info ON vpx_non_orm_vm_config_info.id = vpx_vm.id
                                        LEFT JOIN vpx_vm_ds_space ON vpx_vm_ds_space.vm_id = vpx_vm.id
                                        LEFT JOIN vpx_datastore ON vpx_datastore.id = vpx_vm_ds_space.ds_id');
            foreach ($vms as $vm) {
                $server = Server::where('name', strtoupper($vm->name))->first();

                $dns_name = $vm->name;

                if ($vm->dns_name != null && $vm->dns_name != 'None') $dns_name = $vm->dns_name;

                if (!$server) {

                    [$service, $project] = HydrateDatabaseController::getService($vm->annotation, $project_generic);

                    $server = new Server();
                    $server->name = strtoupper($vm->name);
                    $server->active = strtoupper($project->idproject . $vm->name);
                    $server->machine_name = strtoupper($vm->name);
                    $server->hostname = strtoupper($dns_name);
                    $server->service = $service;
                    $server->idproject = $project->idproject;
                    $server->save();
                } else {

                    [$service, $project] = HydrateDatabaseController::getService($vm->annotation, $project_generic);

                    $server->name = strtoupper($vm->name);
                    $server->active = strtoupper($server->idproject . $vm->name);
                    $server->hostname = strtoupper($dns_name);
                    $server->service = $service;
                    $server->save();
                }

                $disk = round(($vm->aggr_unshared_storage_space + $vm->aggr_uncommited_storage_space) / 1073741824);

                if ($vm->power_state === 0) {
                    $vm->num_vcpu = 0;
                    $vm->memory_ram = 0;
                    $disk = round($vm->aggr_unshared_storage_space / 1073741824);
                }


                $resource_comsuption_ram = new ResourceHistory();
                $resource_comsuption_ram->idserver = $server->idserver;
                $resource_comsuption_ram->name = 'RAM';
                $resource_comsuption_ram->amount = round($vm->memory_ram / 1024);
                $resource_comsuption_ram->date = Carbon::now();
                $resource_comsuption_ram->save();

                $resource_comsuption_vcpu = new ResourceHistory();
                $resource_comsuption_vcpu->idserver = $server->idserver;
                $resource_comsuption_vcpu->name = 'CPU';
                $resource_comsuption_vcpu->amount = $vm->num_vcpu;
                $resource_comsuption_vcpu->date = Carbon::now();
                $resource_comsuption_vcpu->save();

                /* Obtain type disk */
                $disk_type = '';
                if (str_contains($vm->disk_name, '_sas_') || str_contains($vm->disk_name, '_sat_') || str_contains($vm->disk_name, '_fls_')) {
                    $disk_type = 'HDD';
                } else if (str_contains($vm->disk_name, '_ssd_')) {
                    $disk_type = 'SSD';
                }

                $resource_comsuption_disk = new ResourceHistory();
                $resource_comsuption_disk->idserver = $server->idserver;
                $resource_comsuption_disk->name = $disk_type;
                $resource_comsuption_disk->amount = $disk;
                $resource_comsuption_disk->date = Carbon::now();
                $resource_comsuption_disk->save();

                array_push($servers_registered, $server);
            }
        }

        $servers = Server::all();

        $servers = $servers->diff($servers_registered);

        foreach ($servers as $server) {
            $server->is_deleted = true;
            $server->save();
        }
    }

    public static function createFileJsonDatabasesAndGet()
    {
        if (!Storage::disk('json')->exists('databases.json')) {
            /* if not exist file created */
            Storage::disk('json')->put(
                'databases.json',
                '[
                    {
                        "driver": "pgsql",
                        "host": "ipexample",
                        "port": "5432",
                        "database": "vcenter",
                        "username": "postgres",
                        "password": "postgres",
                        "schema": "public"
                    }
                ]'
            );
        }
        /* get path from databases.json */
        $path = Storage::disk('json')->get('databases.json');
        return json_decode($path);
    }

    public static function hydrateWithException()
    {
        $servers = Server::where('is_deleted', false)->get();
        foreach ($servers as $server) {
            $resource_comsuption_ram = new ResourceHistory();
            $resource_comsuption_ram->idserver = $server->idserver;
            $resource_comsuption_ram->name = 'RAM';
            $resource_comsuption_ram->amount = 0;
            $resource_comsuption_ram->date = Carbon::now();
            $resource_comsuption_ram->save();

            $resource_comsuption_vcpu = new ResourceHistory();
            $resource_comsuption_vcpu->idserver = $server->idserver;
            $resource_comsuption_vcpu->name = 'CPU';
            $resource_comsuption_vcpu->amount = 0;
            $resource_comsuption_vcpu->date = Carbon::now();
            $resource_comsuption_vcpu->save();

            $resource_comsuption_disk = new ResourceHistory();
            $resource_comsuption_disk->idserver = $server->idserver;
            $resource_comsuption_disk->name = 'HDD';
            $resource_comsuption_disk->amount = 0;
            $resource_comsuption_disk->date = Carbon::now();
            $resource_comsuption_disk->save();
        }
    }

    private static function getService($project, $project_generic)
    {
        $explode_project = sizeof(explode('-', $project)) === 0 ? explode('_', $project) : explode('-', $project);
        $explode_project[0] = trim($explode_project[0]);

        $service = null;
        $project = null;

        if (count($explode_project) !== 3 ||  !is_numeric($explode_project[0]) || strlen($explode_project[0]) !== 6) {
            $project = $project_generic;
            $service = 'BRONCE';
        } else {
            $explode_project[1] = trim($explode_project[1]);
            $explode_project[2] = strtoupper(trim($explode_project[2]));

            $project = Project::find($explode_project[0]);

            if (!$project) $project = HydrateDatabaseController::registerProject($explode_project[0], $explode_project[2]);

            $service = $explode_project[1];
        }

        return [$service, $project];
    }

    private static function registerProject($alp, $name)
    {
        $project = new Project();
        $project->idproject = $alp;
        $project->name = $name;
        $project->save();

        return $project;
    }
}
