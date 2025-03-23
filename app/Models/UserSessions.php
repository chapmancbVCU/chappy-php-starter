<?php
namespace App\Models;
use Core\Lib\Utilities\Env;
use Core\{Cookie, Model, Session};

/**
 * Supports operations of the User Session model.  Extends the Model class.
 */
class UserSessions extends Model{
    public $id;
    public $session;
    protected static $_table = 'user_sessions';
    public $user_agent;
    public $user_id;

    /**
     * Called before save.
     *
     * @return void
     */
    public function beforeSave(): void {
        $this->timeStamps();
    }

    /**
     * Retrieves User Session information from cookie.
     *
     * @return bool|UserSessions An object containing information about the 
     * current user's session.  If a user session does not exist false is 
     * returned.
     */
    public static function getFromCookie() {
        $userSession = null;
        if(Cookie::exists(Env::get('REMEMBER_ME_COOKIE_NAME'))) {
            $userSession = self::findFirst([
              'conditions' => "user_agent = ? AND session = ?",
              'bind' => [Session::uagent_no_version(), Cookie::get(Env::get('REMEMBER_ME_COOKIE_NAME'))]
            ]);
        }
        return $userSession;
    }
}