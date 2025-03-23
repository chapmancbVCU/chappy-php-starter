<?php
namespace Console\Helpers;

use Core\Lib\Utilities\Arr;
use Core\Lib\Utilities\Env;
use Core\Lib\Logging\Logger;
use Symfony\Component\Console\Command\Command;

/**
 * Contains functions for miscellaneous tasks.
 */
class Tools {
    /**
     * Returns dashed border.
     *
     * @return string A dashed border.
     */
    public static function border() {
        return '--------------------------------------------------';
    }

    /**
     * Generates output messages for console commands.
     *
     * @param string $message The message we want to show.
     * @param string $level The level of severity for log file.  The valid 
     * levels are info, debug, warning, error, critical, alert, and emergency.
     * @param string $background The background color.  This function 
     * supports black, red, green, yellow, blue, magenta, cyan, and 
     * light-grey
     * @param string $text The color of the text.  This function supports 
     * black, white, dark-grey, red, green, brown, blue, magenta, cyan, 
     * light-cyan, light-grey, light-red, light green, light-blue, and 
     * light-magenta.
     * @return void
     */
    public static function info(string $message, string $level = 'info', ?string $background = null, ?string $text = null): void {
        // Load default colors from .env if not provided
        $background = $background ?? Env::get('BACKGROUND_COLOR', 'green'); // Default: green
        $text = $text ?? Env::get('TEXT_COLOR', 'light-grey'); // Default: light-grey
        $backgroundColor = [
            'black' => '40', 'red' => '41', 'green' => '42', 'yellow' => '43',
            'blue' => '44', 'magenta' => '45', 'cyan' => '46', 'light-grey' => '47'
        ];

        $textColor = [
            'black' => '0;30', 'white' => '1;37', 'dark-grey' => '1;30', 'red' => '0;31',
            'green' => '0;32', 'brown' => '0;33', 'yellow' => '1;33', 'blue' => '0;34',
            'magenta' => '0;35', 'cyan' => '0;36', 'light-cyan' => '1;36', 'light-grey' => '0;37',
            'light-red' => '1;31', 'light-green' => '1;32', 'light-blue' => '1;34', 'light-magenta' => '1;35'
        ];
        
        // Validate severity level and log to file.
        $validLevels = ['info', 'debug', 'warning', 'error', 'critical', 'alert', 'emergency'];
        if (!Arr::exists($validLevels, strtolower($level))) {
            $level = 'info'; // Default to 'info' if invalid level provided
        }
        Logger::log($message, $level);

        // Perform console logging
        if(Arr::exists($backgroundColor, $background) && Arr::exists($textColor, $text)) {
            echo "\e[".$textColor[$text].";".$backgroundColor[$background]."m\n\n"."   ".$message."\n\e[0m\n";
        } else {
            echo "\e[0;37;41m\n\n"."   Invalid background or text color.\n\e[0m\n";
        }
    }

    /**
     * Generates files for console commands
     *
     * @param string $path Where the file will be written.
     * @param string $content The contents of the file to be created.
     * @param string $name The name of the file, class, or other relevant information.
     * @return int A value that indicates success, invalid, or failure.
     */
    public static function writeFile(string $path, string $content, string $name): int {
        if(!file_exists($path)) {
            $resp = file_put_contents($path, $content);
            Tools::info(ucfirst($name) . ' successfully created');
            return Command::SUCCESS;
        } else {
            Tools::info(ucfirst($name) . ' already exists', 'debug', 'red');
            return Command::FAILURE;
        }
    }
}