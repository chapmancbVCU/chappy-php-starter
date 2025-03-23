<?php
namespace Core\Lib\Database;
use Core\Lib\Utilities\DateTime;
use Core\{DB, Helper};
use Console\Helpers\Tools;

/**
 * Supports database migration operations.
 */
abstract class Migration {
    protected $_db;
    protected $_columnTypesMap = [
        'int' => '_intColumn', 'integer' => '_intColumn', 'tinyint' => '_tinyintColumn', 'smallint' => '_smallintColumn',
        'mediumint' => '_mediumintColumn', 'bigint' => '_bigintColumn', 'numeric' => '_decimalColumn', 'decimal' => '_decimalColumn',
        'double' => '_doubleColumn', 'float' => '_floatColumn', 'bit' => '_bitColumn', 'date' => '_dateColumn',
        'datetime' => '_datetimeColumn', 'timestamp' => '_timestampColumn', 'time' => '_timeColumn', 'year' => '_yearColumn',
        'char' => '_charColumn', 'varchar' => '_varcharColumn', 'text' => '_textColumn'
    ];
    protected $_isCli;

    /**
     * Creates instance of Migration class.
     * 
     * @param string $isCli Contains value for interface type.
     */
    public function __construct($isCli) {
        $this->_db = DB::getInstance();
        $this->_isCli = $isCli;
    }

    /**
     * Setup acl table's initial fields during first db migration.
     *
     * @param string $table Name of acl table used to test that we are 
     * performing operations on correct table.
     * @return void
     */
    public function aclSetup($table) {
        $timestamp = DateTime::timeStamps();
        if($table == 'acl') {
            $this->_db->insert('acl', ['acl' => 'Admin', 'deleted' => 0, 'created_at' => $timestamp, 'updated_at' => $timestamp]);
        }
    }

    /**
     * Rollback the migration.
     */
    abstract public function down();

    /**
     * Get a new instance of Blueprint for schema building.
     *
     * @return \Core\Lib\Database\Blueprint
     */
    protected function schema() {
        return new \Core\Lib\Database\Blueprint($this->_db);
    }

    /**
     * Execute the migration.
     */
    abstract public function up();
}
