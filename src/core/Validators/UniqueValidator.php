<?php
namespace Core\Validators;
use Core\Validators\CustomValidator;
/**
 * Child class that performs validation for fields that require a unique entry 
 * in a database.
 */
class UniqueValidator extends CustomValidator {
    /**
     * Implements the abstract function of the same name from the parent 
     * class.  Enforces requirement for a field.
     *
     * @return bool Returns true if value is not associated with a record's 
     * field that we are targeting in a database.  Otherwise, we return false.
     */
    public function runValidation() {
        $value = $this->_model->{$this->field};

        if($value == '' || !isset($value)){
            // this allows unique validator to be used with empty strings for fields that are not required.
            return true;
        }

        $conditions = ["{$this->field} = ?"];
        $bind = [$value];

        //check updating record
        if(!empty($this->_model->id)){
            $conditions[] = "id != ?";
            $bind[] = $this->_model->id;
        }

        //this allows you to check multiple fields for Unique
        foreach($this->additionalFieldData as $adds){
            $conditions[] = "{$adds} = ?";
            $bind[] = $this->_model->{$adds};
        }

        $queryParams = ['conditions'=>$conditions,'bind'=>$bind];
        $other = $this->_model::findFirst($queryParams);
        return(!$other);
    }
}