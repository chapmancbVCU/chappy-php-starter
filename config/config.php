<?php
/**
 * Configuration for Chappy.php framework.
 */

 return [
    'debug' => $_ENV['DEBUG'] ?? false,
    'app_env' => $_ENV['APP_ENV'] ?? 'production',

    // This should be set to false for security reasons.
    // If you need to run migrations from the browser, you can set this to true temporarily.
    'run_migrations_from_browser' => $_ENV['RUN_MIGRATIONS_FROM_BROWSER'] ?? false,

    'default_controller' => $_ENV['DEFAULT_CONTROLLER'] ?? 'Home', // Default controller if not set in URL
    'default_layout' => $_ENV['DEFAULT_LAYOUT'] ?? 'main', // Default layout if not set in controller

    'app_domain' => $_ENV['APP_DOMAIN'] ?? '/', // Set this to '/' for a live server
    'version' => $_ENV['VERSION'] ?? '1.0.0',
    'site_title' => $_ENV['SITE_TITLE'] ?? 'My App', // Default site title if not set
    'menu_brand' => $_ENV['MENU_BRAND'] ?? 'My Brand', // Branding for menu

    'access_restricted' => $_ENV['ACCESS_RESTRICTED'] ?? 'Restricted', // Controller for restricted redirects

    'max_login_attempts' => is_numeric($_ENV['MAX_LOGIN_ATTEMPTS'] ?? null) ? (int) $_ENV['MAX_LOGIN_ATTEMPTS'] : 5,
    's3_bucket' => $_ENV['S3_BUCKET'] ?? null,

    'time_zone' => $_ENV['TIME_ZONE'] ?? 'UTC',

    /*
     * ADD ADDITIONAL CONFIGURATION HERE.
     */
 ];
