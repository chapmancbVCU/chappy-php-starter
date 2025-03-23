<?php
use Doctum\Doctum;
use Doctum\Parser\Filter\PublicFilter;
use Symfony\Component\Finder\Finder;

// ROOT should point to the **project root**, not "src/api-docs"
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__, 2)); // Move one level up, now correctly points to the project root

// Use Symfony Finder to scan PHP files inside the correct "src" directory
$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in([
        ROOT . DS . 'src',                // Main source directory
        ROOT . DS . 'database' . DS . 'migrations', // Include migrations
        ROOT . DS . 'database' . DS . 'seeders',    // Include seeders
        ROOT . DS . 'tests',              // Include tests
    ]) // This now correctly points to "src" under the project root
    ->exclude([
        'vendor',
        'node_modules',
        'config',
        'public',
        'logs',
        'cache',
        'api-docs/views' // Ensures generated docs aren't scanned
    ]);

// Create Doctum instance
return new Doctum($iterator, [
    'title' => 'Chappy.php API',
    'build_dir' => ROOT . DS . 'src' . DS . 'api-docs' . DS . 'views',  // Ensures docs are stored directly inside "views"
    'cache_dir' => ROOT . DS . 'cache' . DS . 'doctum',  // Caching for faster generation
    'default_opened_level' => 2,  // Sidebar depth
    'filter' => new PublicFilter(),  // Only include public methods,
    'base_url' => '/api-docs/',  // **Fixes broken links**
]);
