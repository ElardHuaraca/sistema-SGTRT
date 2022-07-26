<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nexus extends Model
{
    use HasFactory;

    protected $table = 'nexus';

    protected $primaryKey = 'idnexus';

    protected $fillable = [
        'idnexus',
        'network_point',
        'cost',
        'idproject',
        'is_deleted'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'idproject');
    }
}
