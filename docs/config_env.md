<h1 style="font-size: 50px; text-align: center;">Config and Env Classes</h1>

## Table of contents
1. [Overview](#overview)
2. [Environment Configuration](#environment-configuration)
3. [Env Utility](#env)
4. [Config Utility](#config)
5. [Best Practices](#best-practices)
6. [Debugging](#debugging)
7. [Conclusion](#conclusion)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We use two classes called Config and Env to manage configuration files and environmental variables.  These classes are discussed in detail below.

<br>

## 2. Environment Configuration <a id="environment-configuration"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This file controls application behavior across development, staging, and production environments. Values are loaded at runtime and influence logging, database connections, security policies, queues, mail, and frontend tooling.

<br>

🔧 **Application Environment**

```bash
DEBUG=true
LOGGING="debug"
APP_ENV=local
```

Controls overall runtime behavior.

**DEBUG**

Enables detailed error output and verbose logging when set to `true`. Should be `false` in production.

**LOGGING**

Sets the minimum PSR-3 severity level written to logs (`debug`, `info`, `notice`, `warning`, `error`, etc).
Messages below this threshold are ignored.

**APP_ENV**

Indicates the current environment (`local`, `staging`, `production`). Used for environment-specific behavior.

<br>

🌐 **Application Identity & Routing**

```bash
APP_DOMAIN='http://localhost:8000/'
SITE_TITLE='My App'
MENU_BRAND='My Brand'

DEFAULT_CONTROLLER=Home
DEFAULT_LAYOUT=default
```

Defines application URLs and UI defaults.
- **APP_DOMAIN** – Base application URL
- **SITE_TITLE** – Default browser title
- **MENU_BRAND** – Branding text shown in navigation
- **DEFAULT_CONTROLLER** – Controller used when no route is specified
- **DEFAULT_LAYOUT** – Default layout template

<br>

🔐 **Security & Sessions**

```bash
APP_KEY=
CURRENT_USER_SESSION_NAME=
REMEMBER_ME_COOKIE_NAME=
REMEMBER_ME_COOKIE_EXPIRY=2592000
```

Authentication and session configuration.
- **APP_KEY** – Application encryption key
- **CURRENT_USER_SESSION_NAME** – Session identifier for logged-in users
- **REMEMBER_ME_COOKIE_NAME** – Persistent login cookie name
- **REMEMBER_ME_COOKIE_EXPIRY** – Cookie lifetime (seconds)

<br>

🗄 **Database Configuration**

```bash
DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database/database.sqlite
DB_USER=
DB_PASSWORD=
DB_LOG_PARAMS=full
```

Controls database connectivity and query logging.
- **DB_CONNECTION** – `sqlite`, `mysql`, or `mariadb`
- **DB_HOST / DB_PORT** – MySQL connection details
- **DB_DATABASE** – Database name or SQLite path
- **DB_USER / DB_PASSWORD** – Credentials
- **DB_LOG_PARAMS**

Controls parameter logging:
- `none` → only parameter count/type
- `masked` → redacted values
- `full` → full parameters (dev only)
- This integrates with your logging system to prevent sensitive data leakage.

<br>

🔑 **Password Policy**

```bash
PW_LOWER_CHAR=true
PW_UPPER_CHAR=true
PW_NUM_CHAR=true
PW_SPECIAL_CHAR=true
PW_MIN_LENGTH=12
PW_MAX_LENGTH=30
SET_PW_MIN_LENGTH=true
SET_PW_MAX_LENGTH=true
```

Defines password complexity requirements.  These values are read through `config/password.php` and enforced by the base user model whenever a user registers, resets their password, or changes it.

Supports:
- lowercase (`PW_LOWER_CHAR`)
- uppercase (`PW_UPPER_CHAR`)
- numbers (`PW_NUM_CHAR`)
- special characters (`PW_SPECIAL_CHAR`)
- configurable minimum and maximum lengths (`PW_MIN_LENGTH`, `PW_MAX_LENGTH`), each switched on by `SET_PW_MIN_LENGTH` and `SET_PW_MAX_LENGTH`

Each flag turns its rule on or off.  Set a flag to `false` to stop enforcing that rule.

These values are also exposed to Vite for frontend validation.

<br>

🚫 **Login Protection**

```bash
MAX_LOGIN_ATTEMPTS=100
```

Maximum allowed login attempts before lockout.

<br>

⏰ **Localization**

```bash
TIME_ZONE='America/New_York'
LOCALE='en'
```

Sets application timezone and locale.

<br>

🎨 **Console Styling**

```bash
BACKGROUND_COLOR='green'
TEXT_COLOR='light-grey'
```

Used by console helpers for visual output formatting.

<br>

✉ **Mail Configuration**

```bash
MAILER_DSN=smtp://username:password@smtp.mailtrap.io:2525
MAIL_FROM_ADDRESS=no-reply@chappyphp.test
MAIL_FROM_NAME="ChappyPHP Framework"
```

SMTP configuration for outgoing mail.

Supports Symfony Mailer DSN format.

<br>

📬 **Queue / Job Dispatch**

```bash
QUEUE_DRIVER=database
MAX_ATTEMPTS=3
REDIS_HOST='127.0.0.1'
REDIS_PORT='6379'
```

Controls background job processing.
- **QUEUE_DRIVER** – database or redis
- **MAX_ATTEMPTS** – Retry count for failed jobs
- **REDIS_HOST / REDIS_PORT** – Redis connection (if enabled)

<br>

⚡ **Vite / Frontend Environment**

```bash
VITE_APP_DOMAIN=${APP_DOMAIN}
VITE_S3_BUCKET=${S3_BUCKET}
VITE_PW_LOWER_CHAR=${PW_LOWER_CHAR}
...
```

Exposes selected backend values to Vite for frontend builds.

Includes:
- domain
- password rules
- security constraints

This ensures React/UI validation matches backend enforcement.

<br>

## 3. Env Utility <a id="env"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
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

You can also use the globally available version:
```php
echo env(''APP_NAME')
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

## 4. Config Utility <a id="config"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
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

You can also use the globally available version of this function:
```php
echo config('database.host', '127.0.0.1');
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

## 5. 🚀 Best Practices <a id="best-practices"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
* ✔️ Use `.env` for sensitive credentials (API keys, database passwords).
* ✔️ Use `Config::get()` instead of hardcoding values.
* ✔️ Ensure all `config/*.php` files return arrays.

<br>

## 6. 🛠 Debugging <a id="debugging"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
If `Config::get('app.name')` returns `null`, check:
* `Config::load(ROOT . '/config');` was called before using `Config::get()`.
* The file exists in `config/` and returns an array.
* Restart the server if needed.

<br>

## 7. 🎯 Conclusion <a id="conclusion"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
* `Env` Utility loads environment variables dynamically.
* `Config` Utility centralizes configuration management.
* Use `Env::get()` for `.env` values and `Config::get()` for app settings.