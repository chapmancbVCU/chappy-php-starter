<?php

use Core\Lib\Utilities\Env;

return [
    'driver' => Env::get('REDIS_DRIVER') ?? 'database',

    'database' => [
        'dsn' => 'mysql:host=127.0.0.1;dbname=queue_app',
        'username' => 'root',
        'password' => '',
    ],

    'redis' => [
        'host' => '127.0.0.1',
        'port' => 6379,
    ],
];