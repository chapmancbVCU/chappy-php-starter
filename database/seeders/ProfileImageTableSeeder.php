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
        $factory = new ProfileImageFactory(1);
        $factory->count(1);

        $factory2 = new ProfileImageFactory(2);
        $factory2->count(1);
        console_info("Finished seeding profileImage table.");
    }
}
