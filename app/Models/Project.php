<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $primaryKey = 'idproject';

    protected $fillable = [
        'name',
        'is_deleted'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function nexus()
    {
        return $this->hasMany(Nexus::class, 'idproject');
    }

    public function fourwalls()
    {
        return $this->hasMany(Fourwall::class, 'idproject');
    }

    public function hps()
    {
        return $this->hasMany(Hp::class, 'idproject');
    }

    public function servers()
    {
        return $this->hasMany(Server::class, 'idproject');
    }
}
