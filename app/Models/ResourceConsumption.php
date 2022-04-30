<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceConsumption extends Model
{
    use HasFactory;

    protected $table = 'consumo_recursos';
    protected $primaryKey = 'alp';

    protected $fillable = [
        'activo',
        'proyecto',
        'servidor',
        'cpu',
        'memoria',
        'disco',
        'servicio'
    ];
}
