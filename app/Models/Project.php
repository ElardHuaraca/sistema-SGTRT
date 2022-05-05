<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'proyecto';

    protected $primaryKey = 'idproyecto';

    protected $fillable = ['nombre'];

    protected $hidden = ['created_at','updated_at'];

    public function nexus()
    {
        return $this->hasMany(Nexus::class, 'idproyecto');
    }

    public function fourwall()
    {
        return $this->hasMany(Fourwall::class, 'idproyecto');
    }

    public function hp()
    {
        return $this->hasMany(Hp::class, 'idproyecto');
    }
}
