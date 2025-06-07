<h1 style="font-size: 50px; text-align: center;">Unit Tests</h1>

## Table of contents
1. [Overview](#overview)
2. [Creating Tests](#creating-tests)
3. [Running Tests](#running-tests)
4. [Testing Configuration](#configuration)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The Chappy.php framework has support for unit testing.  

<br>

## 2. Creating Tests <a id="creating-tests"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You can make your own PHPUnit test class by running the following command:

```sh
php console make:test ${testName}
```

By default the `make:test` places the file under the unit test suite.  To create a feature test run:

```sh
php console make:test ${testName} --feature
```

This version of the PHPUnit test class adds support for migrations and database seeding.

<br>


## 3. Running Tests <a id="running-tests"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Run all available tests.
```sh
php console test
```

**Run Tests By File**
Run all tests in a file that exists within the feature, unit, or both test suites.
```sh
php console test ${fileName}
```

**Run A Particular Test**
Run a specific test in a file.
```sh
php console test ${fileName}::${functionName}
```

If you have the same function in a class with the same name inside both test suites only the one found within the unit test suite will be executed.

**Run A Test Suite**
Run all test within a particular test suite by adding the `--unit` and/or `--feature` flags.

**Run Specific Test File Within A Suite**
You can run all test within a specific test file for an individual suite by specifying the file name and adding the `--unit` and/or `--feature` flags.

<br>

## 4. Testing Configuration <a id="configuration"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

The Chappy.php framework allows you to run your unit and feature tests against **SQLite (in-memory)** or **MySQL**, depending on your project's requirements.

This gives you flexibility for:
- ✅ Fast, isolated testing with SQLite
- ✅ Full-database compatibility testing with MySQL

### 🧪 SQLite (In-Memory) for Fast Testing

For quick and isolated test runs, SQLite is ideal. It requires **no setup** and runs entirely in memory.

#### **Configure PHPUnit to use SQLite**
Update your `phpunit.xml` and make sure `.env.testing` does not exist in your project root:

```xml
<env name="APP_ENV" value="testing"/>
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
...

To toggle on or off refresh, migrations, and seeding uncomment out the following:

```xml
...
<!-- Feature test configuration -->
<!-- <env name="DB_REFRESH" value="true"/> -->
<!-- <env name="DB_MIGRATE" value="true"/> -->
<!-- <env name="DB_SEED" value="true"/> -->
```

Benefits
- No external service needed
- Fastest test execution
- Great for CI pipelines

🐬 MySQL for Real-World Compatibility
To test real-world behavior like foreign key constraints or strict SQL modes (ONLY_FULL_GROUP_BY), use MySQL.

Configure PHPUnit to use MySQL by entering the required information in the MySQL/MariaDB section.  Leave the SQLite information commented out.
```xml
<php>
    <env name="APP_ENV" value="testing"/>
    <!-- In memory SQLite config -->
    <!-- <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/> -->

    <!-- MySQL/MariaDB test DB config -->
    <env name="DB_CONNECTION" value="mysql_testing"/>
    <env name="DB_HOST" value="127.0.0.1"/>
    <env name="DB_PORT" value="3306"/>
    <env name="DB_DATABASE" value=""/>
    <env name="DB_USERNAME" value=""/>
    <env name="DB_PASSWORD" value=""/>

    <!-- Feature test configuration -->
    <!-- <env name="DB_REFRESH" value="true"/> -->
    <!-- <env name="DB_MIGRATE" value="true"/> -->
    <!-- <env name="DB_SEED" value="true"/> -->
</php>
```

You can keep everything commented out except for the `APP_ENV` line and make a copy of the `.env.testing.sample` file and rename it to `.env.testing` and configure that file.

```
DB_CONNECTION=mysql_testing
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
DB_REFRESH=true
DB_MIGRATE=true
DB_SEED=true
```

✅ Requirements
- MySQL test database must exist (e.g., chappy_test)
- Test user must have privileges to create/drop tables