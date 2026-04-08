<h1 style="font-size: 50px; text-align: center;">Arr</h1>

## Table of Contents
1. [Overview](#overview)  
2. [Basic Usage](#usage)  
3. [Methods](#methods)  
    * A. [add](#add)  
    * B. [arrayDivide](#arraydivide)  
    * C. [arrayPluckMulti](#arraypluckmulti)  
    * D. [arrayShuffleAssoc](#arrayshuffleassoc)  
    * E. [chunk](#chunk)  
    * F. [chunkBy](#chunkby)  
    * G. [collapse](#collapse)  
    * H. [contains](#contains)  
    * I. [crossJoin](#crossjoin)  
    * J. [deepMerge](#deepmerge)  
    * K. [diffAssocRecursive](#diffassocrecursive)  
    * L. [dot](#dot)  
4. [Retrieving Data](#retrieving-data)  
    * A. [except](#except)  
    * B. [exists](#exists)  
    * C. [first](#first)  
    * D. [get](#get)  
    * E. [groupBy](#groupby)  
    * F. [has](#has)  
    * G. [hasAllKeys](#hasallkeys)  
    * H. [hasAnyKey](#hasanykey)  
    * I. [keys](#keys)  
    * J. [last](#last)  
    * K. [only](#only)  
    * L. [pluck](#pluck)  
    * M. [values](#values)  
5. [Iteration, Sorting, & Transformation](#sorting-ordering)  
    * A. [flatten](#flatten)  
    * B. [flattenWithDepth](#flattenwithdepth)  
    * C. [flattenKeys](#flattenkeys)  
    * D. [map](#map)  
    * E. [mapWithKeys](#mapwithkeys)  
    * F. [reverse](#reverse)  
    * G. [shuffle](#shuffle)  
    * H. [sort](#sort)  
    * I. [sortAssoc](#sortassoc)  
    * J. [sortBy](#sortby)  
    * K. [sortByKeys](#sortbykeys)  
    * L. [sortByValues](#sortbyvalues)  
6. [Manipulation](#manipulation)  
    * A. [fill](#fill)  
    * B. [forget](#forget)  
    * C. [insertAfter](#insertafter)  
    * D. [insertBefore](#insertbefore)  
    * E. [merge](#merge)  
    * F. [prepend](#prepend)  
    * G. [pull](#pull)  
    * H. [push](#push)  
    * I. [set](#set)  
    * J. [shift](#shift)  
    * K. [swapKeys](#swapkeys)  
    * L. [unsetKeys](#unsetkeys)  
7. [Comparison, Filtering, & Mapping](#comparison-filtering-mapping)  
    * A. [contains](#contains)  
    * B. [filter](#filter)  
    * C. [filterByKeys](#filterbykeys)  
    * D. [filterByValue](#filterbyvalue)  
    * E. [partition](#partition)  
    * F. [reject](#reject)  
    * G. [unique](#unique)  
    * H. [uniqueBy](#uniqueby)  
    * I. [where](#where)  
8. [Chunking and Collapsing](#chunking-collapsing)  
    * A. [chunk](#chunk)  
    * B. [chunkBy](#chunkby)  
    * C. [collapse](#collapse)  
9. [Other Utilities](#other-utilities)  
    * A. [isArray](#isarray)  
    * B. [isAssoc](#isassoc)  
    * C. [isEmpty](#isempty)  
    * D. [isNotEmpty](#isnotempty)  
    * E. [random](#random)  
    * F. [toJson](#tojson)  
    * G. [toObject](#toobject)  
    * H. [unwrap](#unwrap)  
    * I. [walkRecursive](#walkrecursive)  
    * J. [weightedRandom](#weightedrandom)  
    * K. [wrap](#wrap)  
    * L. [xorDiff](#xordiff)  

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
A collection of functions for manipulating arrays that are **static and utility-based**.

```php
use Core\Lib\Utilities\Arr;
```
<br>

## 2. Basic Usage <a id="usage"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
```php
$data = ['name' => 'John', 'age' => 30];

// Get a value using dot notation
echo Arr::get($data, 'name'); // John

// Sort values
$sorted = Arr::sort([5, 3, 8, 1]); 
print_r($sorted); // [1, 3, 5, 8]

// Filter
$filtered = Arr::filter([1, 2, 3, 4], fn($n) => $n > 2);
print_r($filtered); // [3, 4]

// Remove a key
$filtered = Arr::except(['name' => 'John', 'age' => 30], ['age']);
print_r($filtered); // ['name' => 'John']
```
<br>

## 3. Methods <a id="methods"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `add()` <a id="add"></a>
Adds a value to an array if the key does not exist.

Parameter:
- `array $array` - The array to modify.
- `string|int $key` - The key to check.
- `mixed $value` - The value to add.

Returns:
- `array` - The modified array.

Example:
```php
$array = ['name' => 'Alice'];
$array = Arr::add($array, 'age', 25);
print_r($array);
// ['name' => 'Alice', 'age' => 25]
```
<br>

### B. `arrayDivide()` <a id="arraydivide"></a>
Splits an array into two arrays: one with keys and one with values.

Parameter:
- `array $array` - The array to divide.

Returns:
- `array` - An array containing two arrays: [keys, values].

Example:
```php
$array = ['name' => 'Alice', 'age' => 25];
list($keys, $values) = Arr::arrayDivide($array);
print_r($keys);
// ['name', 'age']
print_r($values);
// ['Alice', 25]
```
<br>

### C. `arrayPluckMulti()` <a id="arraypluckmulti"></a>
Plucks nested values from an array.

Parameters:
- `array $array` - The source array.
- `array|string $keys` - The nested keys to extract.

Returns:
- `array` - The plucked values.

Example:

```php
$array = [
    ['name' => 'Alice', 'details' => ['age' => 25]],
    ['name' => 'Bob', 'details' => ['age' => 30]]
];
$result = Arr::arrayPluckMulti($array, 'details.age');
print_r($result);
// [25, 30]
```
<br>

### D. `arrayShuffleAssoc()` <a id="arrayshuffleassoc"></a>
Shuffles an associative array while preserving keys.

Parameter:
- `array $array` - The array to shuffle.

Returns:
- `array` - The shuffled array.

Example:
```php
$array = ['a' => 1, 'b' => 2, 'c' => 3];
$shuffled = Arr::arrayShuffleAssoc($array);
print_r($shuffled);
// Example output: ['b' => 2, 'c' => 3, 'a' => 1]
```
<br>

### E. `chunk()` <a id="chunk"></a>
Splits an array into chunks of a given size.

Parameters:
- `array $array` - The array to split.
- `int $size` - The size of each chunk.
- `bool $preserveKeys` - Whether to preserve keys.

Returns:
- `array` - An array of chunked arrays.

Example:

```php
$array = [1, 2, 3, 4, 5];
$chunks = Arr::chunk($array, 2);
print_r($chunks);
// [[1, 2], [3, 4], [5]]
```
<br>

### F. `chunkBy()` <a id="chunkby"></a>
Chunks an array into groups based on a callback function.

Parameters:
- `array $array` - The array to chunk.
- `callable $callback` - The function to determine chunks.

Returns:
- `array` - The chunked array.

Example:
```php
$array = [1, 2, 2, 3, 3, 3, 4];
$chunks = Arr::chunkBy($array, fn($a, $b) => $a === $b);
print_r($chunks);
// [[1], [2, 2], [3, 3, 3], [4]]
```
<br>

### G. `collapse()` <a id="collapse"></a>
Collapses a multi-dimensional array into a single-level array.

Parameter:
- `array $array` - The multi-dimensional array.

Returns:
- `array` - The collapsed array.

Example:
```php
$array = [[1, 2], [3, 4], [5]];
$flattened = Arr::collapse($array);
print_r($flattened);
// [1, 2, 3, 4, 5]
```
<br>

### H. `contains()` <a id="contains"></a>
Determines if a given value exists in an array.

Parameters:
- `array $array` - The array to search.
- `mixed $value` - The value to find.
- `bool $strict` - Whether to perform a strict comparison.

Returns:
- `bool` - True if the value exists, false otherwise.

Example:
```php
$array = [1, 2, 3, 'a' => 'apple'];
$result = Arr::contains($array, 'apple');
var_dump($result);
// true
```
<br>

### I. `crossJoin()` <a id="crossjoin"></a>
Computes the Cartesian product of multiple arrays.

Parameter:
- `array ...$arrays` - The arrays to compute the product for.

Returns:
- `array` - The Cartesian product.

Example:
```php
$array1 = [1, 2];
$array2 = ['a', 'b'];
$result = Arr::crossJoin($array1, $array2);
print_r($result);
// [[1, 'a'], [1, 'b'], [2, 'a'], [2, 'b']]
```
<br>

### J. `deepMerge()` <a id="deepmerge"></a>
Recursively merges two or more arrays.

Parameter:
- `array ...$arrays` - The arrays to merge.

Returns:
- `array` - The merged array.

Example:
```php
$array1 = ['name' => 'Alice', 'details' => ['age' => 25]];
$array2 = ['details' => ['city' => 'New York']];
$result = Arr::deepMerge($array1, $array2);
print_r($result);
// ['name' => 'Alice', 'details' => ['age' => 25, 'city' => 'New York']]
```
<br>

### K. `diffAssocRecursive()` <a id="diffassocrecursive"></a>
Recursively computes the difference between two arrays with keys.

Parameters:
- `array $array1` - The first array.
- `array $array2` - The second array.

Returns:
- `array` - The difference.

Example:
```php
$array1 = ['a' => 1, 'b' => ['x' => 10, 'y' => 20]];
$array2 = ['a' => 1, 'b' => ['x' => 10]];
$result = Arr::diffAssocRecursive($array1, $array2);
print_r($result);
// ['b' => ['y' => 20]]
```
<br>

### L. `dot()` <a id="dot"></a>
Converts a multi-dimensional array into dot notation keys.

Parameters:
- `array $array` - The multi-dimensional array.
- `string $prepend` - The prefix for keys.

Returns:
- `array` - The array with dot notation keys.

Example:
```php
$array = ['name' => 'Alice', 'details' => ['age' => 25, 'city' => 'New York']];
$result = Arr::dot($array);
print_r($result);
// ['name' => 'Alice', 'details.age' => 25, 'details.city' => 'New York']
```
<br>

## 4. Retrieving Data <a id="retrieving-data"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `except()` <a id="except"></a>
Gets all items except the specified keys.

Parameters:
- `array $array` - The source array.
- `array $keys` - The keys to exclude.

Returns:
- `array` - The filtered array.

Example:
```php
$array = ['name' => 'Alice', 'age' => 25, 'city' => 'New York'];
$result = Arr::except($array, ['age']);
print_r($result);
// ['name' => 'Alice', 'city' => 'New York']
```
<br>

### B. `exists()` <a id="exists"></a>
Checks if a key exists in an array (non-dot notation).

Parameters:
- `array $array` - The source array.
- `string|int $key` - The key to check.

Returns:
- `bool` - True if the key exists, false otherwise.

Example:
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::exists($array, 'age');
var_dump($result);
// true
```
<br>

### C. `first()` <a id="first"></a>
Gets the first element that passes a given test.

Parameters:
- `array $array` - The source array.
- `callable|null $callback` - The function to determine a match.
- `mixed|null $default` - The default value if no match is found.

Returns:
- `mixed` - The first matching value or default.

Example:
```php
$array = [2, 4, 6, 8];
$result = Arr::first($array, fn($value) => $value > 4);
print_r($result);
// 6
```
<br>

### D. `get()` <a id="get"></a>
Gets a value from an array using dot notation.

Parameters:
- `array $array` - The source array.
- `string $key` - The key using dot notation.
- `mixed|null $default` - The default value if the key is not found.

Returns:
- `mixed` - The value from the array or the default.

Example:
```php
$array = ['name' => 'Alice', 'details' => ['age' => 25]];
$result = Arr::get($array, 'details.age');
print_r($result);
// 25
```
<br>

### E. `groupBy()` <a id="groupby"></a>
Groups an array by a given key.

Parameters:
- `array $array` - The array to group.
- `string $key` - The key to group by.

Returns:
- `array` - The grouped array.

Example:
```php
$array = [
    ['id' => 1, 'category' => 'A'],
    ['id' => 2, 'category' => 'B'],
    ['id' => 3, 'category' => 'A']
];
$result = Arr::groupBy($array, 'category');
print_r($result);
// ['A' => [['id' => 1], ['id' => 3]], 'B' => [['id' => 2]]]
```
<br>

### F. `has()` <a id="has"></a>
Checks if an array has a given key using dot notation.

Parameters:
- `array $array` - The source array.
- `string $key` - The key using dot notation.

Returns:
- `bool` - True if the key exists, false otherwise.

Example:
```php
$array = ['name' => 'Alice', 'details' => ['age' => 25]];
$result = Arr::has($array, 'details.age');
var_dump($result);
// true
```
<br>

### G. `hasAllKeys()` <a id="hasallkeys"></a>
Checks if all given keys exist in the array.

Parameters:
- `array $array` - The array to check.
- `array $keys` - The keys to search for.

Returns:
- `bool` - True if all keys exist, otherwise false.

Example:
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::hasAllKeys($array, ['name', 'age']);
var_dump($result);
// true
```
<br>

### H. `hasAnyKey()` <a id="hasanykey"></a>
Checks if at least one key exists in the array.

Parameters:
- `array $array` - The array to check.
- `array $keys` - The keys to search for.

Returns:
- `bool` - True if all keys exist, otherwise false.

Example:
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::hasAnyKey($array, ['age', 'gender']);
var_dump($result);
// true
```
<br>

### I. `keys()` <a id="keys"></a>
Gets all the keys from an array.

Parameter:
- `array $array` - The array to extract keys from.

Returns:
- `array` - The array of keys.

Example:
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::keys($array);
print_r($result);
// ['name', 'age']
```
<br>

### J. `last()` <a id="last"></a>
Gets the last element that passes a given test.

Parameters:
- `array $array` - The source array.
- `callable|null $callback` - The function to determine a match.
- `mixed|null $default` - The default value if no match is found.

Returns:
- `mixed` - The last matching value or default.

Example:
```php
$array = [2, 4, 6, 8];
$result = Arr::last($array, fn($value) => $value < 7);
print_r($result);
// 6
```
<br>

### K. `only()` <a id="only"></a>
Gets only the specified keys from an array.

Parameters:
- `array $array` - The source array.
- `array $keys` - The keys to retrieve.

Returns:
- `array` - The filtered array.

Example:
```php
$array = ['name' => 'Alice', 'age' => 25, 'city' => 'New York'];
$result = Arr::only($array, ['name', 'city']);
print_r($result);
// ['name' => 'Alice', 'city' => 'New York']
```
<br>

### L. `pluck()` <a id="pluck"></a>
Plucks a single key from an array.

Parameters:
- `array $array` - The source array.
- `string $value` - The key to extract values for.
- `string|null` - $key Optional key to use as array index.

Returns:
- `array` - The plucked values.

Example:
```php
$array = [
    ['id' => 1, 'name' => 'Alice'],
    ['id' => 2, 'name' => 'Bob']
];
$result = Arr::pluck($array, 'name');
print_r($result);
// ['Alice', 'Bob']
```
<br>

### M. `values()` <a id="values"></a>
Gets all values from an array, resetting numeric keys.

Parameter:
- `array $array` - The input array.

Returns:
- `array` - The array with numeric indexes.

Example:
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::values($array);
print_r($result);
// ['Alice', 25]
```
<br>

## 5. Iteration, Sorting, & Transformation <a id="sorting-ordering"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `flatten()` <a id="flatten"></a>
Flattens a multi-dimensional array into a single level.

Parameters:
- `array $array` - The multi-dimensional array.
- `int $depth` - The depth limit.

Returns:
- `array` - The flattened array.

Example:
```php
$array = [[1, 2], [3, [4, 5]]];
$result = Arr::flatten($array);
print_r($result);
// [1, 2, 3, 4, 5]
```
<br>

### B. `flattenWithDepth()` <a id="flattenwithdepth"></a>
Flattens an array up to a specified depth.

Parameters:
- `array $array` - The multi-dimensional array.
- `int $depth` - The depth limit (default: infinite).

Returns:
- `array` - The flattened array.

Example:
```php
$array = [[1, 2], [3, [4, 5]]];
$result = Arr::flattenWithDepth($array, 1);
print_r($result);
// [1, 2, 3, [4, 5]]
```
<br>

### C. `flattenKeys()` <a id="flattenkeys"></a>
Converts a multi-dimensional array into dot notation keys.

Parameters:
- `array $array` - The multi-dimensional array.
- `string $prefix` - The prefix for keys.

Returns:
- `array` - The array with flattened keys.

Example:
```php
$array = ['name' => 'Alice', 'details' => ['age' => 25, 'city' => 'New York']];
$result = Arr::flattenKeys($array);
print_r($result);
// ['name' => 'Alice', 'details.age' => 25, 'details.city' => 'New York']
```
<br>

### D. `map()` <a id="map"></a>
Applies a callback to each item in an array.

Parameters:
- `array $array` - The source array.
- `callable $callback` - The function to apply.

Returns:
- `array` - The modified array.

Example:
```php
$array = [1, 2, 3];
$result = Arr::map($array, fn($value) => $value * 2);
print_r($result);
// [2, 4, 6]
```
<br>

### E. `mapWithKeys()` <a id="mapwithkeys"></a>
Maps an array while preserving keys.

Parameters:
- `array $array` - The source array.
- `callable $callback` - The function to apply.

Returns:
- `array` - The modified array with new keys.

Example:
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::mapWithKeys($array, fn($value, $key) => [$key => strtoupper($value)]);
print_r($result);
// ['name' => 'ALICE', 'age' => '25']
```
<br>

### F. `reverse()` <a id="reverse"></a>
Reverses the order of elements in an array.

Parameters:
- `array $array` - The array to reverse.
- `bool $preserveKeys` - Whether to preserve keys in the reversed array.

Returns:
- `array` - The reversed array.

Example:
```php
$array = [1, 2, 3, 4];
$result = Arr::reverse($array);
print_r($result);
// [4, 3, 2, 1]
```
<br>

### G. `shuffle()` <a id="shuffle"></a>
Shuffles the array.

Parameters:
- `array $array` - The source array.
- `int|null $seed` - Optional seed for deterministic results.

Returns:
- `array` - The shuffled array.

Example:
```php
$array = [1, 2, 3, 4, 5];
$result = Arr::shuffle($array);
print_r($result);
// Example output: [3, 1, 4, 5, 2]
```
<br>

### H. `sort()` <a id="sort"></a>
Sorts an array using a callback function.

Parameters:
- `array $array` - The array to sort.
- `callable\|null $callback` - The comparison function.

Returns:
- `array` - The sorted array.

Example:
```php
$array = [3, 1, 4, 1, 5, 9];
$result = Arr::sort($array);
print_r($result);
// [1, 1, 3, 4, 5, 9]
```
<br>

### I. `sortAssoc()` <a id="sortassoc"></a>
Sorts an associative array by its keys.

Parameters:
- `array $array` - The array to sort.
- `bool $descending` - Whether to sort in descending order.

Returns:
- `array` - The sorted array.

Example:
```php
$array = ['b' => 2, 'a' => 1, 'c' => 3];
$result = Arr::sortAssoc($array);
print_r($result);
// ['a' => 1, 'b' => 2, 'c' => 3]
```
<br>

### J. `sortBy()` <a id="sortby"></a>
Sorts an array by a specific key.

Parameters:
- `array $array` - The array to sort.
- `string $key` - The key to sort by.
- `bool $descending` - Whether to sort in descending order.

Returns:
- `array` - The sorted array.

Example:
```php
$array = [
    ['name' => 'Alice', 'age' => 25],
    ['name' => 'Bob', 'age' => 20]
];
$result = Arr::sortBy($array, 'age');
print_r($result);
// [['name' => 'Bob', 'age' => 20], ['name' => 'Alice', 'age' => 25]]
```
<br>

### K. `sortByKeys()` <a id="sortbykeys"></a>
Sorts an array by its keys.

Parameter:
- `array $array` - The array to sort.

Returns:
- `array` - The sorted array.

Example:
```php
$array = ['b' => 2, 'a' => 1, 'c' => 3];
$result = Arr::sortByKeys($array);
print_r($result);
// ['a' => 1, 'b' => 2, 'c' => 3]
```
<br>

### L. `sortByValues()` <a id="sortbyvalues"></a>
Sorts an array by its values.

Parameter:
- `array $array` - The array to sort.

Returns:
- `array` - The sorted array.

Example:
```php
$array = ['b' => 3, 'a' => 1, 'c' => 2];
$result = Arr::sortByValues($array);
print_r($result);
// ['a' => 1, 'c' => 2, 'b' => 3]
```
<br>

## 6. Manipulation <a id="manipulation"></a>
### A. `fill()` <a id="fill"></a>
Fills an array with a specified value.

Parameters:
- `int $startIndex` -  The first index to use.
- `int $count` - The number of elements to insert.
- `mixed $value` - The value to use for filling.

Returns:
- `array` - The filled array.

Example:
```php
$result = Arr::fill(0, 3, 'a');
print_r($result);
// ['a', 'a', 'a']
```
<br>

### B. `forget()` <a id="forget"></a>
Removes a value from an array using dot notation.

Parameters:
- `array $array` - The source array (passed by reference).
- `string|array $keys` - The key(s) to remove.

Example:
```php
$array = ['name' => 'Alice', 'details' => ['age' => 25]];
Arr::forget($array, 'details.age');
print_r($array);
// ['name' => 'Alice', 'details' => []]
```
<br>

### C. `insertAfter()` <a id="insertafter"></a>
Inserts an element after a given key in an array.

Parameters:
- `array $array` - The original array.
- `string|int $key` - The key to insert after.
- `string|int $newKey` - The new key.
- `mixed $value` - The value to insert.

Returns:
- `array` - The modified array.

Example:
```php
$array = ['a' => 1, 'b' => 2];
$result = Arr::insertAfter($array, 'a', 'x', 99);
print_r($result);
// ['a' => 1, 'x' => 99, 'b' => 2]
```
<br>

### D. `insertBefore()` <a id="insertbefore"></a>
Inserts an element before a given key in an array.

Parameters:
- `array $array` - The original array.
- `string|int $key` - The key to insert before.
- `string|int $newKey` - The new key.
- `mixed $value` - The value to insert.

Returns:
- `array` - The modified array.

Example:
```php
$array = ['a' => 1, 'b' => 2];
$result = Arr::insertBefore($array, 'b', 'x', 99);
print_r($result);
// ['a' => 1, 'x' => 99, 'b' => 2]
```
<br>

### E. `merge()` <a id="merge"></a>
Merges one or more arrays together.

Parameter:
- `array ...$arrays` - Arrays to merge.

Returns:
- `array` - The merged array.

Example:
```php
$array1 = ['name' => 'Alice'];
$array2 = ['age' => 25];
$result = Arr::merge($array1, $array2);
print_r($result);
// ['name' => 'Alice', 'age' => 25]
```
<br>

### F. `prepend()` <a id="prepend"></a>
Prepends a value to an array.

Parameters:
- `array $array` - The array to modify.
- `mixed $value` - The value to prepend.
- `string|int|null $key` - Optional key for the prepended value.

Returns:
- `array` - The modified array.

Example:
```php
$array = [2, 3, 4];
$result = Arr::prepend($array, 1);
print_r($result);
// [1, 2, 3, 4]
```
<br>

### G. `pull()` <a id="pull"></a>
Retrieves a value from the array and removes it.

Parameters:
- `array $array` - The source array (passed by reference).
- `string $key` - The key using dot notation.
- `mixed|null $default` - The default value if the key is not found.

Returns:
- `mixed` - The retrieved value or default.

Example:
```php
$array = ['name' => 'Alice', 'age' => 25];
$age = Arr::pull($array, 'age');
print_r($age);
// 25
print_r($array);
// ['name' => 'Alice']
```
<br>

### H. `push()` <a id="push"></a>
Pushes one or more values onto the end of an array.

Parameters:
- `array $array` - The array to modify.
- `mixed ...$values` - The values to push.

Returns:
- `array` - The modified array.

Example:
```php
$array = [1, 2, 3];
Arr::push($array, 4, 5);
print_r($array);
// [1, 2, 3, 4, 5]
```
<br>

### I. `set()` <a id="set"></a>
Sets a value within an array using dot notation.

Parameters:
- `array $array` - The source array (passed by reference).
- `string $key` - The key using dot notation.
- `mixed $value` - The value to set.

Example:
```php
$array = ['name' => 'Alice'];
Arr::set($array, 'details.age', 25);
print_r($array);
// ['name' => 'Alice', 'details' => ['age' => 25]]
```
<br>

### J. `shift()` <a id="shift"></a>
Removes and returns the first element of an array.

Parameters:
- `array &$array` - The array to shift from (passed by reference).

Returns:
- `mixed|null` - The removed element or null if the array is empty.

Example:
```php
$array = [1, 2, 3];
$first = Arr::shift($array);
print_r($first);
// 1
print_r($array);
// [2, 3]
```
<br>

### K. `swapKeys()` <a id="swapkeys"></a>
Swaps two keys in an array.

Parameters:
- `array $array` - The array to modify.
- `string|int $key1` - The first key.
- `string|int $key2` - The second key.

Returns:
- `array` - The modified array.

Example:
```php
$array = ['a' => 1, 'b' => 2];
$result = Arr::swapKeys($array, 'a', 'b');
print_r($result);
// ['a' => 2, 'b' => 1]
```
<br>

### L. `unsetKeys()` <a id="unsetkeys"></a>
Removes multiple keys from an array.

Parameters:
- `array $array` - The array to modify.
- `array $keys` - The keys to remove.

Returns:
- `array` - The array without the specified keys.

Example:
```php
$array = ['name' => 'Alice', 'age' => 25, 'city' => 'New York'];
$result = Arr::unsetKeys($array, ['age', 'city']);
print_r($result);
// ['name' => 'Alice']
```
<br>

## 7. Comparison, Filtering, & Mapping <a id="comparison-filtering-mapping"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `contains()` <a id="contains"></a>
Determines if a given value exists in an array.

Parameters:
- `array $array` - The array to search.
- `mixed $value` - The value to find.
- `bool $strict` - Whether to perform a strict comparison.

Returns:
- `bool` - True if the value exists, false otherwise.

Example:
```php
$array = [1, 2, 3, 'a' => 'apple'];
$result = Arr::contains($array, 'apple');
var_dump($result);
// true
```
<br>

### B. `filter()` <a id="filter"></a>
Filters an array using a callback function.

Parameters:
- `array $array` - The source array.
- `callable $callback` - The filtering function.

Returns:
- `array` - The filtered array.

Example:
```php
$array = [1, 2, 3, 4, 5];
$result = Arr::filter($array, fn($value) => $value > 2);
print_r($result);
// [3, 4, 5]
```
<br>

### C. `filterByKeys()` <a id="filterbykeys"></a>
Filters an array to include only the specified keys.

Parameters:
- `array $array` - The source array.
- `array $keys` - The keys to keep.

Returns:
- `array` - The filtered array.

Example:
```php
$array = ['name' => 'Alice', 'age' => 25, 'city' => 'New York'];
$result = Arr::filterByKeys($array, ['name', 'city']);
print_r($result);
// ['name' => 'Alice', 'city' => 'New York']
```
<br>

### D. `filterByValue()` <a id="filterbyvalue"></a>
Filters an array by its values.

Parameters:
- `array $array` - The array to filter.
- `callable $callback` - The function to apply for filtering.

Returns:
- `array` - The filtered array.

Example:
```php
$array = ['a' => 1, 'b' => 2, 'c' => 3];
$result = Arr::filterByValue($array, fn($value) => $value > 1);
print_r($result);
// ['b' => 2, 'c' => 3]
```
<br>

### E. `partition()` <a id="partition"></a>
Partitions an array into two groups: one where the callback returns true, the other where it returns false.

Parameters:
- `array $array` - The array to partition.
- `callable $callback` - The callback function.

Returns:
- `array` - An array with two arrays (true, false).

Example:
```php
$array = [1, 2, 3, 4, 5];
list($even, $odd) = Arr::partition($array, fn($value) => $value % 2 === 0);
print_r($even);
// [2, 4]
print_r($odd);
// [1, 3, 5]
```
<br>

### F. `reject()` <a id="reject"></a>
Rejects elements that match a given condition.

Parameters:
- `array $array` - The source array.
- `callable $callback` - The function to determine rejection.

Returns:
- `array` - The modified array.

Example:
```php
$array = [1, 2, 3, 4, 5];
$result = Arr::reject($array, fn($value) => $value > 3);
print_r($result);
// [1, 2, 3]
```
<br>

### G. `unique()` <a id="unique"></a>
Removes duplicate values from an array.

Parameter:
- `array $array` - The source array.

Returns:
- `array` - The array without duplicate values.

Example:
```php
$array = [1, 2, 2, 3, 3, 4];
$result = Arr::unique($array);
print_r($result);
// [1, 2, 3, 4]
```
<br>

### H. `uniqueBy()` <a id="uniqueby"></a>
Removes duplicate items from an array based on a key or callback.

Parameters:
- `array $array` - The array to filter.
- `string|callable $key` - The key or function to determine uniqueness.

Returns:
- `array` - The unique array.

Example:
```php
$array = [
    ['id' => 1, 'name' => 'Alice'],
    ['id' => 2, 'name' => 'Bob'],
    ['id' => 1, 'name' => 'Alice']
];
$result = Arr::uniqueBy($array, 'id');
print_r($result);
// [['id' => 1, 'name' => 'Alice'], ['id' => 2, 'name' => 'Bob']]
```
<br>

### I. `where()` <a id="where"></a>
Filters an array using a callback function.

Parameters:
- `array $array` - The source array.
- `callable $callback` - The function to apply to each element.

Returns:
- `array` - The filtered array.

Example:
```php
$array = [1, 2, 3, 4, 5];
$result = Arr::where($array, fn($value) => $value > 2);
print_r($result);
// [3, 4, 5]
```
<br>

## 8. Chunking & Collapsing <a id="chunking-collapsing"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `chunk()` <a id="chunk"></a>
Splits an array into chunks of a given size.

Parameters:
- `array $array` - The array to split.
- `int $size` - The size of each chunk.
- `bool $preserveKeys` - Whether to preserve keys.

Returns:
- `array` - An array of chunked arrays.

Example:
```php
$array = [1, 2, 3, 4, 5];
$result = Arr::chunk($array, 2);
print_r($result);
// [[1, 2], [3, 4], [5]]
```
<br>

### B. `chunkBy()` <a id="chunkby"></a>
Chunks an array into groups based on a callback function.

Parameters:
- `array $array` - The array to chunk.
- `callable $callback` - The function to determine chunks.

Returns:
- `array` - The chunked array.

Example:
```php
$array = [1, 2, 2, 3, 3, 3, 4];
$result = Arr::chunkBy($array, fn($a, $b) => $a === $b);
print_r($result);
// [[1], [2, 2], [3, 3, 3], [4]]
```
<br>

### C. `collapse()` <a id="collapse"></a>
Collapses a multi-dimensional array into a single-level array.

Parameter:
- `array $array` - The multi-dimensional array.

Returns:
- `array` - The collapsed array.

Example:
```php
$array = [[1, 2], [3, 4], [5]];
$result = Arr::collapse($array);
print_r($result);
// [1, 2, 3, 4, 5]
```
<br>

## 9. Other Utilities <a id="other-utilities"></a>
### A. `isArray()` <a id="isarray"></a>
Determines if a given value is an array.

Parameter:
- `mixed $value` - The value to check.

Returns:
- `bool` - True if the value is an array, false otherwise.

Example:
```php
$result = Arr::isArray([1, 2, 3]);
var_dump($result);
// true
```
<br>

### B. `isAssoc()` <a id="isassoc"></a>
Determines if an array is associative (i.e., contains at least one non-numeric key).

Parameter:
- `array $array` - The array to check.

Returns:
- `bool` - True if associative, false otherwise.

Example:
```php
$array = ['a' => 1, 'b' => 2];
$result = Arr::isAssoc($array);
var_dump($result);
// true
```
<br>

### C. `isEmpty()` <a id="isempty"></a>
Checks if the given array is empty.

Parameter:
- `array|null $array` - The array to check.

Returns:
- `bool` - True if empty or null, otherwise false.

Example:
```php
$result = Arr::isEmpty([]);
var_dump($result);
// true
```
<br>

### D. `isNotEmpty()` <a id="isnotempty"></a>
Checks if the given array is not empty.

Parameter:
- `array|null $array` - The array to check.

Returns:
- `bool` - True if not empty, otherwise false.

Example:
```php
$result = Arr::isNotEmpty([1, 2, 3]);
var_dump($result);
// true
```
<br>

### E. `random()` <a id="random"></a>
Gets a random value or multiple values from an array.

Parameter:
- `array $array` - The source array.
- `int|null $number` - Number of elements to retrieve.

Returns:
- `mixed` - The random value(s).

Example:
```php
$array = [1, 2, 3, 4, 5];
$result = Arr::random($array);
print_r($result);
// Example output: 3
```
<br>

### F. `toJson()` <a id="tojson"></a>
Converts an array to a JSON string.

Parameters:
- `array $array` - The array to convert.
- `int $options` - JSON encoding options.

Returns:
- `string` - The JSON string.

Example:
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::toJson($array);
print_r($result);
// '{"name":"Alice","age":25}'
```
<br>

### G. `toObject()` <a id="toobject"></a>
Converts an array to an object.

Parameter:
- `array $array` - The array to convert.

Returns:
- `object` - The converted object.

Example:
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::toObject($array);
print_r($result->name);
// Alice
```
<br>

### H. `unwrap()` <a id="unwrap"></a>
Unwraps an array if it contains only one item.

Parameter:
- `array $array` - The array to unwrap.

Returns:
- `mixed` - The single value or the original array.

Example:
```php
$array = ['single'];
$result = Arr::unwrap($array);
print_r($result);
// 'single'
```
<br>

### I. `walkRecursive()` <a id="walkrecursive"></a>
Recursively applies a callback function to each element in an array.

Parameters:
- `array $array` - The array to be processed.
- `callable $callback` - The callback function to apply.  The callback should accept two parameters: 
    - `mixed $value` - The array value
    - `string|int $key` - The array key

Returns:
- `array` - The modified array with the callback applied to each value.

Example:
```php
$array = [1, [2, 3], 4];
$result = Arr::walkRecursive($array, fn($value) => $value * 2);
print_r($result);
// [2, [4, 6], 8]
```
<br>

### J. `weightedRandom()` <a id="weightedrandom"></a>
Selects a random element based on weighted probabilities.

Parameters:
- `array $array` - The array with weights.
- `array $weights` - The corresponding weights.

Returns:
- `mixed` - A randomly selected item.

Example:
```php
$items = ['apple', 'banana', 'cherry'];
$weights = [1, 2, 1];
$result = Arr::weightedRandom($items, $weights);
print_r($result);
// Example output: 'banana'
```
<br>

### K. `wrap()` <a id="wrap"></a>
Wraps a value in an array.

Parameters:
- `mixed $value` - The value to wrap.

Returns:
- `array` - The wrapped array.

Example:
```php
$result = Arr::wrap('hello');
print_r($result);
// ['hello']
```
<br>

### L. `xorDiff()` <a id="xordiff"></a>
Computes the exclusive difference between two arrays.

Parameters:
- `array $array1` - The first array.
- `array $array2` - The second array.

Returns:
- `array` - The values that exist only in one of the arrays.

Example:
```php
$array1 = [1, 2, 3];
$array2 = [3, 4, 5];
$result = Arr::xorDiff($array1, $array2);
print_r($result);
// [1, 2, 4, 5]
```