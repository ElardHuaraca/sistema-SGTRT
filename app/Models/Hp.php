<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hp extends Model
{
    use HasFactory;

    protected $table = 'hp';

    protected $primaryKey = 'idhp';

    protected $fillable = [
        'idhp',
        'equipo',
        'serie',
        'costo',
        'fec_inicio',
        'fec_fin',
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
