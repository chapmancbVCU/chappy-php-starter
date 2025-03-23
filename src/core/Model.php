<?php
namespace Core;
use Core\Helper;
use Core\Lib\Utilities\Arr;
use Core\Lib\Logging\Logger;
use Core\Lib\Utilities\ArraySet;
use Core\Lib\Utilities\DateTime;

/**
 * Parent class for our models.  Takes functions from DB wrapper and extract 
 * functionality further to make operations easier to use and improve 
 * extendability.
 */
#[\AllowDynamicProperties]
class Model {
    protected static $_db;
    public $id;
    protected $_modelName;
    protected static $_softDelete = false;
    protected static $_table;
    protected $_validates = true;
    protected $_validationErrors = [];

    /**
     * Default constructor.
     */
    public function __construct() {
        $this->_modelName = str_replace(' ', '', ucwords(str_replace('_',' ', static::$_table)));
        $this->onConstruct();
    }

    /**
     * Generates error messages that occur during form validation.
     *
     * @param string $field The form field associated with failed form 
     * validation
     * @param string $message A message that describes to the user the cause 
     * for failed form validation.
     * @return void
     */
    public function addErrorMessage($field,$message) {
        $this->_validates = false;
        if(Arr::exists($this->_validationErrors, $field,)) {
            $this->_validationErrors[$field] .= " " . $message;
        } else {
            $this->_validationErrors[$field] = $message;
        }
    }

    /**
     * Called before delete.
     *
     * @return void
     */
    public function afterDelete() {}

    /**
     * Called before save.
     *
     * @return void
     */
    public function afterSave() {}

    /**
     * Update the object with an associative array.
     * 
     * @param array $params Take values from post array and assign values.
     * @param array $list A list of values to blacklist or whitelist.
     * @param boolean $blackList When set to true the values in the $list array is 
     * blacklisted.  Otherwise they are whitelisted.
     * @return bool Report for whether or not the operation was successful.
     */
    public function assign($params, $list = [], $blackList = true) {
        foreach($params as $key => $val) {
            // check if there is permission to update the object
            $whiteListed = true;
            if(sizeof($list) > 0){
              if($blackList){
                    $whiteListed = !Arr::contains($list, $key);
              } else {
                    $whiteListed = Arr::contains($list, $key);
              }
            }
            if(property_exists($this,$key) && $whiteListed){
                $this->$key = $val;
            }
        }
        return $this;
    }

    /**
     * This runs before delete, needs to return a boolean
     *
     * @return boolean Boolean value depending on results of operation.
     */
    public function beforeDelete() { return true; }
    
    /**
     * Called after save.
     *
     * @return void
     */
    public function beforeSave() {}

    /**
     * Grab object and if we just need data for smaller result set.
     * 
     * @return object The data associated with an object.
     */
    public function data() {
        // No evidence this function is being called.
        $data = new \stdClass();
        $columns = static::getColumns();
        // Determine column key name based on DB driver
        $columnKey = (isset($columns[0]->Field)) ? 'Field' : 'name';

        // foreach ($columns as $column) {
        //     $columnName = $column->{$columnKey};
        //     $data->{$columnName} = $this->{$columnName};
        // }
        (new ArraySet($columns))->each(function($column) use (&$columnName, &$data, &$columnKey) {
            $columnName = $column->{$columnKey};
            $data->{$columnName} = $this->{$columnName};
        });
        return $data;
    }

    /**
     * Wrapper for database delete function.  If not softDelete we set it.
     * If row is set to softDelete we call the database delete function.
     * 
     * @param string $id The primary key for the record we want to remove from a 
     * database table.  The default value is an empty string.
     * @return bool $deleted True if delete operation is successful.  Otherwise, we 
     * return false.
     */
    public function delete() {
        if($this->id == '' || !isset($this->id)) return false;
        $this->beforeDelete();
        if(static::$_softDelete) {
            $deleted = $this->update(['deleted' => 1]);
        } else {
            $deleted = static::getDb()->delete(static::$_table, $this->id);
        }
        $this->afterDelete();
        return $deleted;
    }

    /**
     * Gets columns from table.
     * 
     * @return array An array of objects where each one represents a column 
     * from a database table.
     */
    public static function getColumns() {
        return static::getDb()->getColumns(static::$_table);
    }

    /**
     * Gets an associative array of field values for insert or updating.
     *
     * @return array Associative array of fields from database and values 
     * from model object.
     */
    public function getColumnsForSave() {
        $columns = static::getColumns();
        Logger::log("Columns from DB: " . json_encode($columns), 'debug');

        $fields = [];

        // Determine correct column name key
        $columnKey = isset($columns[0]->Field) ? 'Field' : (isset($columns[0]->name) ? 'name' : null);

        if ($columnKey === null) {
            Logger::log("ERROR: Column key not found!", 'error');
            return [];
        }

        (new ArraySet($columns))->each(function($column) use (&$columnKey, &$key, &$fields) {
            $key = $column->{$columnKey};
            if (isset($this->{$key})) $fields[$key] = $this->{$key};
            
        });
        Logger::log("Fields for save: " . json_encode($fields), 'debug');
        return $fields;
    }

    /**
     * Returns an instance of the DB class.
     *
     * @return DB $_db The instance of the DB class.
     */
    public static function getDb(){
        if(!self::$_db) {
            self::$_db = DB::getInstance();
        }
        return self::$_db;
    }

    /**
     * Displays error messages when form validation fails.
     *
     * @return array An array that contains a list of items that failed form 
     * validation.
     */
    public function getErrorMessages() {
        return $this->_validationErrors;
    }

    /**
     * Used to set default fetchStyle param.
     *
     * @param array $params Query params.
     * @return array $params Updated params.
     */
    protected static function _fetchStyleParams($params){
        if(!isset($params['fetchStyle'])) {
            $params['fetchStyle'] = \PDO::FETCH_CLASS;
        }
        return $params;
    }

    /**
     * Wrapper for the find function that is found in the DB class.
     *
     * @param array $params The values for the query.  They are the fields of 
     * the table in our database.  The default value is an empty array.
     * @return bool|array An array of objects returned from an SQL query.
     */
    public static function find($params = []) {
        $params = static::_fetchStyleParams($params);
        $params = static::_softDeleteParams($params);
        $resultsQuery = static::getDb()->find(static::$_table, $params, static::class);
        if(!$resultsQuery) return [];
        return $resultsQuery;
    }

    /**
     * Get result from database by primary key ID.
     *
     * @param int $id The ID of the row we want to retrieve from the database.
     * @return bool|object The row from a database.
     */
    public static function findById($id) {
        return static::findFirst(['conditions'=>"id = ?", 'bind' => [$id]]);
    }

    /**
     * Retrieves list of all records within a table related to a user.
     *
     * @param int $user_id The user ID.
     * @param array $params Used to build conditions for database query.  The 
     * default value is an empty array.
     * @return array The list of records that is returned from the database.
     */
    public static function findAllByUserId($user_id, $params = []) {
        $conditions = [
            'conditions' => 'user_id = ?',
            'bind' => [(int)$user_id]
        ];

        // In case you want to add more conditions
        $conditions = Arr::merge($conditions, $params);
        return self::find($conditions);
    }

    /**
     * Wrapper for the findFirst function that is found in the DB class.
     *
     * @param array $params The values for the query.  They are the fields of 
     * the table in our database.  The default value is an empty array.
     * @return bool|object An array of object returned from an SQL query.
     */
    public static function findFirst($params = []) {
        $params = static::_fetchStyleParams($params);
        $params = static::_softDeleteParams($params);
        $resultQuery = static::getDb()->findFirst(static::$_table, $params,static::class);
        return $resultQuery;
    }

    /**
     * Returns number of records in a table.  A wrapper function for 
     * findTotal function in DB class.
     *
     * @param array $params The values for the query.  They are the fields of 
     * the table in our database.  The default value is an empty array.
     * @return int The number of records in a table.
     */
    public static function findTotal($params=[]) {
        $params = static::_fetchStyleParams($params);
        $params = static::_softDeleteParams($params);
        unset($params['limit']);
        unset($params['offset']);
        return static::getDb()->findTotal(static::$_table, $params);
    }

    /** 
     * Wrapper for database insert function.
     * 
     * @param array $fields The field names and the respective values we will 
     * use to populate a database record.  The default value is an empty array.
     * @return bool Report for whether or not the operation was successful.
     */
    public function insert($fields) {
        if(empty($fields)) return false;
        if(Arr::exists($fields, 'id')) unset($fields['id']);
        return static::getDb()->insert(static::$_table, $fields);
    }

    /**
     * Checks if an object is a new insertion or an existing record.
     *
     * @return bool Returns true if the record exists.  Otherwise, we 
     * return false.
     */
    public function isNew() {
        return (property_exists($this, 'id') && !empty($this->id)) ? false : true;
    }
    
    /**
     * Runs when the object is constructed.
     *
     * @return void
     */
    public function onConstruct(): void {}
    
    /**
     * Populates object with data.
     *
     * @param array|object $result Results from a database query.
     * @return void
     */
    protected function populateObjData($result) {
        // No evidence this function is being called.
        foreach($result as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * Wrapper for database query function.
     * 
     * @param string $sql The database query we will submit to the database.
     * @param array $bind The values we want to bind in our database query.  
     * The default value is an empty array.
     * @return DB The results of the database query.
     */
    public function query($sql, $bind=[]) {
        return static::getDb()->query($sql, $bind);
    }

    /**
     * Runs a validator object and sets validates boolean and adds error 
     * message if validator fails.
     *
     * @param object $validator The validator object.
     * @return void
     */
    public function runValidation($validator) {
        // $validator->field is the field we ar validating.
        $key = $validator->field;
        if(!$validator->success){
            $this->addErrorMessage($key,$validator->message);
        }
    }

    /**
     * Wrapper for update and insert functions.  A failed form validation will
     * cause this function to return false.
     * 
     * @return bool True if the update operation is successful.  Otherwise, 
     * we return false.
     */
    public function save() {
        $this->validator();
        $save = false;
        if($this->_validates){
            $this->beforeSave();
            $fields = $this->getColumnsForSave();
            
            // Ensure ID is never passed to SQLite INSERT statements
            if (isset($fields['id'])) {
                unset($fields['id']);
            }

            if($this->isNew()) {
                $save = $this->insert($fields);
                if($save){
                    $this->id = static::getDb()->lastID();
                }
            } else {
                $save = $this->update($fields);
            }

            if($save){
                $this->afterSave();
            }
        }
        return $save;
    }

    /**
     * Adds to the conditions to avoid getting soft deleted rows returned
     *
     * @param array $params Defined parameters to search by.
     * @return array $params parameters with appended conditions for soft 
     * delete.
     */
    protected static function _softDeleteParams($params){
        if(isset($params['includeDeleted']) && $params['includeDeleted'] == true) return $params;
        if(static::$_softDelete){
            $dbDriver = static::getDb()->getPDO()->getAttribute(\PDO::ATTR_DRIVER_NAME);
            $notEqualOperator = ($dbDriver === 'sqlite') ? "<>" : "!=";

            if(Arr::exists($params, 'conditions')){
                if(Arr::isArray($params['conditions'])){
                    $params['conditions'][] = "deleted {$notEqualOperator} 1";
                } else {
                    $params['conditions'] .= " AND deleted {$notEqualOperator} 1";
                }
            } else {
                $params['conditions'] = "deleted {$notEqualOperator} 1";
            }
        }
        return $params;
    }

    /**
     * Sets values for timestamp fields.
     *
     * @return void
     */
    public function timeStamps() {
        $now = DateTime::timeStamps();
        $this->updated_at = $now;
        if($this->isNew()) {
            $this->created_at = $now;
        }
    }

    /**
     * Wrapper for the update function found in the DB class.
     *
     * @param array $fields The value of the fields we want to set for the 
     * database record.  The default value is an empty array.
     * @return bool True if the update operation is successful.  Otherwise, 
     * we return false.
     */
    public function update($fields) {
        if(empty($fields) || $this->id == '') return false;
        return static::getDb()->update(static::$_table, $this->id, $fields);
    }

    /**
     * Getter function for the $_validates boolean instance variable.
     *
     * @return bool $_validates is true if validation is successful and 
     * false if there is a failure.
     */
    public function validationPassed() {
        return $this->_validates;
    }

    /**
     * Function that is called on save.  If validation fails the save function 
     * will not proceed.  This function is just a signature and must be 
     * implemented by models that run form validation because since it is 
     * called from within this class.
     * @return void
     */
    public function validator() {}
}