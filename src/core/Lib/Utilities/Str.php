<?php

declare(strict_types=1);

namespace Core\Lib\Utilities;

use Ramsey\Uuid\Uuid;
use Doctrine\Inflector\InflectorFactory;

/**
 * String utility class.
 */
class Str
{
    /**
     * Get the portion of a string after the first occurrence of a given value.
     *
     * @param string $subject The input string.
     * @param string $search The substring to search for.
     */
    public static function after(string $subject, string $search): string
    {
        if (($position = strpos($subject, $search)) !== false) {
            return substr($subject, $position + strlen($search));
        }
        return '';
    }

    /**
     * Convert a string to its ASCII representation.
     *
     * @param string $value The input string.
     */
    public static function ascii(string $value): string
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT', $value) ?: $value;
    }
    
    /**
     * Base64 encode a string.
     *
     * @param string $value The input string.
     */
    public static function base64Encode(string $value): string
    {
        return base64_encode($value);
    }

    /**
     * Base64 decode a string.
     *
     * @param string $value The base64 encoded string.
     */
    public static function base64Decode(string $value): string
    {
        return base64_decode($value);
    }

    /**
     * Get the portion of a string before the first occurrence of a given value.
     *
     * @param string $subject The input string.
     * @param string $search The substring to search for.
     */
    public static function before(string $subject, string $search): string
    {
        return strpos($subject, $search) !== false
            ? substr($subject, 0, strpos($subject, $search))
            : $subject;
    }

    /**
     * Get the substring between two given substrings.
     *
     * @param string $value The input string.
     * @param string $start The starting substring.
     * @param string $end The ending substring.
     */
    public static function between(string $value, string $start, string $end): string
    {
        if (($startPos = strpos($value, $start)) === false) return '';
        $startPos += strlen($start);
        if (($endPos = strpos($value, $end, $startPos)) === false) return '';
        return substr($value, $startPos, $endPos - $startPos);
    }

    /**
     * Convert a string to camelCase.
     *
     * @param string $value The input string.
     */
    public static function camel(string $value): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value))));
    }

    /**
     * Split a string into chunks.
     *
     * @param string $value The input string.
     * @param int $length The chunk length.
     */
    public static function chunk(string $value, int $length = 1): array
    {
        return str_split($value, $length);
    }

    /**
     * Compare two strings.
     *
     * @param string $string1 The first string.
     * @param string $string2 The second string.
     */
    public static function compare(string $string1, string $string2): int
    {
        return strcmp($string1, $string2);
    }

    /**
     * Determine if a string contains a given substring.
     *
     * @param string $haystack The string to search within.
     * @param string $needle The substring to search for.
     */
    public static function contains(string $haystack, string $needle): bool
    {
        return str_contains($haystack, $needle);
    }

    /**
     * Calculate CRC32 hash of a string.
     *
     * @param string $value The input string.
     */
    public static function crc32(string $value): int
    {
        return crc32($value);
    }

    /**
     * Determine if a string ends with a given substring.
     *
     * @param string $haystack The string to check.
     * @param string $needle The substring to check for.
     */
    public static function endsWith(string $haystack, string $needle): bool
    {
        return str_ends_with($haystack, $needle);
    }

    /**
     * Create excerpts around specific words within a string.
     *
     * @param string $text The input string.
     * @param string $phrase The phrase to excerpt around.
     * @param int $radius The number of characters around the phrase.
     */
    public static function excerpt(string $text, string $phrase, int $radius = 100): string
    {
        $position = mb_strpos($text, $phrase);
        if ($position === false) return '';

        $start = max(0, $position - $radius);
        $end = min(mb_strlen($text), $position + mb_strlen($phrase) + $radius);

        return mb_substr($text, $start, $end - $start);
    }

    /**
     * Ensure a string ends with a given value.
     *
     * @param string $value The input string.
     * @param string $cap The ending string to append if missing.
     */
    public static function finish(string $value, string $cap): string
    {
        return str_ends_with($value, $cap) ? $value : $value . $cap;
    }

    /**
     * Convert a string to headline case.
     *
     * @param string $value The input string.
     */
    public static function headline(string $value): string
    {
        return mb_convert_case(str_replace(['-', '_'], ' ', $value), MB_CASE_TITLE);
    }

    /**
     * Check if the given string is pure ASCII.
     *
     * @param string $value The input string.
     */
    public static function isAscii(string $value): bool
    {
        return mb_check_encoding($value, 'ASCII');
    }

    /**
     * Determine if a string is empty.
     *
     * @param string $value The input string.
     */
    public static function isEmpty(string $value): bool
    {
        return trim($value) === '';
    }

    /**
     * Determine if a string is a valid JSON.
     *
     * @param string $value The input string.
     */
    public static function isJson(string $value): bool
    {
        json_decode($value);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Check if a string is a valid UUID.
     *
     * @param string $value The input string.
     */
    public static function isUuid(string $value): bool
    {
        return Uuid::isValid($value);
    }

    /**
     * Convert a string to kebab-case.
     *
     * @param string $value The input string.
     */
    public static function kebab(string $value): string
    {
        return self::snake($value, '-');
    }

    /**
     * Find the position of the last occurrence of a substring.
     *
     * @param string $haystack The string to search in.
     * @param string $needle The substring to search for.
     * @return int|false
     */
    public static function lastPosition(string $haystack, string $needle)
    {
        return strrpos($haystack, $needle);
    }

    /**
     * Converts the first character of a string to lowercase.
     *
     * @param string $value The input string.
     * @return string The string with the first character converted to lowercase.
     */
    public static function lcfirst(string $value): string
    {
        return lcfirst($value);
    }

    /**
     * Get the length of a string using multibyte support.
     *
     * @param string $value The input string.
     */
    public static function length(string $value): int
    {
        return mb_strlen($value);
    }

    /**
     * Calculate Levenshtein distance between two strings.
     *
     * @param string $string1 The first string.
     * @param string $string2 The second string.
     */
    public static function levenshtein(string $string1, string $string2): int
    {
        return levenshtein($string1, $string2);
    }

    /**
     * Limit the number of characters in a string.
     *
     * @param string $value The input string.
     * @param int $limit Maximum number of characters.
     * @param string $end Ending to append if truncated.
     */
    public static function limit(string $value, int $limit = 100, string $end = '...'): string
    {
        return mb_strlen($value) <= $limit ? $value : mb_substr($value, 0, $limit) . $end;
    }

    /**
     * Convert a string to lowercase.
     *
     * @param string $value The input string.
     */
    public static function lower(string $value): string
    {
        return mb_strtolower($value);
    }

    /**
     * Mask portions of a string with a given character.
     *
     * @param string $string The input string.
     * @param string $character The mask character.
     * @param int $start The starting position for masking.
     * @param int|null $length The number of characters to mask.
     */
    public static function mask(string $string, string $character = '*', int $start = 0, ?int $length = null): string
    {
        $length = $length ?? mb_strlen($string);
        return mb_substr($string, 0, $start)
            . str_repeat($character, $length)
            . mb_substr($string, $start + $length);
    }

    /**
     * Return the MD5 hash of a string.
     *
     * @param string $value The input string.
     */
    public static function md5(string $value): string
    {
        return md5($value);
    }

    /**
     * Format a number with grouped thousands.
     *
     * @param float $number The number to format.
     * @param int $decimals Number of decimal points.
     * @param string $decimalSeparator Decimal separator.
     * @param string $thousandSeparator Thousand separator.
     */
    public static function numberFormat(float $number, int $decimals = 0, string $decimalSeparator = '.', string $thousandSeparator = ','): string
    {
        return number_format($number, $decimals, $decimalSeparator, $thousandSeparator);
    }

    /**
     * Pad the left side of a string with a given character.
     *
     * @param string $value The input string.
     * @param int $length The desired total length after padding.
     * @param string $pad The padding character.
     */
    public static function padLeft(string $value, int $length, string $pad = ' '): string
    {
        return str_pad($value, $length, $pad, STR_PAD_LEFT);
    }

    /**
     * Pad the right side of a string with a given character.
     *
     * @param string $value The input string.
     * @param int $length The desired total length after padding.
     * @param string $pad The padding character.
     */
    public static function padRight(string $value, int $length, string $pad = ' '): string
    {
        return str_pad($value, $length, $pad, STR_PAD_RIGHT);
    }

    /**
     * Convert a string to PascalCase (StudlyCase).
     *
     * @param string $value The input string.
     */
    public static function pascal(string $value): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $value)));
    }

    /**
     * Pluralize a word.
     *
     * @param string $word The word to pluralize.
     * @param int $count The number to determine singular or plural.
     */
    public static function plural(string $word, int $count = 2): string
    {
        $inflector = InflectorFactory::create()->build();
        return $count === 1 ? $word : $inflector->pluralize($word);
    }

    /**
     * Find the position of the first occurrence of a substring.
     *
     * @param string $haystack The string to search in.
     * @param string $needle The substring to search for.
     * @return int|false
     */
    public static function position(string $haystack, string $needle)
    {
        return strpos($haystack, $needle);
    }

   /**
     * Generate a random string of a specified length.
     *
     * @param int $length The desired length of the random string.
     */
    public static function random(int $length = 16): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Repeat a string.
     *
     * @param string $value The input string.
     * @param int $times Number of times to repeat.
     */
    public static function repeat(string $value, int $times): string
    {
        return str_repeat($value, $times);
    }

    /**
     * Replace placeholders sequentially with values from an array.
     *
     * @param string $search The placeholder string to replace.
     * @param array $replace Array of replacement values.
     * @param string $subject The string to perform replacements on.
     */
    public static function replaceArray(string $search, array $replace, string $subject): string
    {
        $pattern = '/' . preg_quote($search, '/') . '/';
        foreach ($replace as $value) {
            $subject = preg_replace($pattern, $value, $subject, 1);
        }
        return $subject;
    }

    /**
     * Replace the first occurrence of a substring.
     *
     * @param string $search The substring to find.
     * @param string $replace The substring to replace with.
     * @param string $subject The string to perform replacement on.
     */
    public static function replaceFirst(string $search, string $replace, string $subject): string
    {
        $position = strpos($subject, $search);
        return $position !== false ? substr_replace($subject, $replace, $position, strlen($search)) : $subject;
    }

    /**
     * Replace the last occurrence of a substring.
     *
     * @param string $search The substring to find.
     * @param string $replace The substring to replace with.
     * @param string $subject The string to perform replacement on.
     */
    public static function replaceLast(string $search, string $replace, string $subject): string
    {
        $position = strrpos($subject, $search);
        return $position !== false ? substr_replace($subject, $replace, $position, strlen($search)) : $subject;
    }

    /**
     * Replace multiple occurrences of different values in a string.
     *
     * @param array $replacements Associative array of replacements [search => replace].
     * @param string $subject The string to perform replacements on.
     */
    public static function replaceMultiple(array $replacements, string $subject): string
    {
        return str_replace(array_keys($replacements), array_values($replacements), $subject);
    }

    /**
     * Reverse a given string.
     *
     * @param string $value The input string.
     */
    public static function reverse(string $value): string
    {
        return implode('', array_reverse(mb_str_split($value)));
    }

    /**
     * Return the SHA1 hash of a string.
     *
     * @param string $value The input string.
     */
    public static function sha1(string $value): string
    {
        return sha1($value);
    }

    /**
     * Shuffle characters in a string.
     *
     * @param string $value The input string.
     */
    public static function shuffle(string $value): string
    {
        return str_shuffle($value);
    }

    /**
     * Calculate similarity between two strings.
     *
     * @param string $string1 The first string.
     * @param string $string2 The second string.
     */
    public static function similarity(string $string1, string $string2): int
    {
        similar_text($string1, $string2, $percent);
        return (int)$percent;
    }

    /**
     * Convert a string to snake_case.
     *
     * @param string $value The input string.
     * @param string $delimiter The delimiter used for snake casing.
     */
    public static function snake(string $value, string $delimiter = '_'): string
    {
        $value = preg_replace('/[A-Z]/', $delimiter.'$0', lcfirst($value));
        return strtolower(preg_replace('/[\s]+/', $delimiter, $value));
    }


    /**
     * Convert a string to a URL-friendly slug.
     *
     * @param string $title The input string.
     * @param string $separator The separator used in the slug.
     */
    public static function slug(string $title, string $separator = '-'): string
    {
        $title = preg_replace('/[^\pL\d]+/u', $separator, $title);
        return trim(strtolower($title), $separator);
    }

    /**
     * Remove excessive whitespace from a string.
     *
     * @param string $value The input string.
     */
    public static function squish(string $value): string
    {
        return preg_replace('/\s+/', ' ', trim($value));
    }

    /**
     * Determine if a string starts with a given substring.
     *
     * @param string $haystack The string to search within.
     * @param string $needle The substring to check for.
     */
    public static function startsWith(string $haystack, string $needle): bool
    {
        return str_starts_with($haystack, $needle);
    }

    /**
     * Strip all whitespace from a string.
     *
     * @param string $value The input string.
     */
    public static function stripWhitespace(string $value): string
    {
        return preg_replace('/\s+/', '', $value);
    }

    /**
     * Convert a string to StudlyCase (PascalCase).
     *
     * @param string $value The input string.
     */
    public static function studly(string $value): string
    {
        return self::pascal($value);
    }

    /**
     * Get a part of a string.
     *
     * @param string $value The input string.
     * @param int $start The starting position.
     * @param int|null $length The number of characters to extract.
     */
    public static function substr(string $value, int $start, ?int $length = null): string
    {
        return mb_substr($value, $start, $length);
    }

    /**
     * Count occurrences of a substring.
     *
     * @param string $haystack The input string.
     * @param string $needle The substring to count.
     */
    public static function substrCount(string $haystack, string $needle): int
    {
        return substr_count($haystack, $needle);
    }

    /**
     * Swap keys with values in an array and return as a string.
     *
     * @param array $array The input array.
     */
    public static function swapKeyValue(array $array): string
    {
        return implode(', ', array_map(fn($key, $value) => "$value => $key", array_keys($array), $array));
    }

    /**
     * Convert a string to title case.
     *
     * @param string $value The input string.
     */
    public static function title(string $value): string
    {
        return ucwords(mb_strtolower($value));
    }

    /**
     * Convert a string into an array.
     *
     * @param string $value The input string.
     */
    public static function toArray(string $value): array
    {
        return mb_str_split($value);
    }

    /**
     * Capitalize the first character of a string.
     *
     * @param string $value The input string.
     */
    public static function ucfirst(string $value): string
    {
        return ucfirst($value);
    }

    /**
     * Convert a string to UPPERCASE.
     *
     * @param string $value The input string.
     */
    public static function upper(string $value): string
    {
        return mb_strtoupper($value);
    }

    /**
     * Generate a UUID (Universally Unique Identifier).
     */
    public static function uuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * Count the number of words in a string.
     *
     * @param string $value The input string.
     */
    public static function wordCount(string $value): int
    {
        return str_word_count($value);
    }

    /**
     * Limit a string to a certain number of words.
     *
     * @param string $value The input string.
     * @param int $words Number of words to limit to.
     * @param string $end Ending to append if truncated.
     */
    public static function words(string $value, int $words = 10, string $end = '...'): string
    {
        preg_match('/^(?:\S+\s+){0,' . ($words - 1) . '}\S+/u', $value, $matches);
        return isset($matches[0]) && mb_strlen($matches[0]) < mb_strlen($value) ? $matches[0] . $end : $value;
    }

    /**
     * Wrap a string with a given value.
     *
     * @param string $value The input string.
     * @param string $wrapWith The wrapping string.
     */
    public static function wrap(string $value, string $wrapWith): string
    {
        return $wrapWith . $value . $wrapWith;
    }
}
