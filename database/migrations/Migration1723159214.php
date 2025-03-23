<?php
namespace Database\Migrations;
use Core\Lib\Database\Blueprint;
use Core\Lib\Database\Migration;
use Core\Lib\Database\Schema;
/**
 * Migration class for the acl table.
 */
class Migration1723159214 extends Migration {
  /**
   * Performs a migration.
   *
   * @return void
   */
  public function up() {
    Schema::create('acl', function (Blueprint $table) {
      $table->id();                      // Auto-incrementing primary key
      $table->string('acl', 25);         // VARCHAR(25)
      $table->softDeletes();             // Adds deleted column
      $table->timestamps();              // Adds created_at & updated_at
    });

    $this->aclSetup('acl');  // Ensures initial ACL setup
  }

  /**
   * Undo a migration task.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('acl');
  }
}
