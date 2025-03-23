<?php
namespace App\Models;
use Core\{Cookie, Helper, Model, Session};
use Core\Lib\Logging\Logger;
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
use App\Models\UserSessions;
use Core\Lib\Utilities\Env;
use Core\Lib\Utilities\Arr;
use Core\Lib\Utilities\Str;
/**
 * Extends the Model class.  Supports functions for the Users model.
 */
class Users extends Model {
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
     * Returns an array containing access control list information.  When the 
     * $acl instance variable is empty an empty array is returned.
     *
     * @return array The array containing access control list information.
     */
    public function acls() {
        if(empty($this->acl)) return [];
        return json_decode($this->acl, true);

    }

    /**
     * Ensures that we are always dealing with an array of ACLs
     *
     * @param mixed $acls An array or any type that we want to add to an array.
     * @return array An array of acls.
     */
    public static function aclToArray(mixed $acls): array {
        if (!is_array($acls)) {
            $acls = [];
        }
        return Arr::map($acls, 'strval');
    }

    /**
     * Add ACL to user's acl field as an element of an array.
     *
     * @param int $user_id The id of the user whose acl field we want to 
     * modify.
     * @param string $acl The name of the new ACL.
     * @return bool True or false depending on success of operation.
     */
    public static function addAcl($user_id,$acl) {
        $user = self::findById($user_id);
        if(!$user) return false;
        $acls = $user->acls();
        if(!in_array($acl, $acls)){
            $acls[] = $acl;
            $user->acl = json_encode($acls);
            $user->save();
        }
        return true;
    }

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
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
        if($this->changePassword) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            $this->reset_password = 0;
        }
        
        // ✅ Ensure ACL is always stored as `[""]` when empty
        if (Arr::isEmpty(json_decode($this->acl, true))) {
            $this->acl = json_encode([""]);
        }
        
    }

    /**
     * Checks if a user is logged in.
     *
     * @return object|null An object containing information about current 
     * logged in user from users table.
     */
    public static function currentUser() {
        if(!isset(self::$currentLoggedInUser) && Session::exists(Env::get('CURRENT_USER_SESSION_NAME'))) {
            self::$currentLoggedInUser = self::findById((int)Session::get(Env::get('CURRENT_USER_SESSION_NAME')));
        }
        return self::$currentLoggedInUser;
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
     * Hashes password.
     *
     * @param string $password Original password submitted on a registration 
     * or update password form.
     * @return void
     */
    public function hashPassword($password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
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
     * Creates a session when the user logs in.  A new record is added to the 
     * user_sessions table and a cookie is created if remember me is 
     * selected.
     *
     * @param bool $rememberMe Value obtained from remember me checkbox 
     * found in login form.  Default value is false.
     * @return void
     */
    public function login(bool $rememberMe = false): void {
        $user = self::findFirst([
            'conditions' => 'username = ?',
            'bind' => [$this->username]
        ]);

        if (!$user) {
            Logger::log("Failed login attempt: Username '{$this->username}' not found.", 'warning');
        }

        if ($user->inactive == 1) {
            Logger::log("Failed login attempt: Inactive account for user ID {$user->id} ({$user->username}).", 'warning');
        }

        Session::set(Env::get('CURRENT_USER_SESSION_NAME'), $this->id);
        Logger::log("User {$user->id} ({$user->username}) logged in successfully.", 'info');
        
        if($rememberMe) {
            $hash = Str::md5(uniqid() . rand(0, 100));
            $user_agent = Session::uagent_no_version();
            Cookie::set(Env::get('REMEMBER_ME_COOKIE_NAME'), $hash, Env::get('REMEMBER_ME_COOKIE_EXPIRY', 2592000));
            $fields = ['session'=>$hash, 'user_agent'=>$user_agent, 'user_id'=>$this->id];
            self::$_db->query("DELETE FROM user_sessions WHERE user_id = ? AND user_agent = ?", [$this->id, $user_agent]);
            $us = new UserSessions();
            $us->assign($fields);
            $us->save();
            Logger::log("Remember Me token set for user {$user->id} ({$user->username}).", 'info');
        }
    }

    /**
     * Tests for login attempts and sets session messages when there is a 
     * failed attempt or when account is locked.
     *
     * @param User $user The user whose login attempts we are tracking.
     * @param Login $loginModel The model that will be responsible for 
     * displaying messages.
     * @return Login $loginModel The Login model after login in attempt test 
     * and session messages are assigned.
     */
    public static function loginAttempts($user, $loginModel) {
        if($user->login_attempts >= Env::get('MAX_LOGIN_ATTEMPTS', 5)) {
            $user->inactive = 1; 
        }
        if($user->login_attempts < Env::get('MAX_LOGIN_ATTEMPTS', 5)) {
            $loginModel->addErrorMessage('username', 'There is an error with your username or password.');
        } else {
            Session::addMessage('danger', 'Your account has been locked due to too many failed login attempts.');
        }
        $user->login_attempts = $user->login_attempts + 1;
        $user->save();
        return $loginModel;
    }

    /**
     * Logs in user from cookie.
     *
     * @return Users The user associated with previous session.
     */
    public static function loginUserFromCookie() {
        $userSession = UserSessions::getFromCookie();
        if($userSession && $userSession->user_id != '') {
            $user = self::findById((int)$userSession->user_id);
            if($user) {
                $user->login();
            }
            return $user;
        }
        return;
    }

    /**
     * Perform logout operation on current logged in user.  The record for the 
     * current logged in user is removed from the user_session table and the 
     * corresponding cookie is deleted.
     *
     * @return bool Returns true if operation is successful.
     */
    public function logout(): bool {
        $userSession = UserSessions::getFromCookie();
        if($userSession) {
            $userSession->delete();
        }
        Session::delete(Env::get('CURRENT_USER_SESSION_NAME'));
        if(Cookie::exists(Env::get('REMEMBER_ME_COOKIE_NAME'))) {
            Cookie::delete(Env::get('REMEMBER_ME_COOKIE_NAME'));
        }
        self::$currentLoggedInUser = null;
        Logger::log("User {$this->id} ({$this->username}) logged out.", 'info');
        return true;
    }

    /**
     * Manages the adding and removing of ACLs.
     *
     * @param array $acls ACLs stored in acl table.
     * @param Users $user The user we want to modify 
     * @param array $newAcls The new ACLs for the user.
     * @param array $userAcls The user's existing ACLs.
     * @return void
     */
    public static function manageAcls(array $acls, Users $user, array $newAcls, array $userAcls): void {
        foreach ($acls as $aclName) {
            $aclKeyStr = (string)$aclName;
            if (in_array($aclKeyStr, $newAcls, true) && !in_array($aclKeyStr, $userAcls, true)) {
                self::addAcl($user->id, $aclKeyStr);
            } elseif (!in_array($aclKeyStr, $newAcls, true) && in_array($aclKeyStr, $userAcls, true)) {
                self::removeAcl($user->id, $aclKeyStr);
            }
        }
    }


    /**
     * Removes ACL from user's acl field array.
     *
     * @param int $user_id The id of the user whose acl field we want to modify.
     * @param string $acl The name of the ACL to be removed.
     * @return void
     */
    public static function removeAcl($user_id, $acl) {
        $user = self::findById($user_id);
        if(!$user) return false;
        $acls = $user->acls();
        if(in_array($acl,$acls)){
            $key = Arr::search($acls, $acl);
            unset($acls[$key]);
            $user->acl = json_encode($acls);
            $user->save();
        }
        return true;
    }

    /**
     * Sets ACL at registration.  If users table is empty the default 
     * value is Admin.  Otherwise, we set the value to "".
     *
     * @return string The value of the ACL we are setting upon 
     * registration of a user.
     */
    public static function setAclAtRegistration() {
        if(Users::findTotal() == 0) {
            return '["Admin"]';
        }
        return '[""]';
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