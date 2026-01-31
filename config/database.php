<?php
use Core\Lib\Utilities\Env;
return [
    'default' => Env::get('DB_CONNECTION') ?? 'mysql', // Default to MySQL if not specified
    'db_log_params' => Env::get('DB_LOG_PARAMS' ?? 'none'),
    
    'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => Env::get('DB_DATABASE') ?? __DIR__ . '/../../database/database.sqlite',
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => Env::get('DB_HOST') ?? '127.0.0.1',
            'port' => Env::get('DB_PORT') ?? '3306',
            'database' => Env::get('DB_DATABASE') ?? 'chappy-php',
            'username' => Env::get('DB_USERNAME') ?? 'root',
            'password' => Env::get('DB_PASSWORD') ?? '',  // Corrected to use $_ENV
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
        ],

        'mysql_testing' => [
            'driver' => 'mysql',
            'host' => Env::get('DB_HOST') ?? '127.0.0.1',
            'port' => Env::get('DB_PORT') ?? '3306',
            'database' => Env::get('DB_DATABASE') ?? 'chappy_php_test',
            'username' => Env::get('DB_USERNAME') ?? 'test_user',
            'password' => Env::get('DB_PASSWORD') ?? '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
        ],

        'mariadb' => [
            'driver' => 'mysql', // MariaDB is compatible with MySQL driver
            'host' => Env::get('DB_HOST') ?? '127.0.0.1',
            'port' => Env::get('DB_PORT') ?? '3306',
            'database' => Env::get('DB_DATABASE') ?? 'chappy-php',
            'username' => Env::get('DB_USERNAME') ?? 'root',
            'password' => Env::get('DB_PASSWORD') ?? '',  // Corrected to use $_ENV
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
        ],
    ]
];