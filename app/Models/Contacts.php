<?php
namespace App\Models;
use Core\{Helper, Model};
use Core\Lib\Utilities\Arr;
use Core\Validators\{MaxValidator, RequiredValidator};

/**
 * Extends the Model class.  Supports functions for handling Contacts such as 
 * displaying information, form validation, and DB operations.
 */
class Contacts extends Model {
    public $address;
    public $address2;
    public $cell_phone;
    public $city;
    public $country;
    public $created_at;
    public $deleted = 0;
    public const blackList = ['id', 'deleted'];
    public $email;
    public $fname;
    public $home_phone;
    public $id;
    public $lname;
    protected static $_softDelete = true;
    public $state;
    protected static $_table = 'contacts';
    public $updated_at;
    public $user_id;
    public $work_phone;
    public $zip;

    /**
     * Called before save.
     *
     * @return void
     */
    public function beforeSave(): void {
        $this->timeStamps();
    }
    
    /**
     * Formats address to conform to form factor of an address label.
     *
     * @return string $html The formatted address.
     */
    public function displayAddress(): string {
        $address = '';
        if(!empty($this->address)) {
            $address .= $this->address . '<br>';
        }
        if(!empty($this->address2)) {
            $address .= $this->address2 . '<br>';
        }
        if(!empty($this->city)) {
            $address .= $this->city . ', ';
        }
        $address .= $this->state . ' ' .  $this->zip . '<br>';
        if(!empty($this->country)) {
            $address .= $this->country . '<br>';
        }

        return $address;
    }

    /**
     * Displays contact information in an address label format.
     *
     * @return string $html The contact information in an address label 
     * format.
     */
    public function displayAddressLabel(): string {
        $html = $this->displayName() . '<br>';
        $html .= $this->displayAddress();
        return $html;
    }

    /**
     * Displays name in following format: ${firstName}, ${lastName}.
     *
     * @return string Returns first name and last name.
     */
    public function displayName(): string {
        return $this->fname . ' ' . $this->lname;
    }

    /**
     * Retrieves information for a contact that is associate with a 
     * particular user.
     *
     * @param int $contact_id The ID of the contact whose details we want.
     * @param int $user_id The ID user associated with this contact.
     * @param array $params Used to set additional conditions.  The default 
     * value is an empty array.
     * @return bool|object The associative array with contact information we want to 
     * view.
     */
    public static function findByIdAndUserId($contact_id, $user_id, $params = []) {
        $conditions = [
            'conditions' => 'id = ? AND user_id = ?',
            'bind' => [$contact_id, $user_id]
        ];
        $conditions = Arr::merge($conditions, $params);
        return self::findFirst($conditions);
    }

    /**
     * Performs form validation checks for add and edit contact form template.
     *
     * @return void
     */
    public function validator(): void {
        // Validate first name
        $this->runValidation(new MaxValidator($this, ['field' => 'fname', 'message' => 'First Name must be less than 156 characters.', 'rule' => 155]));

        // Validate last name
        $this->runValidation(new MaxValidator($this, ['field' => 'lname', 'message' => 'Last Name must be less than 156 characters.', 'rule' => 155]));

        // Validate address
        $this->runValidation(new MaxValidator($this, ['field' => 'address', 'message' => 'Address must be less than 256 characters.', 'rule' => 255]));

        // Validate address 2
        $this->runValidation(new MaxValidator($this, ['field' => 'address2', 'message' => 'Address 2 Name must be less than 256 characters.', 'rule' => 255]));

        // Validate city
        $this->runValidation(new MaxValidator($this, ['field' => 'city', 'message' => 'City must be less than 256 characters.', 'rule' => 255]));

        // Validate state
        $this->runValidation(new MaxValidator($this, ['field' => 'state', 'message' => 'City requires 2 character length abbreviation.', 'rule' => 2]));

        // Validate zip
        $this->runValidation(new MaxValidator($this, ['field' => 'zip', 'message' => 'Zip code must be less than 10 characters', 'rule' => 9]));

        // Validate Email
        $this->runValidation(new MaxValidator($this, ['field' => 'email', 'message' => 'Zip code must be less than 176 characters', 'rule' => 175]));

        // Group required validators
        $requiredFields = ['fname' => 'First Name', 'lname' => 'Last Name', 
            'address' => 'Address', 'city' => 'City', 'state' => 'State', 
            'zip' => 'Zip', 'email' => 'Email'];
        foreach($requiredFields as $field => $display) {
            $this->runValidation(new RequiredValidator($this,['field'=>$field,'message'=>$display." is required."]));
        }
    }
}