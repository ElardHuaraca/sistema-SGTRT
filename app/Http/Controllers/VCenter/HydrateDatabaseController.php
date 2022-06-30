<?php

namespace App\Http\Controllers\VCenter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HydrateDatabaseController extends Controller
{
    public function hydrateDatabase()
    {
        $hydrate = DB::connection('psgl2')
            ->select('SELECT
                        vpx_vm.datacenter_id,vpx_vm.id, vpx_non_orm_vm_config_info."name",
                        vpx_vm.guest_family,vpx_vm.mem_size_mb, vpx_vm.num_vcpu,
                        vpx_vm.aggr_commited_storage_space, vpx_vm.aggr_uncommited_storage_space,
                        vpx_vm.aggr_unshared_storage_space, vpx_vm.ip_address,
                        vpx_non_orm_vm_config_info.hardware_memory as memory_ram_2,
                        vpx_non_orm_vm_config_info.hardware_cores as cpu_cores_2
                    from vpx_vm
                    join vpx_non_orm_vm_config_info on vpx_non_orm_vm_config_info.id=vpx_vm.id');
        return $hydrate;
    }
}
