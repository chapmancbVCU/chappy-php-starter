<?php
namespace Core;

use Core\Cookie;
use Core\Session;
use App\Models\Users;
use Core\Lib\Utilities\Env;
use Core\Lib\Logging\Logger;
/**
 * Supports session management
 */
class SessionManager {
    /**
     * Checks if session exists and logs user in.  Logs user out if account 
     * status is inactive.
     *
     * @return void
     */
    public static function initialize(): void {
        if (!Session::exists(Env::get('CURRENT_USER_SESSION_NAME')) && Cookie::exists(Env::get('REMEMBER_ME_COOKIE_NAME'))) {
            $user = Users::loginUserFromCookie();
            
            if ($user) {
                if ($user->inactive == 1) {
                    $user->logout();
                    Logger::log("Inactive user attempted auto-login: User ID {$user->id}", 'warning');
                } else {
                    Session::set(Env::get('CURRENT_USER_SESSION_NAME'), $user->id);
                    Logger::log("User auto-logged in via Remember Me: User ID {$user->id}", 'info');
                }
            }
        }
    }
}
