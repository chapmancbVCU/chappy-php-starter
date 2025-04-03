<?php
use Core\Lib\Utilities\Env;
/**
 * Configuration for Chappy.php framework.
 */

 return [
    'debug' => Env::get('DEBUG') ?? false,
    'app_env' => Env::get('APP_ENV') ?? 'production',

    // This should be set to false for security reasons.
    // If you need to run migrations from the browser, you can set this to true temporarily.
    'run_migrations_from_browser' => Env::get('RUN_MIGRATIONS_FROM_BROWSER') ?? false,

    'default_controller' => Env::get('DEFAULT_CONTROLLER') ?? 'Home', // Default controller if not set in .env
    'default_layout' => Env::get('DEFAULT_LAYOUT') ?? 'main', // Default layout if not set in controller

    'app_domain' => Env::get('APP_DOMAIN') ?? '/', // Set this to '/' for a live server
    'version' => '1.0.28',
    'site_title' => Env::get('SITE_TITLE') ?? 'My App', // Default site title if not set
    'menu_brand' => Env::get('MENU_BRAND') ?? 'My Brand', // Branding for menu

    'access_restricted' => 'Restricted', // Controller for restricted redirects

    'max_login_attempts' => is_numeric(Env::get('MAX_LOGIN_ATTEMPTS') ?? null) ? (int) Env::get('MAX_LOGIN_ATTEMPTS') : 5,

    'time_zone' => Env::get('TIME_ZONE') ?? 'UTC',
    
    's3_bucket' => Env::get('S3_BUCKET') ?? null,
 ];
