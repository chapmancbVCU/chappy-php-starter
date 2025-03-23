<?php
namespace App\Models;
use Core\Model;
use Core\Validators\RequiredValidator;

/**
 * Extends the Model class.  Supports functions for the Login model.
 */
class Login extends Model {
    public $password;
    public $remember_me;
    protected static $_table = 'tmp_fake';
    public $username;

    /**
     * Returns result for remember me checkbox so user stays logged in if it's
     * checked.
     *
     * @return bool The value for remember_me checkbox.
     */
    public function getRememberMeChecked(): bool {
        return $this->remember_me == 'on';
    }

    /**
     * Performs form validation checks for the login screen.
     *
     * @return void
     */
    public function validator(): void {
        $this->runValidation(new RequiredValidator($this, ['field' => 'username', 'message' => 'Username is required']));
        $this->runValidation(new RequiredValidator($this, ['field' => 'password', 'message' => 'Password is required']));
    }
}