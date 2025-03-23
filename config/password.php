<?php

/**
 * Password Complexity Configuration.
 */

 return [
    'pw_lower_char' => filter_var($_ENV['PW_LOWER_CHAR'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'pw_upper_char' => filter_var($_ENV['PW_UPPER_CHAR'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'pw_num_char' => filter_var($_ENV['PW_NUM_CHAR'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'pw_special_char' => filter_var($_ENV['PW_SPECIAL_CHAR'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'pw_min_length' => is_numeric($_ENV['PW_MIN_LENGTH'] ?? null) ? (int) $_ENV['PW_MIN_LENGTH'] : 8,
    'pw_max_length' => is_numeric($_ENV['PW_MAX_LENGTH'] ?? null) ? (int) $_ENV['PW_MAX_LENGTH'] : 32,
    'set_pw_min_length' => filter_var($_ENV['SET_PW_MIN_LENGTH'] ?? true, FILTER_VALIDATE_BOOLEAN), // Now correctly a boolean
    'set_pw_max_length' => filter_var($_ENV['SET_PW_MAX_LENGTH'] ?? true, FILTER_VALIDATE_BOOLEAN), // Now correctly a boolean
];

