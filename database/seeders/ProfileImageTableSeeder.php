<?php
namespace Database\Seeders;

use Faker\Factory as Faker;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;
use Core\Lib\Database\Seeder;
use Console\Helpers\Tools;

// Import your model
use App\Models\ProfileImages;

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
        $faker = Faker::create();
        $faker->addProvider(new FakerPicsumImagesProvider($faker));

        // Generate a unique image filename
        $userId = 1;
        $basePath = 'storage' . DS . 'app' . DS . 'private' . DS . 'profile_images' . DS;
        $uploadPath = $basePath . 'user_' . $userId . DS;
        
        // Set number of records to create.
        $numberOfRecords = 10;
        $i = 0;
        while($i < $numberOfRecords) {
            // Ensure the directory exists
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Generate the image and get the actual filename from Faker
            $actualFilePath = $faker->image($uploadPath, 200, 200, false, null, false, 'jpg');
            
            // Extract only the filename
            $imageFileName = basename($actualFilePath);

            // Create ProfileImages record
            $profileImage = new ProfileImages();
            $profileImage->user_id = $userId;
            $profileImage->sort = $i;
            $profileImage->name = $imageFileName;

            // Correct the database URL to match form-uploaded images
            $profileImage->url = $uploadPath . $imageFileName;

            if ($profileImage->save()) {
                Tools::info("Saved profile image record: $imageFileName");
                $i++;
            } else {
                Tools::info("Failed to save profile image record: $imageFileName");
                Tools::info("Validation Errors: " . json_encode($profileImage->getErrorMessages()));
            }
        }

        Tools::info("Finished seeding profileImage table.");
    }
}
