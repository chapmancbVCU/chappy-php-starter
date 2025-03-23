<?php
namespace Core\Lib\Utilities;

/**
 * Manages the usage of configuration files.
 */
class Config
{
    protected static $configs = [];

    /**
     * Load all config files from a directory.
     *
     * @param string $path Path to config directory
     */
    public static function load(string $path): void {
        foreach (glob($path . '/*.php') as $file) {
            $key = basename($file, '.php');
            $configData = require $file;

            // Ensure we are working with an array
            if (!Arr::isArray($configData)) {
                throw new \Exception("Configuration file {$file} must return an array. Error in: {$file}");
            }

            // Process env() replacements and type conversion
            Arr::walkRecursive($configData, function (&$value) {
                if (is_string($value) && preg_match('/^env\((.*)\)$/', $value, $matches)) {
                    $envKey = trim($matches[1], "'\"");
                    $value = Env::get($envKey) ?? $value;
                }

                // Convert numeric strings to actual numbers
                if (is_string($value) && is_numeric($value)) {
                    $value = $value + 0; // Converts "12" to 12, "30.5" to 30.5, etc.
                }

                // Convert "true"/"false" strings to actual boolean values
                if ($value === 'true') {
                    $value = true;
                } elseif ($value === 'false') {
                    $value = false;
                }
            });

            static::$configs[$key] = $configData;
        }
    }

    /**
     * Get a configuration value.
     *
     * @param string $key Dot notation key (e.g. "app.name")
     * @param mixed $default Default value if key not found
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed {
        $keys = explode('.', $key);
        $value = static::$configs;

        foreach ($keys as $segment) {
            if (!isset($value[$segment])) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * Set a configuration value.
     *
     * @param string $key Dot notation key
     * @param mixed $value Value to set
     */
    public static function set(string $key, mixed $value): void {
        $keys = explode('.', $key);
        $temp = &static::$configs;

        foreach ($keys as $segment) {
            if (!isset($temp[$segment])) {
                $temp[$segment] = [];
            }
            $temp = &$temp[$segment];
        }

        $temp = $value;
    }
}
