<?php
namespace Database\Seeders;

use Faker\Factory as Faker;
use Core\Lib\Database\Seeder;
use Console\Helpers\Tools;

// Import your model
use App\Models\Users;

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
        $faker = Faker::create();
        
        // Set number of records to create.
        $numberOfRecords = 15;
        $i = 0;
        while($i < $numberOfRecords) {
            $users = new Users();
            $users->username = $faker->userName;
            $users->email = $faker->safeEmail;
            $users->acl = json_encode([""]);
            $users->password = $faker->password;
            $users->confirm = $users->password;
            $users->fname = $faker->firstName;
            $users->lname = $faker->lastName;
            $users->description = $faker->sentence(3);
            $users->inactive = 0;
            $users->reset_password = 0;
            $users->login_attempts = 0;
            $users->deleted = 0;

            // Try saving and catch the error
            try {
                if ($users->save()) {
                    Tools::info("✅ Created user: " . $users->username);
                    $i++;
                } else {
                    Tools::info("❌ Failed to save user: " . json_encode($users));
                }
            } catch (\Exception $e) {
                Tools::info("❌ Database error: " . $e->getMessage());
                break; // Prevent infinite loop
            }
        }
        Tools::info("Seeded users table.");
    }
}