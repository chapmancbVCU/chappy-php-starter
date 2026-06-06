<?php
namespace App\Models;
use Core\Model;
use Core\Services\AuthService;
use Core\Lib\Utilities\Arr;
use Core\Traits\HasTimestamps;
use Core\Traits\PasswordPolicy;

/**
 * Extends the Model class.  Supports functions for the Users model.
 */
class Users extends Model {
    use PasswordPolicy;
    use HasTimestamps;
    public $acl;
    public const blackListedFormKeys = ['id','deleted'];
    private $changePassword = false;
    public $confirm;
    public static $currentLoggedInUser = null;
    public $deleted = 0;                // Set default value for db field.
    public $description;
    public $email;
    public $fname;
    public $id;
    public $inactive = 0;
    public $lname;
    public $login_attempts = 0;
    public $reset_password = 0;
    public $password;
    protected static $_softDelete = true;
    protected static $_table = 'users';
    public $username;

    /**
     * Implements beforeSave function described in Model parent class.  
     * Ensures password is not in plain text but a hashed one.  The 
     * reset_password flag is also set to 0.
     *
     * @return void
     */
    public function beforeSave(): void {
        $this->timeStamps();
        if($this->isNew()) {
            $this->password = AuthService::hashPassword($this->password);
        }
        if($this->changePassword) {
            $this->password = AuthService::hashPassword($this->password);
            $this->reset_password = 0;
        }
        
        // ✅ Ensure ACL is always stored as `[""]` when empty
        if (Arr::isEmpty(json_decode($this->acl, true))) {
            $this->acl = json_encode([""]);
        }
        
    }

    /**
     * Retrieves a list of all users except current logged in user.
     *
     * @param int $current_user_id The id of the currently logged in user.
     * @param array $params Used to build conditions for database query.  The 
     * default value is an empty array.
     * @return array The list of users that is returned from the database.
     */
    public static function findAllUsersExceptCurrent($current_user_id, $params = []) {
        $conditions = [
            'conditions' => 'id != ?',
            'bind' => [(int)$current_user_id]
        ];
        // In case you want to add more conditions
        $conditions = Arr::merge($conditions, $params);
        return self::find($conditions);
    }

    /**
     * Finds user by username in the Users table.
     *
     * @param string $username The username we want to find in the Users table. 
     * @return bool|object An object containing information about a user from 
     * the Users table.
     */
    public static function findByUserName(string $username) {
        return self::findFirst(['conditions'=> "username = ?", 'bind'=>[$username]]);
    }

    /**
     * Checks if the user has a specific ACL assigned.
     *
     * @param string $acl The ACL to check.
     * @return bool True if the user has the ACL, otherwise false.
     */
    public function hasAcl($acl) {
        $userAcls = json_decode($this->acl, true);
    
        if (!is_array($userAcls)) {
            return false; // Ensures it always returns a boolean
        }
        return in_array($acl, $userAcls, true);
    }

    /**
     * Assists in setting value of inactive field based on POST.
     *
     * @return $inactive The value for inactive based on value received from 
     * POST.
     */
    public function isInactiveChecked() {
        return $this->inactive === 1;
    }

    /**
     * Assists in setting value of reset_password field based on 
     * POST.
     *
     * @return $reset_password The value for reset_password based on value 
     * received from POST.
     */
    public function isResetPWChecked() {
        return $this->reset_password === 1;
    }

    /**
     * Setter function for $changePassword.
     *
     * @param bool $value The value we will assign to $changePassword.
     * @return void
     */
    public function setChangePassword(bool $value): void {
        $this->changePassword = $value;
    }

    /**
     * Performs validation on the user registration form.
     *
     * @return void
     */
    public function validator(): void {
        $this->runValidation('fname', 'FirstName', ['required', 'max:150']);
        $this->runValidation('lname', 'Last Name', ['required', 'max:150']);
        $this->runValidation('email', 'Email', ['required', 'max:150']);
        $this->runValidation('password', 'Password', ['required']);
        $this->runValidation('username', 'Username', ['required']);
        
        if($this->isNew() || $this->changePassword) {
            $this->runValidation('username', 'Username', ['min:6', 'max:150', 'unique:'.self::class]);
            if($this->isMinLength()) {
                $this->runValidation('password', 'Password', ["min:{$this->minLength()}"]);
            }
            if($this->isMaxLength()) {
                $this->runValidation('password', 'Password', ["max:{$this->maxLength()}"]);
            }
            if($this->lowerChar()) {
                $this->runValidation('password', 'Password', ['lower']);
            }
            if($this->upperChar()) {
                $this->runValidation('password', 'Password', ['upper']);
            }
            if($this->numericChar()) {
                $this->runValidation('password', 'Password', ['number']);
            }
            if($this->specialChar()) {
                $this->runValidation('password', 'Password', ['special']);
            }
            $this->runValidation('confirm', 'Confirm Password', ["match:{$this->password}"]);
        }
    }
}