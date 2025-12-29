<h1 style="font-size: 50px; text-align: center;">Console</h1>

## Table of contents
1. [Overview](#overview)
2. [When to Use the Console](#when-to-use)
3. [Summary of Available Commands](#summary-of-available-commands)
    * A. [Generators](#generators)  
    * B. [Migrations & Seeders](#migrations)  
    * C. [Local Servers](#local-servers)
    * D. [Notifications](#notifications)
    * E. [Queue](#queue)
    * F. [Testing](#testing)  
    * G. [Tools](#tools) 
    * H. [React](#react)
4. [Building Your Own Command](#build-command)
5. [Command Helpers](#command-helpers)
6. [Tools](#tools)
    * A. [border Function](#border)
    * B. [createDirWithPrompt](#dir-with-prompt)
    * C. [dotNotificationVerify](#dot-notification-Verify)
    * D. [info Function](#info)
    * E. [writeFile Function](#write-file)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The Chappy.php framework includes a built-in **command-line interface (CLI)** for managing routine development tasks such as generating files, running tests, seeding the database, and launching local servers.  Check out Symfony's Console component [page](https://symfony.com/doc/current/console.html) for additional documentation.  

You can run a console command following the following syntax:

```sh
php console ${command_name} ${argument}
```

An example of a command that requires arguments is demonstrated below:

```sh
php console test Test
```

Where Test is the name of the file containing the test. Typing php console in the command line at project root will display all of the available commands. Each of the supported commands will be covered in their respective sections in this user guide.

If there is a command you would like for us to support you can submit an issue [here](https://github.com/chapmancbVCU/chappy-php-framework/issues).

<br>

## 2. When to Use the Console <a id="when-to-use"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The console is ideal for:
- Generating boilerplate (models, controllers, views, etc.)
- Running migrations and seeders
- Executing unit tests
- Serving the app locally (via PHP)
- Creating custom tools for automation

<br>

## 3. Summary of Available Commands <a id="summary-of-available-commands"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You can list all available commands at any time by running:

```sh
php console
```

<br>

### A. Generators <a id="generators"></a>

| Command | Description | Arguments |
|:-------:|-------------|-----------|
| [`make:acl`](layouts#menu-acls) | Generates a new menu_acl json file | |
| [`make:command`](console#build-command) | Generates a new command class | |
| [`make:command-helper`](console#command-helpers) | Generates helper class that supports console commands | |
| [`make:component`](components) | Generates component based on flags that are set | [`--card`](components#card), <br>[`--form`](components#form), <br>[`--table`](components#table)|
| [`make:controller`](controllers#creating-a-controller) | Generates a new controller class | [`--layout`](controllers#creating-a-controller), [`--resource`](controllers#creating-a-controller) |
| [`make:css`](views#make-css) | Generates a new CSS file | |
| [`make:email`](email#templates-and-layouts) | Generates a file for an E-mail | |
| [`make:email-layout`](email#templates-and-layouts) | Generates an E-mail template | |
| [`make:event`](events#creating) | Generates a new Event class | [`--queue`](events#create-queued-event) |
| [`make:job`](queue#job-class) | Generates a new job class | |
| [`make:layout`](layouts#build-layout) | Generates a new layout | [`--menu`](layouts#menus), <br>[`--menu-acl`](layouts#menu-acls) |
| [`make:mailer`](email#custom-mailers) | Generates a new custom mailer class | |
| [`make:menu`](layouts#menus) | Generates a new menu file | |
| [`make:migration`](database_operations#creating-a-new-table) | Generates a Database Migration | [`--update`](database_operations#updating-an-existing-table), <br>[`--rename`](database_operations#renaming-an-existing-table) |
| [`make:model`](models#overview) | Generates a new model file | [`--upload`](uploads#model-setup) |
| [`make:notification`](notifications#writing-a-notification) | Generates a new notification class | [`--channels`](notifications#notification-class) |
| [`make:provider`](events#make-provider) | Generates a new event service provider class | |
| [`make:seeder`](database_seeders#seeder-class) | Creates a new database seeder class | |
| [`make:service`](services#user-services) | Generates a new service class | |
| [`make:test`](unit_tests#creating-tests) | Generates a new test class | [`--feature`](unit_tests#creating-tests) |
| [`make:validator`](server_side_validation#custom-validators) | Generates a new custom form validator class | |
| [`make:view`](views#make-views) | Create a new view | |
| [`make:widget`](widgets#overview) | Creates a new widget| |

<br>

### B. Migrations & Seeders <a id="migrations"></a>

| Command | Description | Arguments |
|:-------:|-------------|-----------|
| [`migrate`](database_operations#overview) | Runs a Database Migration | [`--seed`](database_operations#overview)|
| [`migrate:drop-all`](database_operations#drop-all) | Drops all database tables | |
| [`migrate:fresh`](database_operations#migrate-fresh) | Drops all tables and performs migration | [`--seed`](database_operations#migrate-fresh)|
| [`migrate:refresh`](database_operations#migrate-refresh) | Drops all tables with down function and runs a Database Migration | [`--seed`](database_operations#migrate-refresh), <br>[`--step`](database_operations#migrate-refresh)|
| [`seed:run`](database_seeders#running-seeder) | Runs database seeders | |

<br>

### C. Local Servers <a id="local-servers"></a>

| Command | Description | Arguments |
|:-------:|-------------|-----------|
| `serve` | Runs local PHP standalone server | `--host`, `--port` |
| `serve:api` | Locally serves API using built in PHP server | `--host`, `--port` |
| `serve:docs` | Locally serves jekyll based user guide | `--host`, `--port` |

Each of these commands can accept the following flags:
- `--host` - Hostname or ipaddress
- `--port` - Overrides default port

<br>

### D. Notifications <a id="notifications"></a>

| Command | Description | Arguments |
|:-------:|-------------|-----------|
| [`notifications:migration`](notifications#notification-migration) | Creates new migration for the notifications table | |
| [`notifications:prune`](notifications#notification-prune) | Prunes table base on value older than days set | [`--days`](notifications#notification-prune) |

<br>

### E. Queue <a id="queue"></a>

| Command | Description | Arguments |
|:-------:|-------------|-----------|
| [`queue:migration`](queue#migration) | Creates new migration for queue table | |
| [`queue:worker`](queue#worker) | Starts a new queue worker | [`--once`](queue#worker), [`--max`](queue#worker), [`--queue`](queue#worker) |

<br>

### F. Testing <a id="testing"></a>

| Command | Description | Arguments |
|:-------:|-------------|-----------|
| [`test`](unit_tests#running-tests) | Performs a phpunit test | [`--feature`](unit_tests#test-suite), [`--unit`](unit_tests#test-suite), [`--coverage`](unit_tests#coverage), <br>[`--debug`](unit_tests#display-flags), [`--display-depreciations`](unit_tests#display-flags), <br>[`--display-errors`](unit_tests#display-flags), [`--display-incomplete`](unit_tests#display-flags), <br>[`--display-skipped`](unit_tests#display-flags), [`--fail-on-complete`](unit_tests#display-flags), <br>[`--fail-on-risky`](unit_tests#display-flags), [`--testdox`](unit_tests#display-flags), <br>[`--random-order`](unit_tests#behavior-flags), [`--reverse-order`](unit_tests#behavior-flags), <br>[`--stop-on-error`](unit_tests#behavior-flags), [`--stop-on-failure`](unit_tests#behavior-flags), <br>[`--stop-on-incomplete`](unit_tests#behavior-flags), [`--stop-on-risky`](unit_tests#behavior-flags), <br>[`--stop-on-skipped`](unit_tests#behavior-flags), [`--stop-on-warning`](unit_tests#behavior-flags) |
| [`tinker`](tinker#startup) | Launches tinker shell | |

<br>

### G. Tools <a id="tools"></a>

| Command | Description | Arguments |
|:-------:|-------------|-----------|
| [`log:clear`](debugging_and_logs#clear-logs) | Deletes existing log file | [`--all`](debugging_and_logs#clear-logs), <br>[`--app`](debugging_and_logs#clear-logs), <br>[`--cli`](debugging_and_logs#clear-logs), <br>[`--unit`](debugging_and_logs#clear-logs) |
| `tools:rm-profile-images` | Removes all profile images | |

<br>

### H. React <a id="react"></a>

| Command | Description | Arguments |
|:-------:|-------------|-----------|
| [`react:auth`](controllers_and_views#view-commands) | Generates page components for the auth controller | |
| [`react:component`](controllers_and_views#view-commands) | Generates a react component | [`--named`](controllers_and_views#view-commands) | 
| [`react:error`](controllers_and_views#view-commands) | Restores error/NotFound.jsx page component | |
| [`react:home`](controllers_and_views#view-commands) | Generates Index.jsx page component for the home controller | |
| [`react:page`](controllers_and_views#view-commands) | Generates a new react component for views | |
| [`react:profile`](controllers_and_views#view-commands) | Generates page components for the profile controller |  |
| [`react:util`](react_utils#overview) | Generates a JavaScript utility file to support React.js | |

<br>

## 4. Building Your Own Command <a id="build-command"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generating your own command is easy.  We will make a fake command called Foo as an example.  Simply run the following in your terminal under project root:

```sh
php console make:command Foo
```

The output of this command will be a file called `FooCommand.php` and will be located under `app/Lib/Console/Commands`.  The console application will throw an error until you set the name of the command.  The resulting file is shown below:

```php
namespace App\Lib\Console\Commands;
 
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Undocumented class
 */
class FooCommand extends Command {
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        //
    }

    /**
     * Executes the command
     *
     * @param InputInterface $input The input.
     * @param OutputInterface $output The output.
     * @return int A value that indicates success, invalid, or failure.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //
    }
}
```

Everything you need to build your own command is included in this file.  All relevant imports are listed at the top.  Each command you create contains two functions.  The configure function is where everything gets setup and the execute function performs actions associated with the command.

<br>

## 5. Command Helpers <a id="command-helpers"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Since this framework is fully Object-Oriented you can generate helper files to modularize tasks that need to be used across multiple commands.  Helpers can be found at `app\Lib\Console\Helpers`.

You can build your own command helper class by running the `make:command-helper` command.  Let's create a FooHelper class by running the following:

```sh
php console make:command-helper FooHelper
```

Once your run this command the file generated will look as follows:

```php
namespace App\Lib\Console\Helpers;

use Symfony\Component\Console\Command\Command;

/**
 * 
 */
class FooHelper {

}
```

When adding function we usually create those that are static.  We rarely need to create a new instance of a helper class so a constructor is not included in the output.

<br>

## 6. Tools <a id="tools"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Tools is a command helper class that contains functions that are commonly used with other commands.  To use the tools class simply used the following use statement:

```php
use Console\Helpers\Tools;
```
<br>

### A. border Function <a id="border">
The border prints a dashed line.

<br>

### B. createDirWithPrompt <a id="dir-with-prompt">
Creates a directory.  It checks if it already exists.  If not, user is asked to confirm the want to create a new directory.

**Parameters**

- `string $directory` - The full path for the directory to be created.
- `InputInterface $cmdInput` - The Symfony InputInterface object.
- `OutputInterface $cmdOutput` - The Symfony OutputInterface object.

**Returns**
- `array` - An array containing the contents of the $inputName variable.
- `int` - A value that indicates success, invalid, or failure.

**Example** 
```php
$directory = View::VIEW_PATH.$viewArray[0];
$isDirMade = Tools::createDirWithPrompt($directory, $input, $output);
if($isDirMade == Command::FAILURE) return Command::FAILURE;
```

In the above example we use the result of this function's call to test if there is a failure.  In this case we return Command::FAILURE.

<br>

### C. dotNotificationVerify <a id="dot-notification-Verify">
Checks if input is in dot notation.  If in dot notation the string is placed in an array where the first index is the directory name.  The second element is the file name.  The structure is shown below:

```PHP
["directory_name","file_name"]
```

If not in the `<directory_name>.<file_name>` an error message is displayed an a Command::FAILURE integer value is returned.

**Parameters**
- `string $inputName` - The name in `<directory_name>.<file_name>` format.

**Returns**
- `array` - An array containing the contents of the $inputName variable.
- `int` - If $inputName is not in correct format then Command::FAILURE is returned.

**Example** 
```php
$viewArray = Tools::dotNotationVerify('view-name', $input);
if($viewArray == Command::FAILURE) return Command::FAILURE;
```

In the above example we use the result of the dotNotification Verify function call to test if there are any failures.  In this case we return Command::FAILURE.

<br>

### D. info Function <a id="info">
The info function is used to present to the user logging information.  The following is an example of how to call this function:

```php
Tools::info("My message", Logger::INFO, Tools::BG_RED, Tools::TEXT_WHITE);
```

**Parameters**

- `string $message` - The message we want to show.
- `string $level` - The level of severity for log file.  The valid levels are `info`, `debug`, `warning`, `error`, `critical`, `alert`, and `emergency`.
- `string $background` - The background color.  This function supports `black`, `red`, `green`, `yellow`, `blue`, `magenta`, `cyan`, and `light-grey.
- `string $text` - The color of the text.  This function supports `black`, `white`, `dark-grey`, `red`, `green`, `brown`, `blue`, `magenta`, `cyan`, `light-cyan`, `light-grey`, `light-red`, `light green`, `light-blue`, and `light-magenta`.

We usually don't use the fourth argument since it may sometimes be ignored especially if you are using the terminal that comes with Visual Studio Code.

The standard Logger Alert Levels (Based on PSR-3)

| Severity Level | Description |
|:-------:|-------------|
| `Logger::EMERGENCY` | System is unusable (e.g., database crash, critical application failure). |
| `Logger::ALERT` | Immediate action required (e.g., entire system down, security breach). |
| `Logger::CRITICAL` | Critical errors (e.g., service failures, unexpected shutdowns). |
| `Logger::ERROR` | Application errors (e.g., exceptions, failed transactions, runtime errors). |
| `Logger::WARNING` | Warning messages (e.g., deprecated features, high memory usage). |
| `Logger::NOTICE` | Normal but significant events (e.g., config changes, recoverable issues). |
| `Logger::INFO` | Informational messages (e.g., user logins, API requests, background jobs). |
| `Logger::DEBUG` | Debugging details (e.g., variables, performance metrics). |

A `LoggerLevelException` is thrown if the `$level` parameter does not match a supported Logger Alert Level.

The following is a list of supported background colors (`const`):
1. `Tools::BG_BLACK`
2. `Tools::BG_RED`
3. `Tools::BG_GREEN`
4. `Tools::BG_YELLOW`
5. `Tools::BG_BLUE`
6. `Tools::BG_MAGENTA`
7. `Tools::BG_CYAN`
8. `Tools::BG_LIGHT_GREY`

The following text colors are supported (`const`):
1. `Tools::TEXT_BLACK`
2. `Tools::TEXT_WHITE`
3. `Tools::TEXT_DARK_GREY`
4. `Tools::TEXT_RED`
5. `Tools::TEXT_GREEN`
6. `Tools::TEXT_BROWN`
7. `Tools::TEXT_YELLOW`
8. `Tools::TEXT_BLUE`
9. `Tools::TEXT_MAGENTA`
10. `Tools::TEXT_CYAN`
11. `Tools::TEXT_LIGHT_CYAN`
12. `Tools::TEXT_LIGHT_GREY`
13. `Tools::TEXT_LIGHT_RED`
14. `Tools::TEXT_LIGHT_GREEN`
15. `Tools::TEXT_LIGHT_BLUE`
16. `Tools::TEXT_LIGHT_MAGENTA`

A `ConsoleException` is thrown if you use a value for text color in place of background color and vice versa.  If a value provided for `$background` and `$text` does not match any of the supported colors this exception is also thrown.

<br>

### E. writeFile Function <a id="write-file">
The writeFile function is what we used when we need to dump contents of a command to a file.  We use this for commands such as making controllers, models, and migrations.  

Here is an example call to this function for generating a new menu_acl json file.

```php
public static function makeMenuAcl(InputInterface $input): int {
    $menuName = $input->getArgument('acl-name');
    return Tools::writeFile(
        ROOT.DS.'app'.DS.strtolower($menuName)."_menu_acl.json",
        self::menuAcl($menuName),
        "Menu file"
    );
}
```

Since we need to name this file we grab the argument provided when running the command in the console.  The writeFile function contains the following arguments:
1. $path - Where the file will be written
2. $content - The contents of the file to be created
3. $name The name of the file, class, or other relevant information.

Use `DS` instead of `/` or `\` for cross-platform compatibility.

We return an integer to indicate success, invalid, or failure.

The path will usually contain the name variable, in this case, the name of the menu.  We always use the DIRECTORY_SEPARATOR (DS) constant instead of forward or backward slashes to ensure compatibility across different operating systems.

The `self::menuAcl($menuName)` calls a function that generates the content.  We prefer to use a separate function for the content to make the code clean and more maintainable.  

The third argument is used to populate the message that gets printed out to the terminal.  In the case the messages will be `Menu file successfully created` when file write is successful and `Menu file already exists` if the file already exists.