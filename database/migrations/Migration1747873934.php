<?php
namespace Database\Migrations;
use Core\Lib\Database\Schema;
use Core\Lib\Database\Blueprint;
use Core\Lib\Database\Migration;

/**
 * Migration class for the test table.
 */
class Migration1747873934 extends Migration {
    /**
     * Performs a migration for updating an existing table.
     *
     * @return void
     */
    public function up(): void {
        Schema::table('test', function (Blueprint $table) {
            // $table->dropColumns('bar');
            //$table->dropUnique('bar');
            // $table->renameUnique('bar', 'blah');
            // $table->dropPrimaryKey('id');
            // $table->dropForeign('foreign_key');
            // $table->renameForeign('foreign_key', 'new_fk');
        });
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
