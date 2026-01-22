<h1 style="font-size: 50px; text-align: center;">PHPUnit</h1>

## Table of contents
1. [Overview](#overview)
2. [SQLite (In-Memory) for Fast Testing](#sqlite)
3. [MySQL for Real-World Compatibility](#mysql)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The Chappy.php framework allows you to run your unit and feature tests against **SQLite (in-memory)** or **MySQL**, depending on your project's requirements.

This gives you flexibility for:
- ✅ Fast, isolated testing with SQLite
- ✅ Full-database compatibility testing with MySQL

<br>

## 2. SQLite (In-Memory) for Fast Testing <a id="sqlite"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
For quick and isolated test runs, SQLite is ideal. It requires **no setup** and runs entirely in memory.

The sqlite file is located at `database\database.sqlite`.

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

<br>

## 3. MySQL for Real-World Compatibility <a id="mysql"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>



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