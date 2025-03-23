<?php
namespace Database\Migrations;
use Core\Lib\Database\Blueprint;
use Core\Lib\Database\Schema;
use Core\Lib\Database\Migration;

/**
 * Migration class for the user_sessions table.
 */
class Migration1731861283 extends Migration {
    /**
     * Performs a migration.
     *
     * @return void
     */
    public function up() {
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_id');
            $table->string('session', 255);
            $table->string('user_agent', 255);
            $table->index('user_id');
            $table->index('session');
        });
    }

    /**
     * Undo a migration task.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('user_sessions');
    }
}
