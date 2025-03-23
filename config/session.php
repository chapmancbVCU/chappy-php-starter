<?php

/**
 * Session and Cookie Configuration.
 */

 return [
    'current_user_session_name' => $_ENV['CURRENT_USER_SESSION_NAME'] ?? 'user_session',
    'remember_me_cookie_name' => $_ENV['REMEMBER_ME_COOKIE_NAME'] ?? 'remember_me',
    'remember_me_cookie_expiry' => $_ENV['REMEMBER_ME_COOKIE_EXPIRY'] ?? 2592000, // Default: 30 days in seconds (30 * 24 * 60 * 60)
];