<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grafic extends Model
{
    use HasFactory;

    protected $table = 'grafica';

    protected $primaryKey = 'idgrafica';

    protected $fillable = [
        'tiempo_ejecucion',
        'periodo',
    ];

    protected $hidden = [
        'idgrafica',
    ];
}
