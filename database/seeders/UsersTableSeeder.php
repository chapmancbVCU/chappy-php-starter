<?php
namespace Database\Seeders;

use Database\Factories\UserFactory;
use Core\Lib\Database\Seeder;

/**
 * Seeder for users table.
 * 
 * @return void
 */
class UsersTableSeeder extends Seeder {
    /**
     * Runs the database seeder
     *
     * @return void
     */
    public function run(): void {
        $factory = new UserFactory();
        $factory->count(5);
        console_info("Seeded users table.");
    }
}