<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SplaLicense extends Model
{
    use HasFactory;

    protected $table = 'spla_licenses';

    protected $primaryKey = 'idspla';

    protected $fillable = [
        'code',
        'name',
        'cost',
        'type',
        'is_deleted'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function servers()
    {
        return $this->hasMany(Server::class, 'idspla');
    }
}
