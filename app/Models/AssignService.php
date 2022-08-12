<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignService extends Model
{
    use HasFactory;

    protected $table = 'assign_services';

    protected $primaryKey = 'idasser';

    protected $fillable = [
        'is_backup',
        'is_additional',
        'is_windows_license',
        'is_antivirus',
        'is_linux_license',
        'is_additional_spla',
        'idserver'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
