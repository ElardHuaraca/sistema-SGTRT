<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fourwall extends Model
{
    use HasFactory;

    protected $table = 'fourwalls';

    protected $primaryKey = 'idfourwall';

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
