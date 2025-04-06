<h1 style="font-size: 50px; text-align: center;">Config and Env Classes</h1>

## Table of contents
1. [Overview](#overview)
2. [Env Utility](#env)
3. [Config Utility](#config)
4. [Best Practices](#best-practices)
5. [Debugging](#debugging)
6. [Conclusion](#conclusion)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We use two classes called Config and Env to manage configuration files and environmental variables.  These classes are discussed in detail below.

<br>

## 2. Env Utility <a id="env"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The Env utility helps load and manage environment variables from a `.env` file.
<br>

#### A. 📌 Features
* ✅ Loads environment variables from a .env file.
* ✅ Automatically converts "true" / "false" to booleans.
* ✅ Automatically converts numeric values to integers or floats.
* ✅ Provides a `get()` method to retrieve variables with default values.

<br>

#### B. 🔧 Installation & Setup
The Env utility is automatically included in your framework. To ensure .env is loaded, call:
```php
use Core\Lib\Utilities\Env;

Env::load(ROOT . '/.env');
```

An example `.env` file:
```php
APP_NAME="My Custom App"
DEBUG=true
MAX_USERS=100
ENABLE_FEATURE=false
```
<br>

#### C. 📌 Usage
🔍 Getting an Environment Variable
```php
echo Env::get('APP_NAME'); // Outputs: "My Custom App"
```

🛠 Providing a Default Value
```php
echo Env::get('NON_EXISTENT_KEY', 'Default Value');
```

✅ Example Output

| `.env` Value | `Env::get('KEY')` Output |
|-------|-------|
| `DEBUG=true` | `true` (boolean) |
| `MAX_USERS=100` | `100` (integer) |
| `ENABLE_FEATURE=false` | `false` (boolean) |
| `PI=3.14` | `3.14` (float) |

<br>

#### D. 🛠 Debugging
If `Env::get('SOME_KEY')` returns `null`, check:
* The `.env` file exists at the correct location.
* The `.env` file follows the format `KEY=VALUE`.
* Restart the server (sudo systemctl restart apache2) or (sudo systemctl restart nginx).  If using `php console serve` press `ctrl+c` and restart PHP server.

<br>

## 3. Config Utility <a id="config"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `Config` utility helps manage configuration files stored in the `config/` directory.
<br>

#### A. 📌 Features
* ✅ Loads configuration files dynamically.
* ✅ Uses dot notation (`config('app.name')`) for nested values.
* ✅ Provides default values when keys are missing.

<br>

#### B. 🔧 Installation & Setup
The `Config` utility is automatically included in your framework. To load all config files, call:
```php
use Core\Lib\Utilities\Config;

Config::load(ROOT . '/config');
```

📂 Example config/app.php File
```php
return [
    'name' => Env::get('APP_NAME', 'DefaultApp'),
    'debug' => Env::get('DEBUG', false),
    'timezone' => Env::get('TIME_ZONE', 'UTC'),
];
```
<br>

#### C. 📌 Usage
🔍 Getting a Config Value
```php
echo Config::get('app.name'); // Outputs: "My Custom App"
```

🛠 Providing a Default Value
```php
echo Config::get('database.host', '127.0.0.1');
```

📌 Example Config Files
config/app.php
```php
return [
    'name' => Env::get('APP_NAME', 'My App'),
    'debug' => Env::get('DEBUG', false),
];
```

config/database.php
```php
return [
    'host' => Env::get('DB_HOST', '127.0.0.1'),
    'port' => Env::get('DB_PORT', 3306),
    'username' => Env::get('DB_USER', 'root'),
    'password' => Env::get('DB_PASSWORD', ''),
];
```
<br>

#### D. 📌 Advanced Usage
🔍 Storing Dynamic Configuration
```php
Config::set('app.debug', true);
echo Config::get('app.debug'); // Outputs: true
```
<br>

## 4. 🚀 Best Practices <a id="best-practices"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
* ✔️ Use `.env` for sensitive credentials (API keys, database passwords).
* ✔️ Use `Config::get()` instead of hardcoding values.
* ✔️ Ensure all `config/*.php` files return arrays.

<br>

## 5. 🛠 Debugging <a id="debugging"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
If `Config::get('app.name')` returns `null`, check:
* `Config::load(ROOT . '/config');` was called before using `Config::get()`.
* The file exists in `config/` and returns an array.
* Restart the server if needed.

<br>

## 6. 🎯 Conclusion <a id="conclusion"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
* `Env` Utility loads environment variables dynamically.
* `Config` Utility centralizes configuration management.
* Use `Env::get()` for `.env` values and `Config::get()` for app settings.