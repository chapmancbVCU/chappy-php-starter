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
        $factory = (new UserFactory())->count(2)->inactive()->withImages(2)->create();
        $factory2 = (new UserFactory())->inactive()->create();
        $factory3 = new UserFactory();
        $factory3->count(4)->sequence(
            ['acl' => json_encode(["Admin"])],
            ['acl' => json_encode(["test"])],
        )->create();
        $factory3->count(2)->sequence(
            ['acl' => json_encode(["foo"])],
            ['acl' => json_encode(["bar"])],
        )->create();
        $factory3->inactive()->create();
        $factory3->create();
        UserFactory::factory()->admin()->count(1)->create();
        UserFactory::factory()->admin()->inactive()->create(['fname' => 'Jane', 'lname' => 'Doe']);
        console_info("Seeded users table.");
    }
}