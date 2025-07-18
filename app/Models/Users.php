<?php
namespace App\Models;
use Core\Model;
use Core\Services\AuthService;
use Core\Validators\{
    EmailValidator,
    LowerCharValidator,
    MaxValidator,
    MatchesValidator,
    MinValidator,
    NumberCharValidator,
    RequiredValidator,
    SpecialCharValidator,
    UniqueValidator,
    UpperCharValidator
};
use Core\Lib\Utilities\Env;
use Core\Lib\Utilities\Arr;
use Core\Lib\Notifications\Notifiable; 
/**
 * Extends the Model class.  Supports functions for the Users model.
 */
class Users extends Model {
    use Notifiable;
    public $acl;
    public const blackListedFormKeys = ['id','deleted'];
    private $changePassword = false;
    public $confirm;
    public $created_at;
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
    public $updated_at;
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
        // Validate first name
        $this->runValidation(new RequiredValidator($this, ['field' => 'fname', 'message' => 'First Name is required']));
        $this->runValidation(new MaxValidator($this, ['field' => 'fname', 'rule' => 150, 'message' => 'First name must be less than 150 characters.']));

        // Validate last name
        $this->runValidation(new RequiredValidator($this, ['field' => 'lname', 'message' => 'Last Name is required.']));
        $this->runValidation(new MaxValidator($this, ['field' => 'lname', 'rule' => 150, 'message' => 'Last name must be less than 150 characters.']));

        // Validate E-mail
        $this->runValidation(new RequiredValidator($this, ['field' => 'email', 'message' => 'Email is required.']));
        $this->runValidation(new MaxValidator($this, ['field' => 'email', 'rule' => 150, 'message' => 'Email must be less than 150 characters.']));
        $this->runValidation(new EmailValidator($this, ['field' => 'email', 'message' => 'You must provide a valid email address.']));

        // Validate username
        $this->runValidation(new MinValidator($this, ['field' => 'username', 'rule' => 6, 'message' => 'Username must be at least 6 characters.']));
        $this->runValidation(new MaxValidator($this, ['field' => 'username', 'rule' => 150, 'message' => 'Username must be less than 150 characters.']));
        $this->runValidation(new UniqueValidator($this, ['field' => 'username', 'message' => 'That username already exists.  Please chose a new one.']));

        // Validate password
        $this->runValidation(new RequiredValidator($this, ['field' => 'password', 'message' => 'Password is required.'])); 
        
        if($this->isNew() || $this->changePassword) {
            if(Env::get('SET_PW_MIN_LENGTH', false)) {
                $this->runValidation(new MinValidator($this, ['field' => 'password', 'rule' => Env::get('PW_MIN_LENGTH', 12), 'message' => 'Password must be at least '. Env::get('PW_MIN_LENGTH', 12).' characters.']));
            }
            if(Env::get('SET_PW_MAX_LENGTH', false)) {
                $this->runValidation(new MaxValidator($this, ['field' => 'password', 'rule' => Env::get('PW_MAX_LENGTH', 30), 'message' => 'Password must be less than ' . Env::get('PW_MAX_LENGTH', 30). ' characters.']));
            }
            if(Env::get('PW_LOWER_CHAR', false)) {
                $this->runValidation(new LowerCharValidator($this, ['field' => 'password', 'message' => 'Must contain at least 1 lower case character.']));
            }
            if(Env::get('PW_UPPER_CHAR', false)) {
                $this->runValidation(new UpperCharValidator($this, ['field' => 'password', 'message' => 'Must contain at least 1 upper case character.']));
            }
            if(Env::get('PW_NUM_CHAR', false)) {
                $this->runValidation(new NumberCharValidator($this, ['field' => 'password', 'message' => 'Must contain at least 1 numeric character.']));
            }
            if(Env::get('PW_SPECIAL_CHAR', false)) {
                $this->runValidation(new SpecialCharValidator($this, ['field' => 'password', 'message' => 'Must contain at least 1 special character.'])); 
            }
            $this->runValidation(new MatchesValidator($this, ['field' => 'password', 'rule' => $this->confirm, 'message' => 'Passwords must match.']));
        }
    }
}