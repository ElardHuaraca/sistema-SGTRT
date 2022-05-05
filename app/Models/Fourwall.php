<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fourwall extends Model
{
    use HasFactory;

    protected $table = 'fourwalls';

    protected $primaryKey = 'idfourwalls';

    protected $fillable = [
        'idfourwalls',
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
