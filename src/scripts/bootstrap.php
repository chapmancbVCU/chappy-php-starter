<?php
use Dotenv\Dotenv;
use Core\Lib\Utilities\Env;
use Core\Lib\Utilities\Config;

// Load Composer dependencies
require_once ROOT . DS . 'vendor' . DS . 'autoload.php';

// Load environment variables
$dotenv = Dotenv::createImmutable(ROOT);
$dotenv->load();

// Load configuration and helper functions
require_once ROOT . DS . 'src' . DS . 'scripts' . DS . 'helpers.php';

// Load environment variables from .env file
Env::load(ROOT . '/.env');

// Load configuration files from the `config/` directory
Config::load(ROOT . '/config');
