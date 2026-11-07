<h1 style="font-size: 50px; text-align: center;">Static Analysis (PHPStan)</h1>

## Table of contents
1. [Overview](#overview)
2. [Running the Analysis](#running)
3. [Configuration](#configuration)
4. [The Analysis Bootstrap](#bootstrap)
5. [Personal Overrides](#overrides)
6. [Adopting PHPStan in an Existing Application](#baseline)
7. [Using a Different Analyser](#other_tools)
8. [Known Limitations](#limitations)
9. [Framework Development](#framework)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Static analysis finds bugs by reading your code instead of running it: calls to methods that do not exist, wrong argument types, missing return values, and code that can never work.  It catches whole classes of mistakes before you write a test or open a browser.

New applications include [PHPStan](https://phpstan.org/) as a development dependency, a configuration file, and a Composer script, so analysis works without any setup.  It checks your application code in `app/` and `database/`.  The framework's code is not analysed as part of your application.

PHPStan is only needed during development.  It is not installed when you run `composer install --no-dev` for production.

<br>

## 2. Running the Analysis <a id="running"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Run the analysis from your project root:

```sh
composer analyse
```

A clean run ends with `[OK] No errors`.  Otherwise each problem is listed with its file, line, and an identifier such as `method.notFound`.  The identifier links to an explanation at `https://phpstan.org/error-identifiers/<identifier>`.

PHPStan options can be added after `--`:

```sh
# Try a stricter level without changing the configuration
composer analyse -- --level=6

# Analyse a single folder
composer analyse -- app/Controllers
```

Results are cached in `storage/cache/phpstan`, so later runs only re-check changed files.  To clear the cache:

```sh
vendor/bin/phpstan clear-result-cache
```

<br>

## 3. Configuration <a id="configuration"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
PHPStan reads `phpstan.neon.dist` from the directory it is run from, which is your project root.  The file included with new applications looks like this:

```yaml
parameters:
    level: 5

    paths:
        - app
        - database

    bootstrapFiles:
        - vendor/chappy-php/chappy-php-framework/src/scripts/analysis_bootstrap.php

    tmpDir: storage/cache/phpstan
```

- `level` - How strict the analysis is, from 0 (basic checks) to 10 (strictest).  Level 5 checks method calls, argument types, and return types without demanding complete type information everywhere.
- `paths` - The folders that are analysed.  Add other folders that contain your PHP code here.
- `bootstrapFiles` - Files loaded before analysis.  See [The Analysis Bootstrap](#bootstrap).
- `tmpDir` - Where the result cache is stored.  `storage/cache/` is already ignored by Git.

Paths in the file are relative to the file itself, so `app` always means your project's `app/` folder.

View templates in `resources/views` are not analysed.  They rely on `$this` from the view at render time, which PHPStan cannot know.

See the [PHPStan configuration reference](https://phpstan.org/config-reference) for every option.

<br>

## 4. The Analysis Bootstrap <a id="bootstrap"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
At runtime, `index.php` and `console` define the path constants `DS` and `ROOT`, and the framework's bootstrap defines `CHAPPY_ROOT`, `CHAPPY_BASE_PATH`, and the global helper functions such as `env()`, `redirect()`, `flashMessage()`, and `console_info()`.  PHPStan never runs those files, so without help it reports every use of them as undefined.

The framework includes `src/scripts/analysis_bootstrap.php` for this purpose.  It defines the constants and loads the helper functions without starting the application: it does not read `.env` or your configuration, connect to the database, or start a session.  Your configuration loads it through `bootstrapFiles`.

If you see errors such as `Function env not found` or `Constant ROOT not found`, check that the `bootstrapFiles` entry is present and that you are running PHPStan from the project root.

<br>

## 5. Personal Overrides <a id="overrides"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
`phpstan.neon.dist` is committed and shared with everyone working on the project.  For settings only you need, create `phpstan.neon` in the project root.  PHPStan uses `phpstan.neon` instead of `phpstan.neon.dist` when both exist, and `phpstan.neon` is ignored by Git.

Start your override by including the shared file, then change what you need:

```yaml
includes:
    - phpstan.neon.dist

parameters:
    level: 6
```

The first line of PHPStan's output shows which file is in use, for example `Note: Using configuration file /path/to/project/phpstan.neon.`.

<br>

## 6. Adopting PHPStan in an Existing Application <a id="baseline"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
An existing application may report many errors on its first run.  Rather than fixing them all at once, record them in a baseline file.  PHPStan then reports only new errors, so new code is held to the standard while you work through the old ones.

Generate the baseline:

```sh
composer analyse -- --generate-baseline
```

This writes `phpstan-baseline.neon` to the project root.  Include it at the top of `phpstan.neon.dist` and commit both files:

```yaml
includes:
    - phpstan-baseline.neon

parameters:
    level: 5
    # ...
```

When you fix an error that is in the baseline, PHPStan reports that the baseline entry no longer matches.  Run `composer analyse -- --generate-baseline` again to remove it.  The baseline can only shrink this way, which makes it a record of remaining work.

To add PHPStan to an application created before it was included, see [Upgrading to 4.0](upgrading_to_4#static_analysis).

<br>

## 7. Using a Different Analyser <a id="other_tools"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
PHPStan is included by default, but you can use another tool such as Psalm instead.  The analysis bootstrap does not depend on PHPStan, so point your tool at the same file.  For Psalm, use the `autoloader` attribute in `psalm.xml`:

```xml
<psalm
    autoloader="vendor/chappy-php/chappy-php-framework/src/scripts/analysis_bootstrap.php"
>
```

To remove PHPStan, delete `phpstan.neon.dist`, remove `phpstan/phpstan` from `require-dev`, and remove the `analyse` script from `composer.json`.

<br>

## 8. Known Limitations <a id="limitations"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
**Finder results**  
Model finders such as `findById()` and `findFirst()` are declared to return `bool|object`.  PHPStan cannot tell that a successful result is an instance of your model, so a mistake on the result is reported as a call on `bool|object` instead of an undefined method:

```php
$user = Users::findById($id);
$user->notAMethod();   // Reported as: Cannot call method notAMethod() on bool|object.
```

Check the result before using it.  This also handles records that do not exist:

```php
$user = Users::findById($id);
if(!$user) {
    redirect('');
    return;
}
```

**Views**  
Templates in `resources/views` are not analysed.  See [Configuration](#configuration).

<br>

## 9. Framework Development <a id="framework"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The framework repository has its own PHPStan configuration for working on the framework itself.  It analyses the framework's `src/` folder with a committed baseline and runs with `composer analyse` from the framework's root.  These files are left out of tagged framework releases, but they are present in `vendor/` when the framework is installed from source (for example, `dev-main`).  Either way, running PHPStan from your application root uses your application's configuration, not the framework's.
