<?php
namespace Tests;
use Core\DB;
use Core\Lib\Utilities\Env;
use Console\Helpers\Migrate;
use PHPUnit\Framework\TestCase;
use Database\Seeders\DatabaseSeeder;

/**
 * Abstract class for test cases.
 */
abstract class ApplicationTestCase extends TestCase {
    /**
     * Implements setUp function from TestCase class.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        DB::connect([
            'driver'   => Env::get('DB_CONNECTION', 'sqlite'),
            'database' => Env::get('DB_DATABASE', ':memory:'),
            'host'     => Env::get('DB_HOST', '127.0.0.1'),
            'port'     => Env::get('DB_PORT', '3306'),
            'username' => Env::get('DB_USERNAME', 'root'),
            'password' => Env::get('DB_PASSWORD', ''),
            'charset'  => Env::get('DB_CHARSET', 'utf8mb4'),
        ]);
        
        // Control DB setup via env toggles
        if(Env::get('DB_REFRESH', true)) {
            Migrate::refresh();
        }

        $this->runMigrations();

        if(Env::get('DB_SEED', true)) {
            $this->runSeeders();
        }
    }

    protected function runMigrations(): void
    {
        Migrate::migrate();
    }

    protected function runSeeders(): void
    {
        (new DatabaseSeeder())->run();
    }
}