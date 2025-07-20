<?php
use Core\Lib\Utilities\Env;
/**
 * E-mail configuration.
 */

return [
    'mailer_dsn' => Env::get('MAILER_DSN'),
    'mail_from_address' => Env::get('MAIL_FROM_ADDRESS'),
    'mail_from_name' => Env::get('MAIL_FROM_NAME')
];