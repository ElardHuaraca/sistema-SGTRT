<?php

namespace App\Http\Controllers\VCenter;

use App\Helpers\DatabaseConnectionHelper;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ResourceHistory;
use App\Models\Server;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HydrateDatabaseController extends Controller
{
    public static function hydrateDatabase($diskFile)
    {
        /* register a generic project for servers that do not have information in the annotations */
        $project_generic = Project::find('000000');
        if (!$project_generic) {
            Log::info('Creating generic project');
            $project_generic = new Project();
            $project_generic->idproject = '000000';
            $project_generic->name = 'Generic Project';
            $project_generic->save();
        }

        foreach ($diskFile as $vcenter) {

            $connection = DatabaseConnectionHelper::setConnection($vcenter);
            $vms = $connection->select('SELECT
                                            vpx_vm.id,vpx_non_orm_vm_config_info."name",
                                            vpx_vm.guest_family, vpx_vm.num_vcpu,
                                            vpx_vm.aggr_unshared_storage_space, vpx_vm.ip_address,
                                            vpx_non_orm_vm_config_info.hardware_memory AS memory_ram,
                                            vpx_non_orm_vm_config_info.annotation,vpx_datastore.name AS disk_name
                                        FROM vpx_vm
                                        JOIN vpx_non_orm_vm_config_info ON vpx_non_orm_vm_config_info.id = vpx_vm.id
                                        LEFT JOIN vpx_vm_ds_space ON vpx_vm_ds_space.vm_id = vpx_vm.id
                                        LEFT JOIN vpx_datastore ON vpx_datastore.id = vpx_vm_ds_space.ds_id');
            foreach ($vms as $vm) {
                $server = Server::where('name', $vm->name)->first();

                if (!$server) {
                    Log::info('Server not found: ' . $vm->name);
                    $project = $vm->annotation;

                    $explode_project = sizeof(explode('-', $project)) === 0 ? explode('_', $project) : explode('-', $project);
                    if (count($explode_project) === 0) {
                    }
                }
            }
        }
    }

    public static function createFileJsonDatabasesAndGet()
    {
        if (!Storage::disk('json')->exists('databases.json')) {
            /* if not exist file created */
            Storage::disk('json')->put('databases.json', '
            [
                {
                    "driver": "pgsql",
                    "host": "ipexample",
                    "port": "5432",
                    "database": "vcenter",
                    "username": "postgres",
                    "password": "postgres",
                    "schema": "public"
                }
            ]
            ');
        }
        /* get path from databases.json */
        $path = Storage::disk('json')->get('databases.json');
        return json_decode($path);
    }
}
