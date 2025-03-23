<?php
namespace Core\Validators;
use \Exception;
use Core\Lib\Utilities\Arr;
use Core\Lib\Utilities\ArraySet;
/**
 * Abstract parent class for our child validation child classes.  Each child 
 * class must implement the runValidation() function.
 * @abstract
 */
abstract class CustomValidator {
    public $additionalFieldData = [];
    public $field;
    public $includeDeleted = false;
    protected $_model;
    public $message = '';
    public $rule;
    public $success = true;
    
    /**
     * Constructor for Custom Validator.  It performs checks on the model and 
     * params such as fields, rules, and messages.  Finally the validation is 
     * performed against input from a form.  An exception is thrown if any 
     * conditions are not satisfied.  When an exception is thrown a message 
     * is displayed describing the issue.
     *
     * @param object $model The name of the model we want to perform 
     * validation when submitting a form.
     * @param array $params A list of values obtained from an input when a 
     * form is submitted during a post action.
     */
    public function __construct(object $model, array $params) {
        $this->_model = $model;
        $paramsArr = ArraySet::make($params);

        // Validate field existence
        if (!$paramsArr->has('field')->result()) {
            throw new Exception("You must add a 'field' to the params array.");
        }

        $fieldData = $paramsArr->get('field')->result();
        if (Arr::isArray($fieldData)) {
            $this->field = array_shift($fieldData);
            $this->additionalFieldData = $fieldData;
        } else {
            $this->field = $fieldData;
        }

        if (!property_exists($model, $this->field)) {
            throw new Exception("The field '{$this->field}' must exist in the model.");
        }

        // Validate message existence
        if (!$paramsArr->has('message')->result()) {
            throw new Exception("You must add a 'message' to the params array.");
        }
        $this->message = $paramsArr->get('message')->result();

        // Optional rule parameter
        if ($paramsArr->has('rule')->result()) {
            $this->rule = $paramsArr->get('rule')->result();
        }

        // Optional includeDeleted flag
        $this->includeDeleted = (bool) $paramsArr->get('includeDeleted', false)->result();

        try {
            $this->success = $this->runValidation();
        } catch (Exception $e) {
            echo "Validation Exception on " . static::class . ": " . $e->getMessage() . "<br />";
        }
    }

    /**
     * Signature for the runValidation function that must be implemented by 
     * each child class.
     *
     * @return void
     * @abstract
     */
    abstract public function runValidation();
}