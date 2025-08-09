<?php
namespace Database\Migrations;
use Core\Lib\Database\Schema;
use Core\Lib\Database\Blueprint;
use Core\Lib\Database\Migration;

/**
 * Migration class for the queue table.
 */
class Migration1754697616 extends Migration {
    /**
     * Performs a migration for a new table.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('queue', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->default('default');
            $table->index('queue');
            $table->text('payload');
            $table->text('exception')->nullable();
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('available_at');
            $table->index('available_at');
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();
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
