<h1 style="font-size: 50px; text-align: center;">Debugging and Logs</h1>

## Table of contents
1. [Overview](#overview)
2. [filp/whoops](#whoops)
3. [Log Files](#logs)
4. [Helper Functions](#helpers)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework provides built-in tools for identifying and resolving issues during development. These include:

- An interactive error display system powered by [filp/whoops](https://github.com/filp/whoops)
- Application and CLI log files for capturing system events
- Developer-friendly helper functions for debugging within controllers, views, or models

How verbose your logging can be set in the `.env` file using severity levels based on the PSR-3 specification.

Example:
```bash
LOGGING="notice"
```

When logging is set to `notice` any messages with a lower severity level is not logged.
<br>

## 2. filp/whoops <a id="whoops"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
filp/whoops is a pretty page handler for displaying errors within the browser.  This tool is also included with the Laravel framework.  Thus, inclusion of this tool within this frameworks makes the switch relatively easy.

<br>

## 3. Logs <a id="logs"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Add content to the log files with the following global functions:
```php
emergency('My message',);
```

```php
alert('My message',);
```

```php
critical('My message',);
```

```php
error('My message',);
```

```php
warning('My message',);
```

```php
notice('My message',);
```

```php
info('My message',);
```

```php
debug('My message',);
```

Or call using the `Logger` class:
```php
use Core\Lib\Logging\Logger;
Logger::('My message', 'level');
```

📊 Supported Logger Levels (Typical)

| Level       | Description                                                                |
| ----------- | -------------------------------------------------------------------------- |
| `emergency` | System is unusable. Immediate action required.                             |
| `alert`     | Action must be taken immediately (e.g., database is down).                 |
| `critical`  | Critical conditions (e.g., app component unavailable).                     |
| `error`     | Runtime errors that don’t require immediate action but should be logged.   |
| `warning`   | Exceptional occurrences that are not errors (e.g., deprecated API use).    |
| `notice`    | Normal but significant events (e.g., config loaded, login attempt).        |
| `info`      | Informational messages (e.g., user signed in, order placed).               |
| `debug`     | Detailed debug information for developers (e.g., SQL queries, memory use). |

This framework supports two types of log files, located under `storage/logs`:

- `app.log`: Logs frontend and application runtime events
- `cli.log`: Logs activity from console commands

<a id="clear-logs"></a>
To clear these logs, use the following command:

```sh
php console log:clear
```

This command has 4 flags that can be set.
* `--all`: Clears both log files
* `--app`: Clears only app.log
* `--cli`: Clears only cli.log
* `--unit`: Clears only phpunit.log

If the framework has issues writing to the logs files run the following command:
```sh
sudo chmod -R 775 storage/logs/
sudo chown ${user}:${user} -R /storage/logs
```

<br>

## 3. Helper Functions <a id="helpers"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
There are 3 functions in the Global Namespace that are used for debugging.

| Function | Description |
|:--------:|-------------|
| `cl($var)` | Prints a JavaScript `console.log` statement for the given variable. Output will appear in the browser's developer console. Useful for debugging view-layer variables. |
| `dump($var)` | Dumps variable content using Symfony’s `VarDumper`, but allows the application to continue executing. |
| `dd($var)` | Same as `dump`, but halts further execution immediately after the call (short for "dump and die"). |

**Example Usage:**
```php
$user = Users::findById(1);
dd($user); // Stops here and dumps user info
```

Use these tools to inspect variables during development, troubleshoot bugs, or validate your logic at runtime.