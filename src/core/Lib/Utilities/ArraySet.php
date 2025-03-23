<?php

declare(strict_types=1);

namespace Core\Lib\Utilities;

/**
 * Array utility class with functions for both chainable operations.
 */
class ArraySet
{
    protected array $items;
    protected mixed $lastResult;

    /**
     * Constructor to initialize the array.
     *
     * @param array $items The initial array.
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
        $this->lastResult = null;
    }

    /**
     * Add a value to the array if the key does not exist.
     *
     * @param string $key The key to check.
     * @param mixed $value The value to add.
     * @return self
     */
    public function add(string $key, mixed $value): self
    {
        if (!array_key_exists($key, $this->items)) {
            $this->items[$key] = $value;
        }
        return $this;
    }

    /**
     * Get all items in the array.
     *
     * @return array The stored array.
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Sort the array while maintaining index association.
     *
     * This method sorts the array in ascending order while preserving 
     * the original keys.
     *
     * @return self The modified Arr instance.
     */
    public function asort(): self
    {
        asort($this->items);
        return $this;
    }

    /**
     * Sort the array in descending order while maintaining index association.
     *
     * This method sorts the array in descending order while preserving 
     * the original keys.
     *
     * @return self The modified Arr instance.
     */
    public function arsort(): self
    {
        arsort($this->items);
        return $this;
    }

    /**
     * Split an array into chunks.
     *
     * @param int $size The size of each chunk.
     * @return self
     */
    public function chunk(int $size): self
    {
        $this->items = array_chunk($this->items, $size);
        return $this;
    }

    /**
     * Clear all items in the array.
     *
     * @return self
     */
    public function clear(): self
    {
        $this->items = [];
        return $this;
    }

    /**
     * Collapse a multi-dimensional array into a single level.
     *
     * @return self
     */
    public function collapse(): self
    {
        $result = [];
        array_walk_recursive($this->items, function ($a) use (&$result) {
            $result[] = $a;
        });
        $this->items = $result;
        return $this;
    }

    /**
     * Extract a single column from a multi-dimensional array.
     *
     * @param string|int $columnKey The column key.
     * @return self
     */
    public function column(string|int $columnKey): self
    {
        $this->items = array_column($this->items, $columnKey);
        return $this;
    }

    /**
     * Combine two arrays, one as keys and one as values.
     *
     * @param array $keys The keys.
     * @param array $values The values.
     * @return self
     */
    public static function combine(array $keys, array $values): self
    {
        if (count($keys) !== count($values)) {
            throw new \InvalidArgumentException("Keys and values must have the same length.");
        }
        return new self(array_combine($keys, $values));
    }

    /**
     * Check if an array contains a specific value.
     *
     * @param mixed $value The value to search for.
     * @param bool $strict Whether to perform strict comparison.
     * @return self
     */
    public function contains(mixed $value, bool $strict = false): self
    {
        $this->lastResult = in_array($value, $this->items, $strict);
        return $this;
    }

    /**
     * Compute the Cartesian product of multiple arrays.
     *
     * @param array ...$arrays Arrays to join.
     * @return self
     */
    public function crossJoin(array ...$arrays): self
    {
        $results = [[]];
        foreach (array_merge([$this->items], $arrays) as $array) {
            $newResults = [];
            foreach ($results as $result) {
                foreach ($array as $item) {
                    $newResults[] = array_merge($result, [$item]);
                }
            }
            $results = $newResults;
        }
        $this->items = $results;
        return $this;
    }
    
    /**
     * Get the count of elements in the array.
     *
     * @return self
     */
    public function count(): self
    {
        $this->lastResult = count($this->items);
        return $this;
    }

    /**
     * Get the difference between the current array and another array.
     *
     * @param array $array The array to compare.
     * @return self
     */
    public function diff(array $array): self
    {
        $this->items = array_diff($this->items, $array);
        return $this;
    }

    /**
     * Convert an array into dot notation.
     *
     * @param string $prepend A string to prepend before keys.
     * @return self
     */
    public function dot(string $prepend = ''): self
    {
        $results = [];
        $array = $this->items;
        
        $flatten = function (array $array, string $prepend) use (&$results, &$flatten) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $flatten($value, $prepend . $key . '.');
                } else {
                    $results[$prepend . $key] = $value;
                }
            }
        };
        
        $flatten($array, $prepend);
        $this->items = $results;
        return $this;
    }

    /**
     * Iterate over each item and apply a callback.
     *
     * @param callable $callback The function to apply.
     * @return self
     */
    public function each(callable $callback): self
    {
        foreach ($this->items as $key => $value) {
            $callback($value, $key);
        }
        return $this;
    }

    /**
     * Remove specified keys from the array.
     *
     * @param array|string $keys The keys to remove.
     * @return self
     */
    public function except(array|string $keys): self
    {
        foreach ((array) $keys as $key) {
            unset($this->items[$key]);
        }
        return $this;
    }

    /**
     * Determine if a key exists in an array.
     *
     * @param string $key The key to check.
     * @return self
     */
    public function exists(string $key): self
    {
        $this->lastResult = array_key_exists($key, $this->items);
        return $this;
    }

    /**
     * Fill an array with a specified value.
     *
     * @param int $start Index to start filling.
     * @param int $count Number of elements to insert.
     * @param mixed $value The value to insert.
     * @return self
     */
    public function fill(int $start, int $count, mixed $value): self
    {
        $this->items = array_fill($start, $count, $value);
        return $this;
    }

    /**
     * Filters the array using a callback function.
     *
     * @param callable $callback The function to apply for filtering.
     * @return self
     */
    public function filter(callable $callback): self
    {
        $this->items = array_filter($this->items, $callback);
        return $this;
    }

    /**
     * Get the first element that matches a condition.
     *
     * @param callable|null $callback A callback function to test elements.
     * @return self
     */
    public function first(?callable $callback = null): self
    {
        if ($callback === null) {
            $this->lastResult = reset($this->items) ?: null;
        } else {
            foreach ($this->items as $value) {
                if ($callback($value)) {
                    $this->lastResult = $value;
                    break;
                }
            }
        }
        return $this;
    }

    /**
     * Get the first key of the array.
     *
     * @return self
     */
    public function firstKey(): self
    {
        $this->lastResult = array_key_first($this->items);
        return $this;
    }

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @return self
     */
    public function flatten(): self
    {
        $result = [];
        array_walk_recursive($this->items, function ($a) use (&$result) {
            $result[] = $a;
        });
        $this->items = $result;
        return $this;
    }

    /**
     * Swap keys with values in the array.
     *
     * @return self
     */
    public function flip(): self
    {
        $this->items = array_flip($this->items);
        return $this;
    }

    /**
     * Remove a specific key from the array.
     *
     * @param string $key The key to remove.
     * @return self
     */
    public function forget(string $key): self
    {
        unset($this->items[$key]);
        return $this;
    }

    /**
     * Get a value from the array using dot notation.
     *
     * @param string $key The key in dot notation.
     * @param mixed $default The default value if the key is not found.
     * @return self
     */
    public function get(string $key, mixed $default = null): self
    {
        $keys = explode('.', $key);
        $array = $this->items;
        
        foreach ($keys as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                $this->lastResult = $default;
                return $this;
            }
            $array = $array[$segment];
        }
        
        $this->lastResult = $array;
        return $this;
    }
    
    /**
     * Check if a key exists in the array.
     *
     * @param string $key The key to check.
     * @return self
     */
    public function has(string $key): self
    {
        $this->lastResult = array_key_exists($key, $this->items);
        return $this;
    }

    /**
     * Determine if at least one of the given keys exists in the array.
     *
     * @param array|string $keys The keys to check.
     * @return self
     */
    public function hasAny(array|string $keys): self
    {
        $keys = (array) $keys;
        $this->lastResult = count(array_intersect_key($this->items, array_flip($keys))) > 0;
        return $this;
    }

    /**
     * Get the intersection of the current array and another array.
     *
     * @param array $array The array to compare.
     * @return self
     */
    public function intersect(array $array): self
    {
        $this->items = array_intersect($this->items, $array);
        return $this;
    }

    /**
     * Get elements whose keys exist in another array.
     *
     * @param array $array The array to compare.
     * @return self
     */
    public function intersectKeys(array $array): self
    {
        $this->items = array_intersect_key($this->items, $array);
        return $this;
    }

    /**
     * Join array elements with a string.
     *
     * @param string $separator The separator string.
     * @return self
     */
    public function implode(string $separator): self
    {
        $this->lastResult = implode($separator, $this->items);
        return $this;
    }

    /**
     * Check if the given value is an array.
     *
     * @param mixed $value The value to check.
     * @return self
     */
    public function isArray(mixed $value): self
    {
        $this->lastResult = is_array($value);
        return $this;
    }

    /**
     * Check if the array is empty.
     *
     * @return self
     */
    public function isEmpty(): self
    {
        $this->lastResult = empty($this->items);
        return $this;
    }

    /**
     * Get all keys of the array.
     *
     * @return self
     */
    public function keys(): self
    {
        $this->items = array_keys($this->items);
        return $this;
    }

    /**
     * Key an array by a specific field.
     *
     * @param string $key The field to use as keys.
     * @return self
     */
    public function keyBy(string $key): self
    {
        $this->items = array_column($this->items, null, $key);
        return $this;
    }

    /**
     * Sort the array by keys in descending order.
     *
     * @return self
     */
    public function krsort(): self
    {
        krsort($this->items);
        return $this;
    }

    /**
     * Sort the array by keys in ascending order.
     *
     * @return self
     */
    public function ksort(): self
    {
        ksort($this->items);
        return $this;
    }

    /**
     * Get the last element that matches a condition.
     *
     * @param callable|null $callback A callback function to test elements.
     * @return self
     */
    public function last(?callable $callback = null): self
    {
        if ($callback === null) {
            $this->lastResult = end($this->items) ?: null;
        } else {
            $this->lastResult = null;
            foreach (array_reverse($this->items, true) as $value) {
                if ($callback($value)) {
                    $this->lastResult = $value;
                    break;
                }
            }
        }
        return $this;
    }

    /**
     * Get the last key of the array.
     *
     * @return self
     */
    public function lastKey(): self
    {
        $this->lastResult = array_key_last($this->items);
        return $this;
    }

    /**
     * Create a new `Arr` instance from an array or a single value.
     *
     * This function ensures that the provided input is always treated as an array.
     * If a non-array value (e.g., `null`, `string`, `int`) is passed, it wraps
     * it into an array automatically.
     *
     * @param mixed $items The array or single value to wrap.
     * @return self A new `Arr` instance.
     */
    public static function make(mixed $items = []): self
    {
        return new self(match (true) {
            is_null($items) => [],
            is_array($items) => $items,
            default => [$items]
        });
    }

    /**
     * Apply a callback function to each item in the array.
     *
     * @param callable $callback The function to apply.
     * @return self
     */
    public function map(callable $callback): self
    {
        $this->items = array_map($callback, $this->items);
        return $this;
    }

    /**
     * Apply a recursive callback function to each element in the array.
     *
     * @param callable $callback The function to apply.
     * @return self
     */
    public function mapRecursive(callable $callback): self
    {
        $recursiveMap = function ($array) use (&$recursiveMap, $callback) {
            foreach ($array as $key => $value) {
                $array[$key] = is_array($value) ? $recursiveMap($value) : $callback($value);
            }
            return $array;
        };

        $this->items = $recursiveMap($this->items);
        return $this;
    }

    /**
     * Map an array using a callback that defines both keys and values.
     *
     * @param callable $callback The function to apply.
     * @return self
     */
    public function mapWithKeys(callable $callback): self
    {
        $result = [];
        foreach ($this->items as $key => $value) {
            $mapped = $callback($value, $key);
            if (is_array($mapped)) {
                foreach ($mapped as $newKey => $newValue) {
                    $result[$newKey] = $newValue;
                }
            }
        }
        $this->items = $result;
        return $this;
    }

    /**
     * Merge the current array with another array.
     *
     * @param array $array The array to merge with.
     * @return self
     */
    public function merge(array $array): self
    {
        $this->items = array_merge($this->items, $array);
        return $this;
    }

    /**
     * Sort multiple arrays or multi-dimensional arrays.
     *
     * @param int $sortFlags The sorting flags.
     * @return self
     */
    public function multisort(int $sortFlags = SORT_REGULAR): self
    {
        array_multisort($this->items, $sortFlags);
        return $this;
    }

    /**
     * Return only the specified keys from the array.
     *
     * @param array|string $keys The keys to include.
     * @return self
     */
    public function only(array|string $keys): self
    {
        $this->items = array_intersect_key($this->items, array_flip((array) $keys));
        return $this;
    }

    /**
     * Pad an array to the specified length with a value.
     *
     * @param int $size The required size of the array.
     * @param mixed $value The value to pad with.
     * @return self
     */
    public function pad(int $size, mixed $value): self
    {
        $this->items = array_pad($this->items, $size, $value);
        return $this;
    }

    /**
     * Extract a specific key's values.
     *
     * @param string $key The key to pluck.
     * @return self
     */
    public function pluck(string $key): self
    {
        $this->items = array_column($this->items, $key);
        return $this;
    }

    /**
     * Prepend a value to the beginning of the array.
     *
     * @param mixed $value The value to prepend.
     * @return self
     */
    public function prepend(mixed $value): self
    {
        array_unshift($this->items, $value);
        return $this;
    }

    /**
     * Remove and return a value from the array.
     *
     * @param string $key The key to retrieve and remove.
     * @param mixed $default The default value if the key is not found.
     * @return self
     */
    public function pull(string $key, mixed $default = null): self
    {
        $this->lastResult = $this->items[$key] ?? $default;
        unset($this->items[$key]);
        return $this;
    }

    /**
     * Add one or more values to the array.
     *
     * @param mixed ...$values The values to add.
     * @return self
     */
    public function push(mixed ...$values): self
    {
        array_push($this->items, ...$values);
        return $this;
    }

    /**
     * Get a random value or multiple values from the array.
     *
     * @param int|null $number Number of elements to retrieve.
     * @return self
     */
    public function random(?int $number = null): self
    {
        $this->lastResult = is_null($number)
            ? $this->items[array_rand($this->items)]
            : array_intersect_key($this->items, array_flip((array) array_rand($this->items, $number)));
        return $this;
    }

    /**
     * Reduce the array to a single value using a callback.
     *
     * @param callable $callback The callback function.
     * @param mixed $initial The initial value.
     * @return self
     */
    public function reduce(callable $callback, mixed $initial = null): self
    {
        $this->lastResult = array_reduce($this->items, $callback, $initial);
        return $this;
    }

    /**
     * Replace values in the array with values from another array.
     *
     * @param array $array The array with replacement values.
     * @return self
     */
    public function replace(array $array): self
    {
        $this->items = array_replace($this->items, $array);
        return $this;
    }

    /**
     * Retrieve the result of the last operation.
     *
     * @return mixed
     */
    public function result(): mixed
    {
        return $this->lastResult ?? null;
    }

    /**
     * Reverse the order of the array.
     *
     * @return self
     */
    public function reverse(): self
    {
        $this->items = array_reverse($this->items);
        return $this;
    }

    /**
     * Sort the array in descending order.
     *
     * @return self
     */
    public function rsort(): self
    {
        rsort($this->items);
        return $this;
    }

    /**
     * Search for a value in the array and return its key.
     *
     * @param mixed $value The value to search for.
     * @return self
     */
    public function search(mixed $value): self
    {
        $this->lastResult = array_search($value, $this->items, true);
        return $this;
    }

    /**
     * Set a value in the array using dot notation.
     *
     * @param string $key The key in dot notation.
     * @param mixed $value The value to set.
     * @return self
     */
    public function set(string $key, mixed $value): self
    {
        $array = &$this->items;
        $keys = explode('.', $key);
        
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
        return $this;
    }

    /**
     * Remove and return the first item from the array.
     *
     * @return self
     */
    public function shift(): self
    {
        $this->lastResult = array_shift($this->items);
        return $this;
    }

    /**
     * Shuffle the elements of the array.
     *
     * @return self
     */
    public function shuffle(): self
    {
        shuffle($this->items);
        return $this;
    }

    /**
     * Shuffle an array while preserving keys.
     *
     * @return self
     */
    public function shuffleAssociative(): self
    {
        $keys = array_keys($this->items);
        shuffle($keys);
        $this->items = array_merge(array_flip($keys), $this->items);
        return $this;
    }

    /**
     * Extract a slice of the array.
     *
     * @param int $offset The index to start the slice.
     * @param int|null $length The number of elements to extract.
     * @return self
     */
    public function slice(int $offset, ?int $length = null): self
    {
        $this->items = array_slice($this->items, $offset, $length);
        return $this;
    }

    /**
     * Sort the array in ascending order.
     *
     * @param int $sortFlags Flags for sorting (default: SORT_REGULAR).
     * @return self
     */
    public function sort(int $sortFlags = SORT_REGULAR): self
    {
        sort($this->items, $sortFlags);
        return $this;
    }

    /**
     * Remove and replace a portion of the array.
     *
     * @param int $offset The index to start the splice.
     * @param int|null $length The number of elements to remove.
     * @param array $replacement The replacement values.
     * @return self
     */
    public function splice(int $offset, ?int $length = null, array $replacement = []): self
    {
        array_splice($this->items, $offset, $length, $replacement);
        return $this;
    }

    /**
     * Compute the difference between arrays using a custom comparison function.
     *
     * @param array $array The array to compare.
     * @param callable $callback The comparison function.
     * @return self
     */
    public function udiff(array $array, callable $callback): self
    {
        $this->items = array_udiff($this->items, $array, $callback);
        return $this;
    }

    /**
     * Remove duplicate values from the array.
     *
     * @return self
     */
    public function unique(): self
    {
        $this->items = array_unique($this->items);
        return $this;
    }

    /**
     * Sort the array using a user-defined comparison function.
     *
     * @param callable $callback The comparison function.
     * @return self
     */
    public function usort(callable $callback): self
    {
        usort($this->items, $callback);
        return $this;
    }

    /**
     * Get all values of the array.
     *
     * @return self
     */
    public function values(): self
    {
        $this->items = array_values($this->items);
        return $this;
    }

    /**
     * Apply a user function to every item in the array.
     *
     * @param callable $callback The function to apply.
     * @return self
     */
    public function walk(callable $callback): self
    {
        array_walk($this->items, $callback);
        return $this;
    }

    /**
     * Recursively apply a user function to every item in the array.
     *
     * @param callable $callback The function to apply.
     * @return self
     */
    public function walkRecursive(callable $callback): self
    {
        array_walk_recursive($this->items, $callback);
        return $this;
    }

    /**
     * Wrap a value in an array if it is not already an array.
     *
     * @param mixed $value The value to wrap.
     * @return self
     */
    public function wrap(mixed $value): self
    {
        $this->items = match (true) {
            is_array($value) => $value,
            is_null($value) => [],
            default => [$value]
        };
        return $this;
    }

    /**
     * Filter the array using a callback function.
     *
     * @param callable $callback The function to apply.
     * @return self
     */
    public function where(callable $callback): self
    {
        $this->items = array_filter($this->items, $callback);
        return $this;
    }
}
