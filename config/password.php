<?php
use Core\Lib\Utilities\Env;
/**
 * Password Complexity Configuration.
 */

 return [
    'pw_lower_char' => filter_var(Env::get('PW_LOWER_CHAR') ?? true, FILTER_VALIDATE_BOOLEAN),
    'pw_upper_char' => filter_var(Env::get('PW_UPPER_CHAR') ?? true, FILTER_VALIDATE_BOOLEAN),
    'pw_num_char' => filter_var(Env::get('PW_NUM_CHAR') ?? true, FILTER_VALIDATE_BOOLEAN),
    'pw_special_char' => filter_var(Env::get('PW_SPECIAL_CHAR') ?? true, FILTER_VALIDATE_BOOLEAN),
    'pw_min_length' => is_numeric(Env::get('PW_MIN_LENGTH') ?? null) ? (int) Env::get('PW_MIN_LENGTH') : 8,
    'pw_max_length' => is_numeric(Env::get('PW_MAX_LENGTH') ?? null) ? (int) Env::get('PW_MAX_LENGTH') : 32,
    'set_pw_min_length' => filter_var(Env::get('SET_PW_MIN_LENGTH') ?? true, FILTER_VALIDATE_BOOLEAN),
    'set_pw_max_length' => filter_var(Env::get('SET_PW_MAX_LENGTH') ?? true, FILTER_VALIDATE_BOOLEAN),
];

