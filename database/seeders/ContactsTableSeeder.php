<?php
namespace Database\Seeders;

use Faker\Factory as Faker;
use Core\Lib\Database\Seeder;
use App\Models\Contacts;
use Console\Helpers\Tools;
/**
 * Seeder for contacts table.
 * 
 * @return void
 */
class ContactsTableSeeder extends Seeder {
    /**
     * Runs the database seeder
     *
     * @return void
     */
    public function run(): void {
        $faker = Faker::create('en_us');
        
        $numberOfContacts = 10;
        $i = 0;
        while($i < $numberOfContacts) {
            $contact = new Contacts();
            $contact->fname = $faker->firstName;
            $contact->lname = $faker->lastName;
            $contact->email = $faker->unique()->safeEmail;
            $contact->address = $faker->streetAddress;
            $contact->city = $faker->city;
            $contact->state = $faker->stateAbbr;
            $contact->zip = $faker->postcode;
            $contact->home_phone = $faker->phoneNumber;
            $contact->user_id = 1;

            if($contact->save()) {
                $i++;
            }
        }
        Tools::info("Seeded contacts table.");
    }
}