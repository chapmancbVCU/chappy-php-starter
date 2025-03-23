<?php
namespace Core;
/**
 * Manages cookies used by this application.  The $_COOKIE superglobal 
 * variable is an associative array.
 */
class Cookie {
    /**
     * Deletes a cookie from the $_COOKIE superglobal variable.
     *
     * @param string $name The name of the cookie we want to remove.  Also 
     * known as the constant REMEMBER_ME_COOKIE_NAME.
     * @return void
     */
    public static function delete(string $name): void {
        self::set($name, '', time() - 1);
    }

    /**
     * The name of the cookie we want to work with that is found in the 
     * $_COOKIE superglobal.  
     *
     * @param string $name The cookie identification string we want to 
     * retrieve from the $_COOKIE superglobal.  Also known as the constant 
     * REMEMBER_ME_COOKIE_NAME.
     * @return string|int The name of the cookie specified in the $name parameter.
     */
    public static function get(string $name): string|int {
        return $_COOKIE[$name];
    }

    /**
     * Checks if a particular cookie exists in the $_COOKIE superglobal 
     * variable.
     *
     * @param string $name The cookie identification string we want to check 
     * if it exists in the $_COOKIE superglobal variable.  Also known as the
     * constant REMEMBER_ME_COOKIE_NAME.
     * @return bool True if the cookie exists.  Otherwise false.
     */
    public static function exists(string $name): bool {
        return isset($_COOKIE[$name]);
    }

    /**
     * Sets a cookie to the $_COOKIE superglobal variable.  Information that 
     * it needs are its name, a value, and the amount of time we want this 
     * cookie to exist.
     *
     * @param string $name The value for REMEMBER_ME_COOKIE_NAME constant.
     * @param string $value The value of the cookie unique to this session.
     * @param int $expiry The amount of time we want this cookie to exist 
     * before it expires.
     * @return bool True if the cookie is successfully set.  Otherwise it 
     * returns false.
     */
    public static function set(string $name, string $value, int $expiry): bool {
        if(setCookie($name, $value, time() + $expiry, '/')) {
            return true;
        }
        return false;
    } 
}