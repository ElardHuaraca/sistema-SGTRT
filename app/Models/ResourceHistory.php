<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceHistory extends Model
{
    use HasFactory;

    protected $table = 'resources_history';

    protected $primaryKey = 'idresource';

    protected $fillable = [
        'name',
        'amount',
        'date',
        'idserver'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function server()
    {
        return $this->belongsTo(Server::class, 'idserver');
    }
}
