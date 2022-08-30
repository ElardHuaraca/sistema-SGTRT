<?php

namespace App\Models\Export;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceHistory extends Model
{
    use HasFactory;

    protected $primaryKey = 'idresource';

    protected $fillable = [
        'server_name',
        'active_server',
        'idproject',
        'project_name',
        'service',
        'CPU',
        'RAM',
        'DISK',
        'date'
    ];
}
