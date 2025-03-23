<?php

namespace Core\Lib\Utilities;

/**
 * Contains functions that support array operations.
 */
class Arr
{
    /**
     * Add a value to an array if the key does not exist.
     *
     * @param array $array The array to modify.
     * @param string|int $key The key to check.
     * @param mixed $value The value to add.
     * @return array The modified array.
     */
    public static function add(array $array, string|int $key, mixed $value): array {
        if (!array_key_exists($key, $array)) {
            $array[$key] = $value;
        }

        return $array;
    }

    /**
     * Split an array into two separate arrays: one with keys, one with values.
     *
     * @param array $array The array to divide.
     * @return array An array containing two arrays: [keys, values].
     */
    public static function arrayDivide(array $array): array
    {
        return [array_keys($array), array_values($array)];
    }

    /**
     * Pluck a nested value from an array.
     *
     * @param array $array The source array.
     * @param array|string $keys The nested keys to extract.
     * @return array The plucked values.
     */
    public static function arrayPluckMulti(array $array, array|string $keys): array
    {
        $result = [];

        foreach ($array as $item) {
            $value = static::get($item, $keys);
            if ($value !== null) {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * Shuffle an associative array while preserving keys.
     *
     * @param array $array The array to shuffle.
     * @return array The shuffled array.
     */
    public static function arrayShuffleAssoc(array $array): array
    {
        $keys = array_keys($array);
        shuffle($keys);
        return array_merge(array_flip($keys), $array);
    }

    /**
     * Collapse a multi-dimensional array into a single-level array.
     *
     * @param array $array The multi-dimensional array.
     * @return array The collapsed array.
     */
    public static function collapse(array $array): array {
        $result = [];

        foreach ($array as $values) {
            if (is_array($values)) {
                $result = array_merge($result, $values);
            }
        }

        return $result;
    }

    /**
     * Split an array into chunks of a given size.
     *
     * @param array $array The array to split.
     * @param int $size The size of each chunk.
     * @param bool $preserveKeys Whether to preserve keys.
     * @return array An array of chunked arrays.
     */
    public static function chunk(array $array, int $size, bool $preserveKeys = false): array
    {
        return array_chunk($array, $size, $preserveKeys);
    }

    /**
     * Chunk an array into groups based on a callback function.
     *
     * @param array $array The array to chunk.
     * @param callable $callback The function to determine chunks.
     * @return array The chunked array.
     */
    public static function chunkBy(array $array, callable $callback): array {
        $result = [];
        $chunk = [];

        foreach ($array as $key => $value) {
            if (!empty($chunk) && !$callback($value, end($chunk))) {
                $result[] = $chunk;
                $chunk = [];
            }
            $chunk[] = $value;
        }

        if (!empty($chunk)) {
            $result[] = $chunk;
        }

        return $result;
    }

    /**
     * Determine if a given value exists in an array.
     *
     * @param array $array The array to search.
     * @param mixed $value The value to find.
     * @param bool $strict Whether to perform a strict comparison.
     * @return bool True if the value exists, false otherwise.
     */
    public static function contains(array $array, mixed $value, bool $strict = false): bool
    {
        return in_array($value, $array, $strict);
    }

    /**
     * Compute the Cartesian product of multiple arrays.
     *
     * @param array ...$arrays The arrays to compute the product for.
     * @return array The Cartesian product.
     */
    public static function crossJoin(array ...$arrays): array {
        $result = [[]];

        foreach ($arrays as $array) {
            $append = [];

            foreach ($result as $product) {
                foreach ($array as $item) {
                    $append[] = array_merge($product, [$item]);
                }
            }

            $result = $append;
        }

        return $result;
    }
    
    /**
     * Recursively merge two or more arrays.
     *
     * @param array ...$arrays The arrays to merge.
     * @return array The merged array.
     */
    public static function deepMerge(array ...$arrays): array {
        $base = array_shift($arrays);

        foreach ($arrays as $array) {
            foreach ($array as $key => $value) {
                if (is_array($value) && isset($base[$key]) && is_array($base[$key])) {
                    $base[$key] = static::deepMerge($base[$key], $value);
                } else {
                    $base[$key] = $value;
                }
            }
        }

        return $base;
    }

    /**
     * Recursively compute the difference between two arrays with keys.
     *
     * @param array $array1 The first array.
     * @param array $array2 The second array.
     * @return array The difference.
     */
    public static function diffAssocRecursive(array $array1, array $array2): array {
        $difference = array_diff_assoc($array1, $array2);

        foreach ($array1 as $key => $value) {
            if (isset($array2[$key]) && is_array($value) && is_array($array2[$key])) {
                $recursiveDiff = self::diffAssocRecursive($value, $array2[$key]);
                if (!empty($recursiveDiff)) {
                    $difference[$key] = $recursiveDiff;
                }
            }
        }

        return $difference;
    }

    /**
     * Convert a multi-dimensional array into dot notation keys.
     *
     * @param array $array The multi-dimensional array.
     * @param string $prepend The prefix for keys.
     * @return array The array with dot notation keys.
     */
    public static function dot(array $array, string $prepend = ''): array {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results += static::dot($value, $prepend . $key . '.');
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }


    /**
     * Get all items except the specified keys.
     *
     * @param array $array The source array.
     * @param array $keys The keys to exclude.
     * @return array The filtered array.
     */
    public static function except(array $array, array $keys): array {
        return array_diff_key($array, array_flip($keys));
    }

    /**
     * Check if a key exists in an array (non-dot notation).
     *
     * @param array $array The source array.
     * @param string|int $key The key to check.
     * @return bool True if the key exists, false otherwise.
     */
    public static function exists(array $array, string|int $key): bool {
        return array_key_exists($key, $array);
    }

    /**
     * Fill an array with a specified value.
     *
     * @param int $startIndex The first index to use.
     * @param int $count The number of elements to insert.
     * @param mixed $value The value to use for filling.
     * @return array The filled array.
     */
    public static function fill(int $startIndex, int $count, mixed $value): array
    {
        return array_fill($startIndex, $count, $value);
    }

    /**
     * Filter an array using a callback function.
     *
     * @param array $array The source array.
     * @param callable $callback The filtering function.
     * @return array The filtered array.
     */
    public static function filter(array $array, callable $callback): array
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Filter an array to include only the specified keys.
     *
     * @param array $array The source array.
     * @param array $keys The keys to keep.
     * @return array The filtered array.
     */
    public static function filterByKeys(array $array, array $keys): array {
        return array_intersect_key($array, array_flip($keys));
    }

    /**
     * Filter an array by its values.
     *
     * @param array $array The array to filter.
     * @param callable $callback The function to apply for filtering.
     * @return array The filtered array.
     */
    public static function filterByValue(array $array, callable $callback): array {
        return array_filter($array, $callback);
    }

    /**
     * Get the first element that passes a given test.
     *
     * @param array $array The source array.
     * @param callable|null $callback The function to determine a match.
     * @param mixed|null $default The default value if no match is found.
     * @return mixed The first matching value or default.
     */
    public static function first(array $array, ?callable $callback = null, mixed $default = null): mixed {
        if ($callback === null) {
            return reset($array) ?: $default;
        }

        foreach ($array as $value) {
            if ($callback($value)) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param array $array The multi-dimensional array.
     * @param int $depth The depth limit.
     * @return array The flattened array.
     */
    public static function flatten(array $array, int $depth = INF): array {
        $result = [];

        foreach ($array as $value) {
            if (is_array($value) && $depth > 1) {
                $result = array_merge($result, static::flatten($value, $depth - 1));
            } else {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * Flatten an array up to a specified depth.
     *
     * @param array $array The multi-dimensional array.
     * @param int $depth The depth limit (default: infinite).
     * @return array The flattened array.
     */
    public static function flattenWithDepth(array $array, int $depth = INF): array {
        $result = [];

        foreach ($array as $value) {
            if (is_array($value) && $depth > 1) {
                $result = array_merge($result, static::flattenWithDepth($value, $depth - 1));
            } else {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * Convert a multi-dimensional array into dot notation keys.
     *
     * @param array $array The multi-dimensional array.
     * @param string $prefix The prefix for keys.
     * @return array The array with flattened keys.
     */
    public static function flattenKeys(array $array, string $prefix = ''): array
    {
        $results = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                $results += static::flattenKeys($value, $newKey);
            } else {
                $results[$newKey] = $value;
            }
        }

        return $results;
    }

    /**
     * Remove a value from an array using dot notation.
     *
     * @param array $array The source array (passed by reference).
     * @param string|array $keys The key(s) to remove.
     * @return void
     */
    public static function forget(array &$array, string|array $keys): void {
        $keys = (array) $keys;

        foreach ($keys as $key) {
            $parts = explode('.', $key);
            $temp = &$array;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (!isset($temp[$part]) || !is_array($temp[$part])) {
                    continue 2;
                }

                $temp = &$temp[$part];
            }

            unset($temp[array_shift($parts)]);
        }
    }

    /**
     * Get a value from an array using dot notation.
     *
     * @param array $array The source array.
     * @param string $key The key using dot notation.
     * @param mixed|null $default The default value if the key is not found.
     * @return mixed The value from the array or the default.
     */
    public static function get(array $array, string $key, mixed $default = null): mixed {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }
    
    /**
     * Group an array by a given key.
     *
     * @param array $array The array to group.
     * @param string $key The key to group by.
     * @return array The grouped array.
     */
    public static function groupBy(array $array, string $key): array
    {
        $result = [];

        foreach ($array as $item) {
            $groupKey = $item[$key] ?? null;
            if ($groupKey !== null) {
                $result[$groupKey][] = $item;
            }
        }

        return $result;
    }

    /**
     * Check if an array has a given key using dot notation.
     *
     * @param array $array The source array.
     * @param string $key The key using dot notation.
     * @return bool True if the key exists, false otherwise.
     */
    public static function has(array $array, string $key): bool {
        if (array_key_exists($key, $array)) {
            return true;
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return false;
            }
            $array = $array[$segment];
        }

        return true;
    }

    /**
     * Check if all given keys exist in the array.
     *
     * @param array $array The array to check.
     * @param array $keys The keys to search for.
     * @return bool True if all keys exist, otherwise false.
     */
    public static function hasAllKeys(array $array, array $keys): bool {
        return !array_diff_key(array_flip($keys), $array);
    }

    /**
     * Check if at least one key exists in the array.
     *
     * @param array $array The array to check.
     * @param array $keys The keys to search for.
     * @return bool True if any key exists, otherwise false.
     */
    public static function hasAnyKey(array $array, array $keys): bool {
        return (bool) array_intersect_key(array_flip($keys), $array);
    }

    /**
     * Determine if at least one of the given keys exists in the array.
     *
     * @param array $array The array to check.
     * @param array $keys The list of keys to check.
     * @return bool True if any key exists, false otherwise.
     */
    public static function hasAny(array $array, array $keys): bool
    {
        return count(array_intersect_key($array, array_flip($keys))) > 0;
    }

    /**
     * Insert an element before a given key in an array.
     *
     * @param array $array The original array.
     * @param string|int $key The key to insert before.
     * @param string|int $newKey The new key.
     * @param mixed $value The value to insert.
     * @return array The modified array.
     */
    public static function insertBefore(array $array, string|int $key, string|int $newKey, mixed $value): array
    {
        $position = array_search($key, array_keys($array));
        
        if ($position === false) {
            return $array;
        }

        return array_slice($array, 0, $position, true)
            + [$newKey => $value]
            + array_slice($array, $position, null, true);
    }

    /**
     * Insert an element after a given key in an array.
     *
     * @param array $array The original array.
     * @param string|int $key The key to insert after.
     * @param string|int $newKey The new key.
     * @param mixed $value The value to insert.
     * @return array The modified array.
     */
    public static function insertAfter(array $array, string|int $key, string|int $newKey, mixed $value): array
    {
        $position = array_search($key, array_keys($array));

        if ($position === false) {
            return $array;
        }

        return array_slice($array, 0, $position + 1, true)
            + [$newKey => $value]
            + array_slice($array, $position + 1, null, true);
    }

    /**
     * Determine if a given value is an array.
     *
     * @param mixed $value The value to check.
     * @return bool True if the value is an array, false otherwise.
     */
    public static function isArray(mixed $value): bool
    {
        return is_array($value);
    }

    /**
     * Determine if an array is associative (i.e., contains at least one non-numeric key).
     *
     * @param array $array The array to check.
     * @return bool True if associative, false otherwise.
     */
    public static function isAssoc(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * Check if the given array is empty.
     *
     * @param array|null $array The array to check.
     * @return bool True if empty or null, otherwise false.
     */
    public static function isEmpty(?array $array): bool {
        return empty($array);
    }

    /**
     * Check if the given array is not empty.
     *
     * @param array|null $array The array to check.
     * @return bool True if not empty, otherwise false.
     */
    public static function isNotEmpty(?array $array): bool {
        return !self::isEmpty($array);
    }

    /**
     * Get all the keys from an array.
     *
     * @param array $array The array to extract keys from.
     * @return array The array of keys.
     */
    public static function keys(array $array): array
    {
        return array_keys($array);
    }

    /**
     * Reindex an array using a specified key.
     *
     * @param array $array The source array.
     * @param string|int $key The key to index by.
     * @return array The reindexed array.
     */
    public static function keyBy(array $array, string|int $key): array {
        $result = [];

        foreach ($array as $item) {
            if (!is_array($item) || !array_key_exists($key, $item)) {
                throw new \InvalidArgumentException("Each item must be an array and contain the key '$key'.");
            }

            $result[$item[$key]] = $item;
        }

        return $result;
    }


    /**
     * Get the last element that passes a given test.
     *
     * @param array $array The source array.
     * @param callable|null $callback The function to determine a match.
     * @param mixed|null $default The default value if no match is found.
     * @return mixed The last matching value or default.
     */
    public static function last(array $array, ?callable $callback = null, mixed $default = null): mixed {
        return static::first(array_reverse($array, true), $callback, $default);
    }

    /**
     * Apply a callback to each item in an array.
     *
     * @param array $array The source array.
     * @param callable $callback The function to apply.
     * @return array The modified array.
     */
    public static function map(array $array, callable $callback): array {
        return array_map($callback, $array);
    }

    /**
     * Map an array while preserving keys.
     *
     * @param array $array The source array.
     * @param callable $callback The function to apply.
     * @return array The modified array with new keys.
     */
    public static function mapWithKeys(array $array, callable $callback): array {
        $result = [];

        foreach ($array as $item) {
            $mapped = $callback($item);

            if (!is_array($mapped) || count($mapped) !== 1) {
                throw new \InvalidArgumentException("Callback must return an array with a single key-value pair.");
            }

            $result[key($mapped)] = reset($mapped);
        }

        return $result;
    }

    /**
     * Merge one or more arrays together.
     *
     * @param array ...$arrays Arrays to merge.
     * @return array The merged array.
     */
    public static function merge(array ...$arrays): array
    {
        return array_merge(...$arrays);
    }

    /**
     * Get only the specified keys from an array.
     *
     * @param array $array The source array.
     * @param array $keys The keys to retrieve.
     * @return array The filtered array.
     */
    public static function only(array $array, array $keys): array {
        return array_intersect_key($array, array_flip($keys));
    }

    /**
     * Partition an array into two arrays: 
     * One where the callback returns true, the other where it returns false.
     *
     * @param array $array The array to partition.
     * @param callable $callback The callback function.
     * @return array An array with two arrays (true, false).
     */
    public static function partition(array $array, callable $callback): array {
        $matches = [];
        $nonMatches = [];

        foreach ($array as $item) {
            if ($callback($item)) {
                $matches[] = $item;
            } else {
                $nonMatches[] = $item;
            }
        }

        return [$matches, $nonMatches];
    }

    /**
     * Pluck a single key from an array.
     *
     * @param array $array The source array.
     * @param string $value The key to extract values for.
     * @param string|null $key Optional key to use as array index.
     * @return array The plucked values.
     */
    public static function pluck(array $array, string $value, ?string $key = null): array {
        $results = [];

        foreach ($array as $item) {
            $itemValue = static::get($item, $value);

            if ($key !== null) {
                $itemKey = static::get($item, $key);
                $results[$itemKey] = $itemValue;
            } else {
                $results[] = $itemValue;
            }
        }

        return $results;
    }

    /**
     * Prepend a value to an array.
     *
     * @param array $array The array to modify.
     * @param mixed $value The value to prepend.
     * @param string|int|null $key Optional key for the prepended value.
     * @return array The modified array.
     */
    public static function prepend(array $array, mixed $value, string|int|null $key = null): array {
        if ($key !== null) {
            return [$key => $value] + $array;
        }

        array_unshift($array, $value);
        return $array;
    }

    /**
     * Retrieve a value from the array and remove it.
     *
     * @param array $array The source array (passed by reference).
     * @param string $key The key using dot notation.
     * @param mixed|null $default The default value if the key is not found.
     * @return mixed The retrieved value or default.
     */
    public static function pull(array &$array, string $key, mixed $default = null): mixed {
        $value = static::get($array, $key, $default);
        static::forget($array, $key);
        return $value;
    }

    /**
     * Push one or more values onto the end of an array.
     *
     * @param array $array The array to modify.
     * @param mixed ...$values The values to push.
     * @return array The modified array.
     */
    public static function push(array &$array, mixed ...$values): array
    {
        array_push($array, ...$values);
        return $array;
    }

    /**
     * Get a random value or multiple values from an array.
     *
     * @param array $array The source array.
     * @param int|null $number Number of elements to retrieve.
     * @return mixed The random value(s).
     */
    public static function random(array $array, ?int $number = null): mixed {
        $count = count($array);

        if ($number === null) {
            return $array[array_rand($array)];
        }

        if ($number > $count) {
            $number = $count;
        }

        return array_intersect_key($array, array_flip((array) array_rand($array, $number)));
    }

    /**
     * Reject elements that match a given condition.
     *
     * @param array $array The source array.
     * @param callable $callback The function to determine rejection.
     * @return array The modified array.
     */
    public static function reject(array $array, callable $callback): array
    {
        return array_filter($array, fn($value, $key) => !$callback($value, $key), ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Reverse the order of elements in an array.
     *
     * @param array $array The array to reverse.
     * @param bool $preserveKeys Whether to preserve keys in the reversed array.
     * @return array The reversed array.
     */
    public static function reverse(array $array, bool $preserveKeys = false): array
    {
        return array_reverse($array, $preserveKeys);
    }

    /**
     * Rotate an array left or right.
     *
     * @param array $array The array to rotate.
     * @param int $positions Number of positions to rotate (positive for right, negative for left).
     * @return array The rotated array.
     */
    public static function rotate(array $array, int $positions): array {
        if ($positions === 0) return $array;

        $count = count($array);
        $positions = $positions % $count;

        return array_merge(array_slice($array, -$positions), array_slice($array, 0, -$positions));
    }

    /**
     * Search for a value in an array and return the corresponding key.
     *
     * @param array $array The array to search in.
     * @param mixed $value The value to search for.
     * @param bool $strict Whether to perform a strict type comparison.
     * @return string|int|false The key if found, false otherwise.
     */
    public static function search(array $array, mixed $value, bool $strict = false): string|int|false
    {
        return array_search($value, $array, $strict);
    }

    /**
     * Set a value within an array using dot notation.
     *
     * @param array $array The source array (passed by reference).
     * @param string $key The key using dot notation.
     * @param mixed $value The value to set.
     * @return void
     */
    public static function set(array &$array, string $key, mixed $value): void {
        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $segment = array_shift($keys);

            if (!isset($array[$segment]) || !is_array($array[$segment])) {
                $array[$segment] = [];
            }

            $array = &$array[$segment];
        }

        $array[array_shift($keys)] = $value;
    }

    /**
     * Remove and return the first element of an array.
     *
     * @param array &$array The array to shift from (passed by reference).
     * @return mixed|null The removed element or null if the array is empty.
     */
    public static function shift(array &$array): mixed
    {
        return array_shift($array);
    }

    /**
     * Shuffle the array.
     *
     * @param array $array The source array.
     * @param int|null $seed Optional seed for deterministic results.
     * @return array The shuffled array.
     */
    public static function shuffle(array $array, ?int $seed = null): array {
        if ($seed !== null) {
            mt_srand($seed);
        }

        shuffle($array);
        return $array;
    }

    /**
     * Sort an array using a callback function.
     *
     * @param array $array The array to sort.
     * @param callable|null $callback The comparison function.
     * @return array The sorted array.
     */
    public static function sort(array $array, ?callable $callback = null): array
    {
        if ($callback) {
            usort($array, $callback);
        } else {
            sort($array);
        }

        return $array;
    }

    /**
     * Sort an associative array by its keys.
     *
     * @param array $array The array to sort.
     * @param bool $descending Whether to sort in descending order.
     * @return array The sorted array.
     */
    public static function sortAssoc(array $array, bool $descending = false): array
    {
        $descending ? krsort($array) : ksort($array);
        return $array;
    }

    /**
     * Sort an array by a specific key.
     *
     * @param array $array The array to sort.
     * @param string $key The key to sort by.
     * @param bool $descending Whether to sort in descending order.
     * @return array The sorted array.
     */
    public static function sortBy(array $array, string $key, bool $descending = false): array
    {
        usort($array, fn($a, $b) => ($a[$key] <=> $b[$key]) * ($descending ? -1 : 1));
        return $array;
    }

    /**
     * Sort an array by its keys.
     *
     * @param array $array The array to sort.
     * @return array The sorted array.
     */
    public static function sortByKeys(array $array): array {
        ksort($array);
        return $array;
    }

    /**
     * Sort an array by its values.
     *
     * @param array $array The array to sort.
     * @return array The sorted array.
     */
    public static function sortByValues(array $array): array {
        asort($array);
        return $array;
    }

    /**
     * Swap two keys in an array.
     *
     * @param array $array The array to modify.
     * @param string|int $key1 The first key.
     * @param string|int $key2 The second key.
     * @return array The modified array.
     */
    public static function swapKeys(array $array, string|int $key1, string|int $key2): array {
        if (!isset($array[$key1]) || !isset($array[$key2])) {
            return $array; // Keys must exist
        }

        [$array[$key1], $array[$key2]] = [$array[$key2], $array[$key1]];

        return $array;
    }

    /**
     * Convert an array to a JSON string.
     *
     * @param array $array The array to convert.
     * @param int $options JSON encoding options.
     * @return string The JSON string.
     */
    public static function toJson(array $array, int $options = 0): string {
        return json_encode($array, $options);
    }

    /**
     * Convert an array to an object.
     *
     * @param array $array The array to convert.
     * @return object The converted object.
     */
    public static function toObject(array $array): object {
        return json_decode(json_encode($array), false);
    }

    /**
     * Remove duplicate values from an array.
     *
     * @param array $array The source array.
     * @return array The array without duplicate values.
     */
    public static function unique(array $array): array
    {
        return array_unique($array, SORT_REGULAR);
    }

    /**
     * Remove duplicate items from an array based on a key or callback.
     *
     * @param array $array The array to filter.
     * @param string|callable $key The key or function to determine uniqueness.
     * @return array The unique array.
     */
    public static function uniqueBy(array $array, string|callable $key): array {
        $seen = [];
        return array_filter($array, function ($item) use (&$seen, $key) {
            $keyValue = is_callable($key) ? $key($item) : $item[$key] ?? null;
            if ($keyValue === null || in_array($keyValue, $seen, true)) {
                return false;
            }
            $seen[] = $keyValue;
            return true;
        });
    }

    /**
     * Remove multiple keys from an array.
     *
     * @param array $array The array to modify.
     * @param array $keys The keys to remove.
     * @return array The array without the specified keys.
     */
    public static function unsetKeys(array $array, array $keys): array {
        foreach ($keys as $key) {
            unset($array[$key]);
        }
        return $array;
    }

    /**
     * Unwrap an array if it contains only one item.
     *
     * @param array $array The array to unwrap.
     * @return mixed The single value or the original array.
     */
    public static function unwrap(array $array): mixed {
        return count($array) === 1 ? reset($array) : $array;
    }

    /**
     * Return all values from an array, resetting numeric keys.
     *
     * @param array $array The input array.
     * @return array The array with numeric indexes.
     */
    public static function values(array $array): array
    {
        return array_values($array);
    }

    /**
     * Recursively applies a callback function to each element in an array.
     *
     * This function modifies each value in the array using the provided callback.
     * The callback receives both the value and the key of each array element.
     *
     * @param array $array The array to be processed.
     * @param callable $callback The callback function to apply.
     *     The callback should accept two parameters: 
     *     - mixed $value (the array value)
     *     - string|int $key (the array key)
     * 
     * @return array The modified array with the callback applied to each value.
     */
    public static function walkRecursive(array $array, callable $callback): array
    {
        array_walk_recursive($array, function (&$value, $key) use ($callback) {
            $value = $callback($value, $key);
        });

        return $array;
    }

    /**
     * Select a random element based on weighted probabilities.
     *
     * @param array $array The array with weights.
     * @param array $weights The corresponding weights.
     * @return mixed A randomly selected item.
     */
    public static function weightedRandom(array $array, array $weights): mixed {
        $totalWeight = array_sum($weights);
        $rand = mt_rand(1, $totalWeight);
        $cumulative = 0;

        foreach ($array as $key => $value) {
            $cumulative += $weights[$key];
            if ($rand <= $cumulative) {
                return $value;
            }
        }

        return null; // Fallback, should never reach
    }

    /**
     * Wrap a value in an array.
     *
     * @param mixed $value The value to wrap.
     * @return array The wrapped array.
     */
    public static function wrap(mixed $value): array {
        return is_array($value) ? $value : [$value];
    } 

    /**
     * Filter an array using a callback.
     *
     * @param array $array The source array.
     * @param callable $callback The function to apply to each element.
     * @return array The filtered array.
     */
    public static function where(array $array, callable $callback): array {
        return array_filter($array, $callback);
    }

    /**
     * Compute the exclusive difference between two arrays.
     *
     * @param array $array1 The first array.
     * @param array $array2 The second array.
     * @return array The values that exist only in one of the arrays.
     */
    public static function xorDiff(array $array1, array $array2): array {
        return array_merge(array_diff($array1, $array2), array_diff($array2, $array1));
    }
}