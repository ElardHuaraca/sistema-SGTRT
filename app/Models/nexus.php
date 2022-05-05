<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nexus extends Model
{
    use HasFactory;

    protected $table = 'nexus';

    protected $primaryKey = 'idnexus';

    protected $fillable = [
        'idnexus',
        'punto_red',
        'costo',
        'idproyecto',
        'eliminado'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'idproyecto');
    }
}