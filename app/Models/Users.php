<?php
namespace App\Models;
use Core\Model;
use Core\Lib\Utilities\Arr;
use Core\Lib\Contracts\Principal as ContractsPrincipal;
use Core\Traits\{
    HasTimestamps,
    IsPrincipal,
    IsUser,
    PasswordPolicy
};

/**
 * Extends the Model class.  Supports functions for the Users model.
 */
class Users extends Model implements ContractsPrincipal {
    use HasTimestamps;
    use IsPrincipal;
    use IsUser;
    use PasswordPolicy;
        
    public ?string $description = null;
    public ?string $email = null;
    public ?string $fname = null;
    public int $id;
    public ?string $lname = null;
    protected static string $_table = 'users';
    public ?string $username = null;
    public const whiteList = ['username', 'email', 'password', 'fname', 'lname', 'description'];

    /**
     * Implements beforeSave function described in Model parent class.  
     * Ensures password is not in plain text but a hashed one.  The 
     * reset_password flag is also set to 0.
     *
     * @return void
     */
    public function beforeSave(): void {
        $this->timeStamps();
        $this->userBeforeSave();
    }

    /**
     * Retrieves a list of all users except current logged in user.
     *
     * @param int $current_user_id The id of the currently logged in user.
     * @param array $params Used to build conditions for database query.  The 
     * default value is an empty array.
     * @return array|bool The list of users that is returned from the database.
     */
    public static function findAllUsersExceptCurrent(int $current_user_id, array $params = []): array|bool {
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
    public static function findByUserName(string $username): bool|object {
        return self::findFirst(['conditions'=> "username = ?", 'bind'=>[$username]]);
    }

    public function getRememberTokenName(): ?string { return null; }

    /**
     * Performs validation on user related forms.
     *
     * @return void
     */
    public function validator(): void {
        $this->runValidation('fname', ['required', 'max:150'], 'First Name');
        $this->runValidation('lname', ['required', 'max:150'], 'Last Name');
        $this->runValidation('email', ['required', 'max:150'], 'Email');
        $this->runValidation('password', ['required'], 'Password');
        $this->runValidation('username', ['required'], 'Username');
        
        if($this->isNew()) {
            $this->runValidation('username', ['min:6', 'max:150', 'unique:'.self::class], 'Username');
        }
        
        if($this->isNew() || $this->changePassword) {
            
            if($this->isMinLength()) {
                $this->runValidation('password', ["min:{$this->minLength()}"], 'Password');
            }
            if($this->isMaxLength()) {
                $this->runValidation('password', ["max:{$this->maxLength()}"], 'Password');
            }
            if($this->lowerChar()) {
                $this->runValidation('password', ['lower'], 'Password');
            }
            if($this->upperChar()) {
                $this->runValidation('password', ['upper'], 'Password');
            }
            if($this->numericChar()) {
                $this->runValidation('password', ['number'], 'Password');
            }
            if($this->specialChar()) {
                $this->runValidation('password', ['special'], 'Password');
            }
            $this->runValidation('confirm', ["match:{$this->password}"], 'Confirm Password');
        }
    }
}