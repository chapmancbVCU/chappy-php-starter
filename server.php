<?php
// Route all requests through index.php
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    if (file_exists(__DIR__ . $path)) {
        return false; // Serve the file as-is
    }
}
require __DIR__ . "/index.php";
