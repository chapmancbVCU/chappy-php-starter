<?php

use Core\Lib\Utilities\Env;

return [
    'driver' => Env::get('QUEUE_DRIVER', 'database'),

    'database' => [],

    'redis' => [
        'host' => Env::get('REDIS_HOST', '127.0.0.1'),
        'port' => Env::get('REDIS_PORT', '6379')
    ],
];