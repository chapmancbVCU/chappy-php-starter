<?php
namespace Database\Migrations;
use Core\DB;
use Core\Lib\Database\Schema;
use Core\Lib\Database\Blueprint;
use Core\Lib\Database\Migration;

/**
 * Migration class for the test table.
 */
class Migration1747787882 extends Migration {
    /**
     * Performs a migration for a new table.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('test', function (Blueprint $table) {
            $table->id();
            $table->string('foo', 25)->nullable();
            $table->integer('my_index');
            $table->index('my_index');
            $table->string('bar', 25)->unique();
            //$table->integer('foreign_key');
            //$table->foreign('foreign_key', 'id', 'users');
        });
        // DB::getInstance()->insert('users', ['id' => 1, 'username' => 'test']);
        // // Insert dummy data
        // DB::getInstance()->query("
        //     INSERT INTO test (foo, my_index, bar, foreign_key) VALUES
        //     ('Alpha', 101, 'bar-1', 1),
        //     ('Bravo', 202, 'bar-2', 1),
        //     ('Charlie', 303, 'bar-3', 1)
        // ");
    }

    /**
     * Undo a migration task.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('test');
    }
}
