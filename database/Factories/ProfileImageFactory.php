<?php
namespace Database\Factories;

use Console\Helpers\Tools;
use Core\DB;
use Core\Models\ProfileImages;
use Core\Lib\Database\Factory;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;

/**
 * Factory for generating new profile images.
 */
class ProfileImageFactory extends Factory {
    protected $modelName = ProfileImages::class;
    private $userId;
    public function __construct(int $userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker->addProvider(new FakerPicsumImagesProvider($this->faker));
        $basePath = 'storage' . DS . 'app' . DS . 'private' . DS . 'profile_images' . DS;
        $uploadPath = $basePath . 'user_' . $this->userId . DS;
        Tools::pathExists($uploadPath);

        // Generate the image and get the actual filename from Faker
        $actualFilePath = $this->faker->image($uploadPath, 200, 200, false, null, false, 'jpg');
        
        // Extract only the filename
        $imageFileName = basename($actualFilePath);
        ProfileImages::findAllByUserId($this->userId);
        $sort = DB::getInstance()->count();
        return [
            'user_id' => $this->userId,
            'sort' => $sort,
            'name' => $imageFileName,
            'url' => $uploadPath . $imageFileName
        ];
    }
}