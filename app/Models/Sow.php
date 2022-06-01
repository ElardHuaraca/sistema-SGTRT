<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sow extends Model
{
    use HasFactory;

    protected $table = 'sows';

    protected $primaryKey = 'idsow';

    protected $fillable = [
        'version',
        'name',
        'type',
        'cost_cpu',
        'cost_ram',
        'cost_hdd_mechanical',
        'cost_hdd_solid',
        'cost_mo_clo_sw_ge',
        'cost_mo_cot',
        'cost_cot_monitoring',
        'cost_license_vssp',
        'cost_license_vssp_srm',
        'cost_link',
        'add_cost_antivirus',
        'add_cost_win_license_cpu',
        'add_cost_win_license_ram',
        'add_cost_linux_license',
        'cost_backup_db',
        'created_at',
        'updated_at',
    ];
}
