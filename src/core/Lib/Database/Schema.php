<?php
namespace Core\Lib\Database;

use Core\DB;

/**
 * The migration API that delegates table creation and modifications to the 
 * Blueprint class.
 */
class Schema {
    /**
     * Create a new table.
     *
     * @param string $table The name of the table.
     * @param callable $callback The callback function.
     * @return void
     */
    public static function create(string $table, callable $callback): void {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        $blueprint->create();
    }

    /**
     * Drop a table if it exists.
     *
     * @param string $table The name of the table.
     * @return void
     */
    public static function dropIfExists($table): void {
        $sql = "DROP TABLE IF EXISTS {$table}";
        DB::getInstance()->query($sql);
    }

    /**
     * Modify an existing table.
     *
     * @param string $table The name of the table.
     * @param callable $callback The callback function.
     * @return void
     */
    public static function table($table, callable $callback) {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        $blueprint->update();
    }
}