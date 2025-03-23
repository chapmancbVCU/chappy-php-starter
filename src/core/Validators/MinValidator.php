<?php
namespace Core\Validators;
use Core\Validators\CustomValidator;
/**
 * Child class that performs validation for the minimum length of a value for 
 * a field.
 */
class MinValidator extends CustomValidator {
    /**
     * Implements the abstract function of the same name from the parent 
     * class.  Enforces minimum length requirements for a form field.
     *
     * @return bool True if value we are testing is less than the min value 
     * set by the rule.  Otherwise, we return false.
     */
    public function runValidation(): bool {
        $value = $this->_model->{$this->field};
        $pass = (strlen($value) >= $this->rule);
        return $pass;
    }
}