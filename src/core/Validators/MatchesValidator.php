<?php
namespace Core\Validators;
use Core\Validators\CustomValidator;
/**
 * Child class that performs validation for two fields that are required to 
 * have the same value.
 */
class MatchesValidator extends CustomValidator {
    /**
     * Implements the abstract function of the same name from the parent 
     * class.  Enforces requirements when two fields must match.
     *
     * @return bool True if values match.  Otherwise, we return false.
     */
    public function runValidation(): bool {
        $value = $this->_model->{$this->field};
        return $value == $this->rule;
    }
}