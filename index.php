<?php
use Core\Application;
// Define path related constants.
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

// Run Composer autoloader and bootstrap application.
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/chappy-php/chappy-php-framework/src/scripts/bootstrap.php';

// Initialize services and processes the request.
Application::appStart();