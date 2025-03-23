<?php
/**
 * Application execution begins here.
 */
use Core\Router;
use Core\Lib\Logging\Logger;
use Core\SessionManager;
use Core\ErrorHandler;

// Define path related constants.
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

// Load configuration and helper functions.
require_once ROOT.DS.'src'.DS.'scripts'.DS.'bootstrap.php';

// Start PHP session
session_start();

// Set up error & exception handling
ErrorHandler::initialize();

// Start session & handle auto-login from Remember Me cookie
SessionManager::initialize();

// Perform routing.
try {
    Router::route();
} catch (Exception $e) {
    Logger::log("Unhandled Exception: " . $e->getMessage(), 'error');
    throw $e; // Let Whoops handle it
}