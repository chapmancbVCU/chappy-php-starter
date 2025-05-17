<?php
namespace Database\Migrations;
use Core\Lib\Database\Schema;
use Core\Lib\Database\Blueprint;
use Core\Lib\Database\Migration;

/**
 * Migration class for the migrations table.
 */
class Migration1722819683 extends Migration {
    /**
     * Performs a migration.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('migrations', function (Blueprint $table) {
          $table->id();
          $table->string('migration', 35);
          $table->index('migration');
          $table->integer('batch');
      });
    }

    /**
     * Undo a migration task.
     *
     * @return void
     */
    public function down(): void {
      Schema::dropIfExists('migrations');
    }
}
