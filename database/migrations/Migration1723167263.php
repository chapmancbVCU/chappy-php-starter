<?php
namespace Database\Migrations;
use Core\Lib\Database\Schema;
use Core\Lib\Database\Blueprint;
use Core\Lib\Database\Migration;

/**
 * Migration class for the contacts table.
 */
class Migration1723167263 extends Migration {
  /**
   * Performs a migration.
   *
   * @return void
   */
  public function up() {
    Schema::create('contacts', function (Blueprint $table) {
      $table->id();
      $table->string('fname', 150);
      $table->string('lname', 150);
      $table->string('email', 175);
      $table->string('home_phone', 50)->nullable();
      $table->string('cell_phone', 50)->nullable();
      $table->string('work_phone', 50)->nullable();
      $table->string('address', 255)->nullable();
      $table->string('address2', 255)->nullable();
      $table->string('city', 255);
      $table->string('state', 150);
      $table->string('zip', 50);
      $table->string('country', 155);
      $table->integer('user_id');
      $table->timestamps();
      $table->softDeletes();
      $table->index('user_id');
    });
  }

  /**
   * Undo a migration task.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('contacts');
  }
}
