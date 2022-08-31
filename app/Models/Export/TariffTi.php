<?php

namespace App\Models\Export;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TariffTi extends Model
{
    use HasFactory;

    protected $fillable = [
        'ALP',
        'project_name',
        'CPU',
        'DISK',
        'RAM',
        'lic_spla',
        'lic_cloud',
        'backup',
        'mo',
        'maintenance',
        'total'
    ];
}
