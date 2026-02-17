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
        $factory = (new UserFactory())->inactive()->count(1);
        $factory2 = (new UserFactory())->inactive()->createOne();
        $factory3 = new UserFactory();
        $factory3->admin()->count(1);
        UserFactory::factory(UserFactory::class)->admin()->count(1);
        UserFactory::factory(UserFactory::class)->admin()->inactive()->createOne(['fname' => 'Jane', 'lname' => 'Doe']);
        console_info("Seeded users table.");
    }
}