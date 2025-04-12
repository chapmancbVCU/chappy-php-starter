<h1 style="font-size: 50px; text-align: center;">Database Operations</h1>

## Table of contents
1. [Overview](#overview)
2. [Migration](#migration)
3. [Creating and Managing Migrations](#creating-and-managing-migrations)  
  * A. [Creating a New Table](#creating-a-new-table)  
  * B. [Updating an Existing Table](#updating-an-existing-table)  
  * C. [Dropping a Table](#dropping-a-table)  
4. [Supported Field Types & Modifiers](#field-types)
  * A. [Field Types](#types)
  * B. [Column Modifiers](#modifiers)
  * C. [Notes on Compatibility](#compatibility)
  * D. [Example Using Many Field Types](#example)
5. [Tips and Common Pitfalls](#tips)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Chappy.php supports full migration-based database management using its built-in CLI. This includes:
- Creating new tables
- Updating existing tables
- Dropping all tables
- Refreshing the schema

Migrations are managed using the `migrations` table, which keeps track of which files have been executed. This ensures that only **new** migration files are applied each time you run `php console migrate`.

<br>

## 2. Migration <a id="migration"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Performing a database migration is one of the first tasks that is completed when setting up a project.  By default, the `.env` file is configured to use SQLite.  If you want to use a MySQL or MariaDB as your database you will have to update the `.env` file.  An example is shown below:

```sh
# Set to mysql or mariadb for production
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
# Set to your database name for production
DB_DATABASE=my_db
DB_USER=root
DB_PASSWORD=my_secure-password
```

Next, create the database using your preferred method.  We like to use phpMyAdmin and Adminer.

Finally, you can run the migrate command shown below:

```php console migrate```

**Common Commands**
```php
# Run all pending migrations
php console migrate

# Refresh all tables (drop and re-run)
php console migrate:refresh

# Drop all tables without rerunning
php console migrate:drop-all
```

💡 The migrate:refresh command is great during local development when you want a clean slate.

<br>

## 3. Creating and Managing Migrations <a id="creating-and-managing-migrations"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

### A. Creating a New Table <a id="creating-a-new-table"></a>
Create a migration by running the make:migration command. An example is shown below for a table called foo:

```php console make:migration foo```

Once you perform this action a migration class is created with two functions called up and down. Up is used to create a new table or update an existing one. Down drops an existing table. We usually don't modify the down function. The output from the previous command is shown below:

```php
namespace Database\Migrations;
use Core\Lib\Database\Schema;
use Core\Lib\Database\Blueprint;
use Core\Lib\Database\Migration;

/**
 * Migration class for the foo table.
 */
class Migration1741215401 extends Migration {
    /**
     * Performs a migration.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('foo', function (Blueprint $table) {
          $table->id();

      });
    }

    /**
     * Undo a migration task.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('foo');
    }
}
```

The up function automatically creates a $table variable set to the value you entered when you ran the make:migration command along with a function call to create the table. In the code snippet below we added some fields.

```php
namespace Database\Migrations;
use Core\Lib\Database\Schema;
use Core\Lib\Database\Blueprint;
use Core\Lib\Database\Migration;

/**
 * Migration class for the foo table.
 */
class Migration1741215401 extends Migration {
    /**
     * Performs a migration.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('foo', function (Blueprint $table) {
            $table->id();
            $table->string('bar', 150)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('user_id');
            $table->index('user_id');
      });
    }

    /**
     * Undo a migration task.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('foo');
    }
}
```

**Common Field Methods**
- `$table->id()` — Creates an `id` column (auto-increment primary key)
- `$table->string('name', 255)` — Varchar column
- `$table->timestamps()` — Adds `created_at` and `updated_at`
- `$table->softDeletes()` — Adds `deleted_at` for soft deletion
- `$table->index('user_id')` — Adds an index

Run the migration and the console output, if successful, will be shown below:

<div style="text-align: center;">
  <img src="assets/migrate-output.png" alt="Migrate output example">
  <p style="font-style: italic;">Figure 1: Console output after running the migrate command.</p>
</div>

Open your database management software package and you will see that the table has been created.

<div style="text-align: center;">
  <img src="assets/foo-table.png" alt="New database table">
  <p style="font-style: italic;">Figure 2 - New database table after migration was performed</p>
</div>

<br>

### B. Updating an Existing Table <a id="updating-an-existing-table"></a>
To add or modify columns:

```sh
php console make:migration foo --update
```

Configure migration for update:

```php
public function up(): void {
    Schema::table('foo', function (Blueprint $table) {
        $table->string('bar', 150)->nullable()->default('Pending');
        $table->index('bar');
    });
}
```

🔄 Adding the `--update` flag generates a migration file for updating your table.

<br>

### C. Dropping a Table <a id="dropping-a-table"></a>
You can drop a table manually using:

```php
Schema::dropIfExists('foo');
```

This is automatically included in your migrations class.

<br>

## 4. Supported Field Types & Modifiers <a id="field-types"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Chappy.php’s migration system includes a flexible schema builder via the Blueprint class. It supports most standard SQL column types, modifiers, and constraints across both MySQL and SQLite databases. The table below outlines supported fields and how to define them.

<br>

### A. Field Types <a id="types"></a>

| Modifier | Description |
|:--------:|-------------|
| `id()` | Adds a primary key (`AUTO_INCREMENT` or `AUTOINCREMENT`) |
| `string('name', 255)`	| Adds a `VARCHAR` field (or `TEXT` for SQLite) |
| `text('description')` | Adds a `TEXT` field |
| `integer('age')` | Adds an `INT` (or `INTEGER` on SQLite) |
| `bigInteger('views')`	| Adds a `BIGINT` field |
| `mediumInteger('count')` | Adds a `MEDIUMINT` field |
| `smallInteger('flag')` | Adds a `SMALLINT` field |
| `tinyInteger('bool_flag')` | Adds a `TINYINT` (or `INTEGER` on SQLite) |
| `unsignedInteger('num')` | Adds `UNSIGNED INT` (MySQL only) or `INTEGER` (SQLite) |
| `decimal('amount', 10, 2)` | Adds a `DECIMAL` field with precision and scale |
| `float('ratio', 8, 2)` | Adds a `FLOAT` field |
| `double('rate', 16, 4)`	| Adds a `DOUBLE` field |
| `boolean('active')`	| Adds a `TINYINT(1)` to represent boolean values |
| `date('start_date')` | Adds a `DATE` field |
| `datetime('event_time')` | Adds a `DATETIME` field |
| `time('alarm')`	| Adds a `TIME `field |
| `timestamp('published_at')`	| Adds a `TIMESTAMP` field |
| `timestamps()` | Adds `created_at` and `updated_at` fields |
| `softDeletes()` | Adds a soft delete `deleted` field |
| `enum('status', [...])`	| Adds an `ENUM` (MySQL only, falls back to `TEXT` in SQLite) |
| `uuid('uuid')` | Adds a `CHAR(36)` for UUIDs (or `TEXT` in SQLite) |

<br>

### B. Column Modifiers <a id="modifiers"></a>

| Modifier | Description |
|:--------:|-------------|
| `nullable()` | Makes the column `NULL`-able |
| `default('value')` | Assigns a default value to the most recent column |
| `index('column')` | Adds an index to the specified column |
| `foreign('col', 'id', 'table')`	| Adds a foreign key constraint (MySQL only) |

<br>

### C. Notes on Compatibility <a id="compatibility"></a>
- 🐬 MySQL: All features are supported, including foreign keys and `ENUM`.
- 🐘 SQLite: Lacks native support for `ENUM`, foreign keys (unless enabled), and strict `UNSIGNED` types. Your migration code gracefully degrades in these cases.

<br>

### D. Example Using Many Field Types <a id="example"></a>
```php
Schema::create('products', function(Blueprint $table) {
    $table->id();
    $table->string('name', 255)->default('Unnamed');
    $table->decimal('price', 10, 2)->nullable();
    $table->boolean('in_stock')->default(true);
    $table->unsignedInteger('category_id');
    $table->foreign('category_id', 'id', 'categories');
    $table->timestamps();
    $table->softDeletes();
});
```

🔐 Reminder: Use foreign keys only when using MySQL. They’ll be ignored silently on SQLite.

<br>

## 5. Tips and Common Pitfalls <a id="tips"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
✅ Use `nullable()->default()` to safely add optional fields.
⚠️ **Foreign keys and ENUM** types are only supported in MySQL.
🐘 **SQLite** ignores unsupported column modifiers silently.
🧪 Always verify your migrations using a database viewer like phpMyAdmin or SQLiteBrowser.
📄 Log messages in the CLI will show `SUCCESS: Adding Column..`. or `SUCCESS: Creating Table....`