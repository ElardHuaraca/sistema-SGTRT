<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TChange extends Model
{
    use HasFactory;

    protected $table = 'tcambio';
    protected $primaryKey = 'idtipo';

    protected $fillable = [
        'valor'
    ];
}
