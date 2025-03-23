<h1 style="font-size: 50px; text-align: center;">Console</h1>

## Table of contents
1. [Overview](#overview)
2. [Summary of Available Commands](#summary-of-available-commands)
3. [Building Your Own Command](#build-command)
4. [Command Helpers](#command-helpers)
5. [Tools](#tools)
    * A. [border Function](#border)
    * B. [info Function](#info)
    * C. [writeFile Function](#write-file)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The console command is used to manage and perform tasks related to this framework. Just like Laravel's Artisan command, this frameworks Command Line Interface (CLI) is based off of the Symfony Console component.  Check out Symfony's Console component [page](https://symfony.com/doc/current/console.html) for documentation on how to set up your command.  

You can run a console command following the following syntax:

```sh
php console ${command_name} ${argument}
```

An example of a command that requires arguments is demonstrated below:

```sh
php console test:run-test Test
```

Where Test is the name of the file containing the test. Typing php console in the command line at project root will display all of the available commands. Each of the supported commands will be covered in their respective sections in this user guide.

If there is a command you would like for us to support you can submit an issue [here](https://github.com/chapmancbVCU/chappy-php/issues).
<br>

## 2. Summary of Available Commands <a id="summary-of-available-commands"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Below is a list of available commands. Most items in this list contains a link to the page that describes an individual command.  This list can be viewed in the terminal by typing ```php console``` under project root.

| Command | Description |
|:-------:|-------------|
| init:mk-profile-images-dir | Builds Profile Image Dir |
| log:clear | Deletes existing log file |
| make:acl | Generates a new menu_acl json file |
| make:api | Generates or updates api-docs |
| make:command | Generates a new command class |
| make:command-helper | Generates helper class that supports console commands |
| make:component | Generates component based on flags that are set |
| make:controller| Generates a new controller class |
| make:layout | Generates a new layout |
| make:menu | Generates a new menu file |
| make:migration | Generates a Database Migration |
| make:model | Generates a new model file |
| make:seeder | Creates a new database seeder class |
| make:test | Generates a new test class |
| make:view | Create a new view |
| migrate | Runs a Database Migration |
| migrate:drop-all | Drops all database tables |
| migrate:refresh | Drops all tables and runs a Database Migration |
| seed:run | Runs database seeders |
| serve | Runs local PHP server without having to use Apache2 or Nginx |
| serve:api | Locally serves API using built in PHP server |
| serve:docs | Locally serves jekyll based user guide |
| test | Performs a phpunit test |
| tools:mk-env | Creates the .env file |
| tools:rm-profile-images | Removes all profile images |

## 3. Building Your Own Command <a id="build-command"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generating your own command is easy.  We will make a fake command called Foo as an example.  Simply run the following in your terminal under project root:

```sh
php console make:command Foo
```

The output of this command will be a file called `FooCommand.php` and will be located under `src/console/commands`.  The resulting file is shown below:

```php
namespace Console\Commands;
 
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

## 4. Command Helpers <a id="command-helpers"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Since this framework is fully Object-Oriented you can generate helper files to modularize tasks that need to be used across multiple commands.  Helpers can be found at `src\console\helpers`.

You can build your own command helper class by running the `make:command-helper` command.  Let's create a FooHelper class by running the following:

```sh
php console make:helper Foo Helper
```

Once your run this command the file generated will look as follows:

```php
namespace Console\Helpers;

use Symfony\Component\Console\Command\Command;

/**
 * 
 */
class FooHelper {

}
```

When adding function we usually create those that are static.  We rarely need to create a new instance of a helper class so a constructor is not included in the output.
<br>

## 5. Tools <a id="tools"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Tools is a command helper class that contains functions that are commonly used with other commands.  To use the tools class simply used the following use statement:

```php
use Console\Helpers\Tools;
```
<br>

#### A. border Function <a id="border">
The border prints a dashed line.
<br>

#### B. info Function <a id="info">
The info function is used to present to the user logging information.  The following is an example of how to call this function:

```php
Tools::info("My message", 'info', 'red', 'white')
```

The first argument is you message, the second argument is the severity level for cli logging, the third is the background color, and finally the fourth is the text color.  We usually don't use the fourth argument since it may sometimes be ignored especially if you are using the terminal that comes with Visual Studio Code.

The standard Logger Alert Levels (Based on PSR-3)

| Severity Level | Description |
|:-------:|-------------|
| emergency | System is unusable (e.g., database crash, critical application failure). |
| alert | Immediate action required (e.g., entire system down, security breach). |
| critical | Critical errors (e.g., service failures, unexpected shutdowns). |
| error | Application errors (e.g., exceptions, failed transactions, runtime errors). |
| warning | Warning messages (e.g., deprecated features, high memory usage). |
| notice | Normal but significant events (e.g., config changes, recoverable issues). |
| info | Informational messages (e.g., user logins, API requests, background jobs). |
| debug | Debugging details (e.g., variables, performance metrics). |

The following is a list of supported background colors:
1. black
2. red
3. green
4. yellow
5. blue
6. magenta
7. cyan
8. light-grey

The following text colors are supported:
1. black
2. white
3. dark-grey
4. red
5. green
6. brown
7. yellow
8. blue
9. magenta
10. cyan
11. light-cyan
12. light-grey
13. light-red
14. light-green
15. light-blue
16. light-magenta
<br>

#### C. writeFile Function <a id="write-file">
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

We return an integer to indicate success, invalid, or failure.

The path will usually contain the name variable, in this case, the name of the menu.  We always use the DIRECTORY_SEPARATOR (DS) constant instead of forward or backward slashes to ensure compatibility across different operating systems.

The `self::menuAcl($menuName)` calls a function that generates the content.  We prefer to use a separate function for the content to make the code clean and more maintainable.  

The third argument is used to populate the message that gets printed out to the terminal.  In the case the messages will be `Menu file successfully created` when file write is successful and `Menu file already exists` if the file already exists.