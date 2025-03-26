<?php
use Core\Lib\Utilities\Env;
/**
 * Configuration for console.
 */
return [
    'background_color' => Env::get('BACKGROUND_COLOR') ?? 'black',
    'text_color' => Env::get('TEXT_COLOR') ?? 'white',
];