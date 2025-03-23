<?php
namespace Console\Helpers;

use Core\Lib\Utilities\Str;
use Database\Seeders\DatabaseSeeder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
/**
 * Supports operations related to database seeding.
 */
class DBSeeder {

    /**
     * Creates a class for seeding a database.
     *
     * @param InputInterface $input The input for getting name of seeder class.
     * @return int A value that indicates success, invalid, or failure.
     */
    public static function makeSeeder(InputInterface $input): int {
        $seederName = Str::ucfirst($input->getArgument('seeder-name'));

        // Generate Seeder class
        return Tools::writeFile(
            ROOT.DS.'database'.DS.'seeders'.DS.$seederName.'TableSeeder.php',
            self::seeder($seederName),
            'Seeder'
        );
    }
    
    /**
     * Runs command for seeding database.
     *
     * @return int A value that indicates success, invalid, or failure.
     */
    public static function seed(): int {
        $seeder = new DatabaseSeeder();
        $seeder->run();
        Tools::info('Database seeding complete!.  If you see only this message then uncomment your seeders.');
        return Command::SUCCESS;
    }

    /**
     * Returns a string containing contents of a new Seeder class.
     *
     * @param string $seederName The name of the Seeder class.
     * @return string The contents of the seeder class.
     */
    public static function seeder(string $seederName): string {
        $lcSeederName = Str::lcfirst($seederName);
        $ucSeederName = Str::ucfirst($seederName);
        return '<?php
namespace Database\Seeders;

use Faker\Factory as Faker;
use Core\Lib\Database\Seeder;
use Console\Helpers\Tools;

// Import your model
use App\Models\\'.$ucSeederName.';

/**
 * Seeder for '.$lcSeederName.' table.
 * 
 * @return void
 */
class '.$ucSeederName.'TableSeeder extends Seeder {
    /**
     * Runs the database seeder
     *
     * @return void
     */
    public function run(): void {
        $faker = Faker::create();
        
        // Set number of records to create.
        $numberOfRecords = 10;
        $i = 0;
        while($i < $numberOfRecords) {
            $'.$lcSeederName.' = new '.$ucSeederName.'();
            

            if($'.$lcSeederName.'->save()) {
                $i++;
            }
        }
        Tools::info("Seeded '.$lcSeederName.' table.");
    }
}
';
    }
}