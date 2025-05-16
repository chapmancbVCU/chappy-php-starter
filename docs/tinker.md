<h1 style="font-size: 50px; text-align: center;">Tinker Command</h1>

## Table of contents
1. [Overview](#overview)
2. [Requirements](#requirements)
3. [Starting Tinker](#startup)
4. [Examples](#examples)
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `tinker` command gives you an interactive REPL (Read-Eval-Print Loop) for your application.
It allows you to interact with your models, database, and framework utilities **without running the full application**.

This is extremely useful for debugging, testing, and development.

<br>

## 2. ✅ Requirements <a id="requirements"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You must install psysh as a dev dependency first:

```sh
composer require --dev psy/psysh
```

<br>

## 3. 🚀 Starting Tinker <a id="startup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
In your project root:

```sh
php console tinker
```

YOu will see a prompt:

```sh
Psy Shell v0.12.0 (PHP 8.x — cli) by Justin Hileman
>>>
```

<br>

## 4. 💡 Examples <a id="examples"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
1️⃣ Access Models
```php
use App\Models\Users;
$admin = Users::findById(1);
print_r($admin);
```

<br>

2️⃣ Test Utilities
```php
use Core\Helper;
Helper::currentPage();
```

<br>

3️⃣ Read Environment Variables
```php
use Core\Lib\Utilities\Env;
Env::get('APP_DOMAIN');
```

<br>

4️⃣ Manually Validate a Model
```php
use App\Models\Users;
$user = new Users();
$user->email = 'invalid-email';
$user->runValidation(new EmailValidator($user, ['field' => 'email', 'message' => 'Invalid email']));
print_r($user->getErrorMessages());
```

<br>

5️⃣ Run Raw SQL Queries
```php
use Core\DB;
$db = DB::getInstance();
$results = $db->query("SELECT * FROM users")->results();
print_r($results);
```

<br>

6️⃣ Create New Records
```
use App\Models\Users;
$user = new Users();
$user->username = 'chad';
$user->email = 'chad@example.com';
$user->save();
echo $user->id;
```