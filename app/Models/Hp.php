<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hp extends Model
{
    use HasFactory;

    protected $table = 'hps';

    protected $primaryKey = 'idhp';

    protected $fillable = [
        'equipment',
        'serie',
        'cost',
        'date_start',
        'date_end',
        'idproject',
        'is_deleted'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'idproject');
    }
}
