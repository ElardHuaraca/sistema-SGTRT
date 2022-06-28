<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SplaAssignedDiscount extends Model
{
    use HasFactory;

    protected $table = 'spla_assigned_discounts';

    protected $primaryKey = 'iddiscount';

    protected $fillable = [
        'percentage',
        'idserver',
        'idspla',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
