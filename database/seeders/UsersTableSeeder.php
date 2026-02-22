<?php
namespace Database\Seeders;

use Core\Lib\Database\Factories\UserFactory;
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
        //UserFactory::factory()->count(10)->create();
        console_info("Seeded users table.");
    }
}