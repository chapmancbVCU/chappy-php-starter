<?php
namespace App\Models;
use Core\Models\User;

/**
 * The application's user model.  Extends the framework's base User model,
 * which supplies the columns, authentication, and validation the framework
 * relies on.  Add application-specific columns and methods here, or override
 * validator() to customize registration and profile validation.
 *
 * To use a different class, set user_model in config/auth.php.  The class
 * must extend Core\Models\User.
 */
class Users extends User {

}
