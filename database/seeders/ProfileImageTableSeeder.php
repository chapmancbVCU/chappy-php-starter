<?php
namespace Database\Seeders;

use Core\Lib\Database\Seeder;
use Core\Lib\Database\Factories\ProfileImageFactory;

/**
 * Class for generating profile images.
 */
class ProfileImageTableSeeder extends Seeder {
    /**
     * Runs the database seeder
     *
     * @return void
     */
    public function run(): void {
        // $factory = new ProfileImageFactory(1);
        // $factory->count(2)->create();
        console_info("Finished seeding profileImage table.");
    }
}
