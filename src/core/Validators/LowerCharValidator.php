<?php
namespace Core\Validators;
use Core\Validators\CustomValidator;
/**
 * Child class class that supports ability to check if field contains a
 * lower case character in the field.
 */
class LowerCharValidator extends CustomValidator {
    /**
     * Implements the abstract function of the same name from the parent 
     * class.  Enforces requirement where a field must contain at least one 
     * lower case character.
     *
     * @return bool True if field contains at least one lower case character.
     */
    public function runValidation(): bool {
        if(preg_match('/[a-z]/', $this->_model->{$this->field}) == 1) {
            return true;
        }
        return false;
    }
}