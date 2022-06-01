<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $table = 'servers';

    protected $primaryKey = 'idserver';

    protected $fillable = [
        'idserver',
        'name',
        'active',
        'idproject',
        'idsow',
        'idspla'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function resourcesHistory()
    {
        return $this->hasMany(ResourceHistory::class, 'idserver');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'idproject');
    }
}
