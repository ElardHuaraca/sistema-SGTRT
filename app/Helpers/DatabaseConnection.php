<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DatabaseConnectionHelper
{
    public static function setConnection($params)
    {
        config(['database.connections.onthefly' => [
            'driver' => $params->driver,
            'host' => $params->host,
            'port' => $params->port,
            'database' => $params->database,
            'username' => $params->username,
            'password' => $params->password,
            'search_path' => $params->schema
        ]]);

        return DB::connection('onthefly');
    }
}
