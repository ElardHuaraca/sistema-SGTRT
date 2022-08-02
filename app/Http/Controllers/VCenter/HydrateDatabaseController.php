<?php

namespace App\Http\Controllers\VCenter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HydrateDatabaseController extends Controller
{
    public static function hydrateDatabase($diskFile)
    {
        foreach ($diskFile as $vcenter) {
            # code...
            $hydrate = DB::connection('pgsql2')
                ->select('SELECT
                            vpx_vm.datacenter_id,
                            vpx_vm.id,vpx_non_orm_vm_config_info."name",
                            vpx_vm.guest_family,vpx_vm.mem_size_mb, vpx_vm.num_vcpu,
                            vpx_vm.aggr_unshared_storage_space, vpx_vm.ip_address,
                            vpx_non_orm_vm_config_info.hardware_memory as memory_ram_2,
                            vpx_non_orm_vm_config_info.hardware_cores as cpu_cores_2,
                            vpx_non_orm_vm_config_info.annotation
                        FROM vpx_vm
                        JOIN vpx_non_orm_vm_config_info on vpx_non_orm_vm_config_info.id=vpx_vm.id');
        }
    }

    public static function createFileJsonDatabasesAndGet()
    {
        $disk = null;
        if (!Storage::disk('json')->exists('databases.json')) {
            $disk = Storage::disk('json')->put('databases.json', '');
        } else {
            $disk = Storage::disk('json')->get('databases.json');
        }
        return json_decode($disk . storage_path(), true);
    }
}
