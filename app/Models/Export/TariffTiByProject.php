<?php

namespace App\Models\Export;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TariffTiByProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'idproject',
        'project_name',
        'server_name',
        'sow_name',
        'CPU',
        'RAM',
        'DISK',
        'lic_spla',
        'lic_cloud',
        'backup',
        'mo',
        'maintenance',
        'total'
    ];
}
