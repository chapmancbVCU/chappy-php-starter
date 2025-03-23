<?php
namespace Core\Validators;
use Core\Validators\CustomValidator;
/**
 * Child class class that supports ability to check if field contains an 
 * special character that is not a space in the field.
 */
class SpecialCharValidator extends CustomValidator {
    /**
     * Implements the abstract function of the same name from the parent 
     * class.  Enforces requirement where a field must contain at least one 
     * special character that is not a space.
     *
     * @return bool True if field contains at least one special character 
     * that is not a space.
     */
    public function runValidation(): bool {
        $value = $this->_model->{$this->field};
        if((preg_match('/[^a-zA-Z0-9]/', $value) == 1) && 
            (preg_match('/\s/', $value) == 0)) {
            return true;
        }
        return false;
    }//
}