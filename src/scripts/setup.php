#!/usr/bin/env php
<?php

// 1️⃣ Determine the project root dynamically (if inside src/scripts/)
$projectRoot = dirname(__DIR__, 2); // Go up two levels from 'src/scripts'
chdir($projectRoot); // Ensure we run from the project root

echo "🚀 Setting up the project at: $projectRoot\n";

// 2️⃣ Check if Composer is installed
$composerExists = shell_exec('composer --version');
if (!$composerExists) {
    echo "❌ Composer is not installed. Please install Composer and run this script again.\n";
    exit(1);
}

// 3️⃣ Install Composer dependencies (if vendor folder is missing)
if (!is_dir("vendor")) {
    echo "📦 Running 'composer install'...\n";
    system("composer install --no-interaction");
} else {
    echo "✅ Dependencies are already installed. Skipping 'composer install'.\n";
}

// 🔟 Ensure .env exists and is populated
$envFile = '.env';
$envSampleFile = '.env.sample'; // Assuming your sample file is named .env.sample

if (!file_exists($envFile)) {
    if (file_exists($envSampleFile)) {
        copy($envSampleFile, $envFile);
        echo "✅ Copied .env.sample to .env\n";
    } else {
        echo "⚠️ Warning: .env.sample not found. Creating a blank .env file with defaults.\n";
        $defaultEnv = <<<EOL
APP_KEY=
CURRENT_USER_SESSION_NAME=
REMEMBER_ME_COOKIE_NAME=
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
EOL;
        file_put_contents($envFile, $defaultEnv);
    }
}

// 4️⃣ Require Composer autoloader AFTER ensuring .env exists
require_once "vendor/autoload.php";

use Dotenv\Dotenv;

// 5️⃣ Load environment variables safely
$dotenv = Dotenv::createImmutable($projectRoot);
$dotenv->load();

echo "✅ Loaded environment variables.\n";

// 6️⃣ Generate random keys for security
$appKey = 'base64:' . base64_encode(random_bytes(32));
$cookieSecret = bin2hex(random_bytes(32));
$sessionSecret = bin2hex(random_bytes(32));

// 7️⃣ Update .env file with generated keys
$envLines = file($envFile, FILE_IGNORE_NEW_LINES);
$updatedEnv = [];

foreach ($envLines as $line) {
    if (preg_match('/^APP_KEY\s*=\s*/', $line)) {
        $updatedEnv[] = "APP_KEY={$appKey}";
    } elseif (preg_match('/^CURRENT_USER_SESSION_NAME\s*=\s*/', $line)) {
        $updatedEnv[] = "CURRENT_USER_SESSION_NAME={$cookieSecret}";
    } elseif (preg_match('/^REMEMBER_ME_COOKIE_NAME\s*=\s*/', $line)) {
        $updatedEnv[] = "REMEMBER_ME_COOKIE_NAME={$sessionSecret}";
    } else {
        $updatedEnv[] = $line;
    }
}

// 8️⃣ Write the updated content back to .env
file_put_contents($envFile, implode("\n", $updatedEnv) . "\n");

chmod($envFile, 0777);

echo "🔑 Successfully updated .env with generated keys.\n";

// 9️⃣ Remove .git directory (for fresh installs)
if (is_dir('.git')) {
    echo "🗑 Removing existing Git repository...\n";

    if (PHP_OS_FAMILY === 'Windows') {
        // Windows: Use PowerShell to remove the directory
        system('rd /s /q .git');
    } else {
        // macOS/Linux: Use rm -rf
        system('rm -rf .git');
    }

    // Verify removal
    if (!is_dir('.git')) {
        echo "✅ Git repository removed successfully.\n";
    } else {
        echo "❌ Failed to remove .git. Please delete it manually.\n";
    }
}


// 🔟 Initialize a new Git repository
echo "🔄 Initializing a new Git repository...\n";
system("git init");
system("git add .");
system("git commit -m 'Initial commit'");
echo "✅ New Git repository initialized.\n";

// 1️⃣1️⃣ Create necessary directories
$directories = [
    'storage/app/private/profile_images',
    'storage/logs',
    'database'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
        echo "📂 Created directory: $dir\n";
    }
}

// 1️⃣2️⃣ Set permissions (Linux/macOS only)
if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
    chmod('storage', 0777);
    chmod('storage/logs', 0777);
    chmod('database', 0777);
    echo "🔧 Set permissions for storage, logs, and database directories.\n";
}

// 1️⃣3️⃣ Create SQLite database file if it doesn't exist
$sqliteFile = 'database/database.sqlite';
if (!file_exists($sqliteFile)) {
    touch($sqliteFile);
    echo "📄 Created SQLite database file: $sqliteFile\n";
} else {
    echo "✅ SQLite database file already exists.\n";
}

// 1️⃣4️⃣ Run database migrations
echo "⚙️ Running database migrations...\n";
$migrateCommand = "php console migrate";
$migrateOutput = shell_exec($migrateCommand);

if ($migrateOutput) {
    echo "✅ Migrations completed successfully.\n";
} else {
    echo "❌ Migration process failed. Check your database connection.\n";
}

// 1️⃣6️⃣ Install NPM dependencies (if package.json exists)
if (file_exists("package.json")) {
    echo "📦 Installing NPM dependencies...\n";
    system("npm install");
    echo "✅ NPM dependencies installed.\n";
} else {
    echo "⚠️ No package.json found. Skipping NPM install.\n";
}

// 1️⃣5️⃣ Final instructions
echo "\n✅ Setup complete!\n";
echo "➡️ Run: git add .\n";
echo "➡️ Run: git commit -m \"Initial commit\"\n";
echo "➡️ Set GitHub origin: git remote add origin https://github.com/YOUR_GITHUB_USERNAME/YOUR_REPO_NAME.git\n";
echo "➡️ Run: git push -u origin main\n";
echo "➡️ Run: php console serve\n";
echo "🌍 Open your project at: http://localhost:8000\n";
exit(0);
