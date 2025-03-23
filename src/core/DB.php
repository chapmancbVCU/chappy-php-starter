<?php
namespace Core;
use \PDO;
use Exception;
use Core\Helper;
use \PDOException;
use Core\Lib\Utilities\Arr;
use Core\Lib\Logging\Logger;
use Core\Lib\Utilities\ArraySet;
/**
 * Support database operations.
 */
class DB {
    private $_count = 0;
    private $_dbDriver;
    private $_error = false;
    private $_fetchStyle = PDO::FETCH_OBJ;
    private static $_instance = null;
    private $_lastInsertID = null;
    private $_pdo;
    private $_query;
    private $_result;
    
    /**
     * This constructor creates a new PDO object as an instance variable.  If 
     * there are any failures the application quits with an error message.
     */
    private function __construct() {
        $config = require ROOT.DS.'config'.DS.'database.php';
        $dbConfig = $config['connections'][$config['default']] ?? null;
        
        if (!$dbConfig) {
            throw new Exception("Database configuration not found.");
        }

        try {
            if ($dbConfig['driver'] === 'sqlite') {
                if (!file_exists($dbConfig['database'])) {
                    touch($dbConfig['database']);
                }
                $dsn = "sqlite:" . $dbConfig['database'];
                $this->_pdo = new PDO($dsn);
                $this->_pdo->exec("PRAGMA foreign_keys=ON;"); // Enable foreign keys for SQLite
            } else {
                $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
                $this->_pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
                $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $this->_pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            }
            $this->_dbDriver = $dbConfig['driver']; // Store database driver
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /** 
     * Constructs join statements for SQL queries.
     *
     * @param array $join Data such as table, conditions, and aliases needed 
     * to construct join query.  Default value is an empty array.
     * @return string The join component of a query.
     */
    protected function _buildJoin($join=[]) {
        $table = $join[0];
        $condition = $join[1];
        $alias = $join[2];
        $type = (isset($join[3]))? strtoupper($join[3]) : "INNER";
        $jString = "{$type} JOIN {$table} {$alias} ON {$condition}";
        return " " . $jString;
    }

    /**
     * Getter function for the private _count variable.
     *
     * @return int The number of results found in an SQL query.
     */
    public function count() {
        return $this->_count;
    }

    /**
     * Performs delete operation against SQL database.
     *
     * Example setup:
     * $contacts = $db->delete('contacts', 3);
     * 
     * @param string $table The name of the table that contains the record 
     * we want to delete.
     * @param int $id The primary key for the record we want to remove from a 
     * database table.
     * @return bool True if delete operation is successful.  Otherwise, we 
     * return false.
     */
    public function delete($table, $id) {
        $sql = "DELETE FROM {$table} WHERE id = ?";
        return !$this->query($sql, [$id])->error();
    }

    /**
     * Getter function for the $_error variable.
     *
     * @return bool The value for the $_error flag.
     */
    public function error() {
        return $this->_error;
    }

    /**
     * Performs find operation against the database.  The user can use 
     * parameters such as conditions, bind, order, limit, and sort.
     * 
     * Example setup:
     * $contacts = $db->find('users', [
     *     'conditions' => ["email = ?"],
     *     'bind' => ['chad.chapman@email.com'],
     *     'order' => "username",
     *     'limit' => 5,
     *     'sort' => 'DESC'
     * ]);
     *
     * @param string $table The name or the table we want to perform 
     * our query against
     * @param array $params An associative array that contains key value pair 
     * parameters for our query such as conditions, bind, limit, offset, 
     * join, order, and sort.  The default value is an empty array.
     * @param bool|string $class A default value of false, it contains the 
     * name of the class we will build based on the name of a model.
     * @return bool|array An array of object returned from an SQL query.
     */
    public function find($table, $params = [], $class = false) {
        if($this->_read($table, $params, $class)) {
            return $this->results();
        }
        return false;
    }

    /**
     * Returns the first result performed by an SQL query.  It is a wrapper
     * for the _read function for this purpose.
     *
     * @param @param string $table The name or the table we want to perform 
     * our query against.
     * @param array $params An associative array that contains key value pair 
     * parameters for our query such as conditions, bind, limit, offset, 
     * join, order, and sort.  The default value is an empty array.
     * @param bool|string  $class A default value of false, it contains the 
     * name of the class we will build based on the name of a model.
     * @return bool|array An associative array of results returned from an SQL 
     * query.
     */
    public function findFirst($table, $params = [], $class = false) {
        if($this->_read($table, $params, $class)) {
            return $this->first();
        }
        return false;
    }

    /** 
     * Returns number of records in a table.
     *
     * @param string $table  The name or the table we want to perform 
     * our query against.
     * @param array $params An associative array that contains key value pair 
     * parameters for our query such as conditions, bind, limit, offset, 
     * join, order, and sort.  The default value is an empty array.
     * @return int $count The number of records in a table.
     */
    public function findTotal($table, $params=[]) {
        $count = 0;
        if($this->_read($table, $params, false, true)) {
            $count = $this->first()->count;
        }
        return $count;
    }

    /**
     * Returns first result in the _result array.
     *
     * @return array|object An associative array that is the first object 
     * in a _result.
     */
    public function first() {
        return (!empty($this->_result)) ? $this->_result[0] : [];
    }

    /**
     * Returns columns for a table.
     *
     * @param string $table The name of the table we want to retrieve
     * the column names.
     * @return array An array of objects where each one represents a column 
     * from a database table.
     */
    public function getColumns($table) {
        $dbDriver = $this->_pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        if ($dbDriver === 'sqlite') {
            return $this->query("PRAGMA table_info({$table})")->results();
        } else {
            return $this->query("SHOW COLUMNS FROM {$table}")->results();
        }
    }

    /**
     * An instance of this class set as a variable.  To be used in other 
     * class because we can't use $this.
     *
     * @return self The instance of this class.
     */
    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Returns instance of PDO class.
     *
     * @return PDO The PDO object.
     */
    public function getPDO(): PDO {
        return $this->_pdo;
    }

    /**
     * Perform insert operations against the database.
     * 
     * Example setup:
     * $fields = [
     *   'fname' => 'John',
     *   'lname' => 'Doe',
     *   'email' => 'example@email.com'
     * ];
     * $contacts = $db->insert('contacts', $fields);
     * 
     * @param string $table The name of the table we want to perform the 
     * insert operation.
     * @param array $fields An associative array of key value pairs.  The key 
     * is the name of the database field and the value is the value we will 
     * set to a particular field.  The default value is an empty array.
     * @return bool Report whether or not the operation was successful.
     */
    public function insert($table, $fields = []) {
        if (empty($fields)) {
            Logger::log("Attempted to insert empty data into {$table}", 'error');
            return false;
        }
    
        // Remove ID field from insertion if it's an autoincrement field
        if (isset($fields['id'])) {
            unset($fields['id']);
        }
    
        $fieldString = implode(',', Arr::keys($fields));
        $valueString = implode(',', Arr::fill(0, count($fields), '?'));
        $values = Arr::values($fields);
    
        $sql = "INSERT INTO {$table} ({$fieldString}) VALUES ({$valueString})";
    
        Logger::log("Preparing INSERT query: $sql | Params: " . json_encode($values), 'debug');

        if (!$this->query($sql, $values)->error()) {
            return true;
        }
        return false;
    }

    /**
     * The primary key ID of the last insert operation.
     *
     * @return int The primary key ID from the last insert operation.
     */
    public function lastID() {
        return $this->_lastInsertID;
    }

    /**
     * Performs database query operations that includes prepare, 
     * binding, execute, and fetch.  
     *
     * @param string $sql The database query we will submit to the database.
     * @param array $params An associative array that contains key value pair 
     * parameters for our query such as conditions, bind, limit, offset, 
     * join, order, and sort.  The default value is an empty array.
     * @param bool|string $class A default value of false, it contains the 
     * name of the class we will build based on the name of a model.
     * @return DB The results of the database query.  If the operation 
     * is not successful the $_error instance variable is set to true and is 
     * returned.
     */
    public function query($sql, $params = [], $class = false) {
    $this->_error = false;
    $startTime = microtime(true);

    if ($this->_query = $this->_pdo->prepare($sql)) {
        $x = 1;
        foreach ($params as $param) {
            $this->_query->bindValue($x, $param);
            $x++;
        }

        if ($this->_query->execute()) {
            $executionTime = microtime(true) - $startTime;
            $this->_result = $class ? $this->_query->fetchAll(PDO::FETCH_CLASS, $class) : $this->_query->fetchAll($this->_fetchStyle);
            $this->_count = $this->_query->rowCount();
            $this->_lastInsertID = $this->_pdo->lastInsertId();

            // If multiple rows updated, log a summary
            if ($this->_count > 1) {
                Logger::log("Executed Batch Query: {$this->_count} rows affected | Execution Time: " . number_format($executionTime, 5) . "s", 'debug');
            } else {
                Logger::log("Executed Query: $sql | Params: " . json_encode($params) . " | Rows Affected: {$this->_count} | Execution Time: " . number_format($executionTime, 5) . "s", 'debug');
            }
        } else {
            $this->_error = true;
            Logger::log("Database Error: " . json_encode($this->_query->errorInfo()) . " | Query: $sql | Params: " . json_encode($params), 'error');
        }
    } else {
        Logger::log("Failed to prepare query: $sql | Params: " . json_encode($params), 'error');
    }

    return $this;
}

    
    

    /**
     * Supports SELECT operations that maybe ran against a SQL database.  It 
     * supports the ability to order and limit the number of results returned 
     * from a database query.  The user can use parameters such as conditions, 
     * bind, order, limit, and sort.
     *
     * @param string $table The name of the table that contains the 
     * record(s) we want to find.
     * @param array $params An associative array that contains key value pair 
     * parameters for our query such as conditions, bind, limit, offset, 
     * join, order, and sort.  The default value is an empty array.
     * @param bool|string  $class A default value of false, it contains the 
     * name of the class we will build based on the name of a model.
     * @param bool $count Boolean switch for turning on support for count 
     * operations.  Default value is false.
     * @return bool A true or false value depending on a successful operation.
     */
    protected function _read($table, $params=[], $class=false, $count=false) {
        $columns = '*';
        $joins = "";
        $conditionString = '';
        $bind = [];
        $order = '';
        $limit = '';
        $offset = '';

        // Detect SQLite
        $dbDriver = $this->_pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        // Fetch Style
        if(isset($params['fetchStyle'])){
            $this->_fetchStyle = $params['fetchStyle'];
        }

        // Conditions
        if(isset($params['conditions'])) {
            if(Arr::isArray($params['conditions'])) {
                foreach($params['conditions'] as $condition) {
                    // Convert `!=` to `<>` for SQLite
                    if ($dbDriver === 'sqlite') {
                        $condition = str_replace('!=', '<>', $condition);
                    }
                    $conditionString .= ' ' . $condition . ' AND';
                }
                $conditionString = trim($conditionString);
                $conditionString = rtrim($conditionString, ' AND');
            } else {
                $conditionString = $params['conditions'];
                if ($dbDriver === 'sqlite') {
                    $conditionString = str_replace('!=', '<>', $conditionString);
                }
            }
            if($conditionString != '') {
                $conditionString = ' WHERE ' . $conditionString;
            }
        }

        // Columns
        if(Arr::exists($params, 'columns')){
            $columns = $params['columns'];
        }

        // Joins and raw joins
        if(Arr::exists($params, 'joins')){
            foreach($params['joins'] as $join){
                $joins .= $this->_buildJoin($join);
            }
            $joins .= " ";
        }

        if(Arr::exists($params, 'joinsRaw')) {
            foreach($params['joinsRaw'] as $raw) {
                $joins .= ' ' .$raw;
            }
        }

        // Bind
        if(Arr::exists($params, 'bind')) {
            $bind = $params['bind'];
        }

        // Order
        if(Arr::exists($params, 'order')) {
            $order = ' ORDER BY ' . $params['order'];
        }

        // Limit
        if(Arr::exists($params, 'limit')) {
            $limit = ' LIMIT ' . $params['limit'];
        }

        // Offset
        if(Arr::exists($params, 'offset')) {
            $offset = ' OFFSET ' . $params['offset'];
        }

        $sql = ($count) ? "SELECT COUNT(*) as count " : "SELECT {$columns} ";
        $sql .= "FROM {$table}{$joins}{$conditionString}{$order}{$limit}{$offset}";

        if($this->query($sql, $bind, $class)) {
            if(!count($this->_result)) return false;
            return true;
        }
        return false;
    }

    /**
     * Returns value of query results.
     *
     * @return array An array of objects that contain results of a database 
     * query.
     */
    public function results() {
        return $this->_result;
    }

    public function tableExists($table) {
        if ($this->_dbDriver === 'sqlite') {
            $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name=:table";
        } else {
            $sql = "SHOW TABLES LIKE :table";
        }

        $this->query($sql, ['table' => $table]);
        return $this->count() > 0;
    }

    /**
     * Performs update operation on a SQL database record.
     *
     * Example setup:
     * $fields = [
     *   'fname' => 'John',
     *   'email' => 'example@email.com'
     * ];
     * $contacts = $db->update('contacts', 3, $fields);
     * 
     * @param string $table $table The name of the table that contains the 
     * record we want to update.
     * @param int $id The primary key for the record we want to remove from a 
     * database table.
     * @param array $fields The value of the fields we want to set for the 
     * database record.  The default value is an empty array.
     * @return bool True if the update operation is successful.  Otherwise, 
     * we return false.
     */
    public function update($table, $id, $fields = []) {
        $setString = implode('=?, ', Arr::keys($fields)) . '=?';
        $values = (new ArraySet($fields))->values()->push($id)->all();

        $sql = "UPDATE {$table} SET {$setString} WHERE id = ?";

        return !$this->query($sql, $values)->error();
    }  

    /**
     * Check if a value exists in a JSON or text-based column
     *
     * @param string $table The table name
     * @param string $column The column name (JSON or text-based)
     * @param mixed $value The value to search for
     * @return bool True if value exists, False otherwise
     */
    public function valueExistsInColumn($table, $column, $value) {
        $dbDriver = $this->_pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        
        if ($dbDriver === 'mysql') {
            $condition = "JSON_CONTAINS({$column}, ?)";
        } else {
            $condition = "{$column} LIKE ?";
            $value = '%"'.$value.'"%'; // Adjust value for SQLite string search
        }

        $query = "SELECT COUNT(*) as count FROM {$table} WHERE {$condition}";
        $result = $this->query($query, [$value])->first();

        return $result && isset($result->count) && $result->count > 0;
    }

}