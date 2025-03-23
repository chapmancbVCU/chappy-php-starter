<?php
namespace Core\Validators;
use Core\Validators\CustomValidator;
/**
 * Child class class that supports ability to check if field contains a 
 * numeric character in the field.
 */
class NumberCharValidator extends CustomValidator {
    /**
     * Implements the abstract function of the same name from the parent 
     * class.  Enforces requirement where a field must contain at least one 
     * numeric character.
     *
     * @return bool True if field contains at least one upper case character.
     */
    public function runValidation(): bool {
        if(preg_match('/[0-9]/', $this->_model->{$this->field}) == 1) {
            return true;
        }
        return false;
    }
}