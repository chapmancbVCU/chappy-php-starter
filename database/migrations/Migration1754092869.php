<?php
namespace Database\Migrations;
use Core\Lib\Database\Schema;
use Core\Lib\Database\Blueprint;
use Core\Lib\Database\Migration;

/**
 * Migration class for the queue table.
 */
class Migration1754092869 extends Migration {
    /**
     * Performs a migration for a new table.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('queue', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->default('default');
            $table->text('payload');
            $table->text('exception');
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('available_at');
            $table->timestamp('failed_at');
            $table->timestamp('created_at');
        });
    }

    /**
     * Undo a migration task.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('queue');
    }
}
