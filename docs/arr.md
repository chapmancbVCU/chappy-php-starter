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
    * C. [insertBefore](#insertbefore)  
    * D. [insertAfter](#insertafter)  
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
#### A. add <a id="add"></a>
`add(array $array, string|int $key, mixed $value): array`

Adds a value to an array if the key does not exist.
```php
$array = ['name' => 'Alice'];
$array = Arr::add($array, 'age', 25);
print_r($array);
// ['name' => 'Alice', 'age' => 25]
```
<br>

#### B. arrayDivide <a id="arraydivide"></a>
`arrayDivide(array $array): array`

Splits an array into two arrays: one with keys and one with values.
```php
$array = ['name' => 'Alice', 'age' => 25];
list($keys, $values) = Arr::arrayDivide($array);
print_r($keys);
// ['name', 'age']
print_r($values);
// ['Alice', 25]
```
<br>

#### C. arrayPluckMulti <a id="arraypluckmulti"></a>
`arrayPluckMulti(array $array, array|string $keys): array`

Plucks nested values from an array.
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

#### D. arrayShuffleAssoc <a id="arrayshuffleassoc"></a>
`arrayShuffleAssoc(array $array): array`

Shuffles an associative array while preserving keys.
```php
$array = ['a' => 1, 'b' => 2, 'c' => 3];
$shuffled = Arr::arrayShuffleAssoc($array);
print_r($shuffled);
// Example output: ['b' => 2, 'c' => 3, 'a' => 1]
```
<br>

#### E. chunk <a id="chunk"></a>
`chunk(array $array, int $size, bool $preserveKeys = false): array`

Splits an array into chunks of a given size.
```php
$array = [1, 2, 3, 4, 5];
$chunks = Arr::chunk($array, 2);
print_r($chunks);
// [[1, 2], [3, 4], [5]]
```
<br>

#### F. chunkBy <a id="chunkby"></a>
`chunkBy(array $array, callable $callback): array`

Chunks an array into groups based on a callback function.
```php
$array = [1, 2, 2, 3, 3, 3, 4];
$chunks = Arr::chunkBy($array, fn($a, $b) => $a === $b);
print_r($chunks);
// [[1], [2, 2], [3, 3, 3], [4]]
```
<br>

#### G. collapse <a id="collapse"></a>
`collapse(array $array): array`

Collapses a multi-dimensional array into a single-level array.
```php
$array = [[1, 2], [3, 4], [5]];
$flattened = Arr::collapse($array);
print_r($flattened);
// [1, 2, 3, 4, 5]
```
<br>

#### H. contains <a id="contains"></a>
`contains(array $array, mixed $value, bool $strict = false): bool`

Determines if a given value exists in an array.
```php
$array = [1, 2, 3, 'a' => 'apple'];
$result = Arr::contains($array, 'apple');
var_dump($result);
// true
```
<br>

#### I. crossJoin <a id="crossjoin"></a>
`crossJoin(array ...$arrays): array`

Computes the Cartesian product of multiple arrays.
```php
$array1 = [1, 2];
$array2 = ['a', 'b'];
$result = Arr::crossJoin($array1, $array2);
print_r($result);
// [[1, 'a'], [1, 'b'], [2, 'a'], [2, 'b']]
```
<br>

#### J. deepMerge <a id="deepmerge"></a>
`deepMerge(array ...$arrays): array`

Recursively merges two or more arrays.
```php
$array1 = ['name' => 'Alice', 'details' => ['age' => 25]];
$array2 = ['details' => ['city' => 'New York']];
$result = Arr::deepMerge($array1, $array2);
print_r($result);
// ['name' => 'Alice', 'details' => ['age' => 25, 'city' => 'New York']]
```
<br>

#### K. diffAssocRecursive <a id="diffassocrecursive"></a>
`diffAssocRecursive(array $array1, array $array2): array`

Recursively computes the difference between two arrays with keys.
```php
$array1 = ['a' => 1, 'b' => ['x' => 10, 'y' => 20]];
$array2 = ['a' => 1, 'b' => ['x' => 10]];
$result = Arr::diffAssocRecursive($array1, $array2);
print_r($result);
// ['b' => ['y' => 20]]
```
<br>

#### L. dot <a id="dot"></a>
`dot(array $array, string $prepend = ''): array`

Converts a multi-dimensional array into dot notation keys.
```php
$array = ['name' => 'Alice', 'details' => ['age' => 25, 'city' => 'New York']];
$result = Arr::dot($array);
print_r($result);
// ['name' => 'Alice', 'details.age' => 25, 'details.city' => 'New York']
```
<br>

## 4. Retrieving Data <a id="retrieving-data"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### A. except <a id="except"></a>
`except(array $array, array $keys): array`

Gets all items except the specified keys.
```php
$array = ['name' => 'Alice', 'age' => 25, 'city' => 'New York'];
$result = Arr::except($array, ['age']);
print_r($result);
// ['name' => 'Alice', 'city' => 'New York']
```
<br>

#### B. exists <a id="exists"></a>
`exists(array $array, string|int $key): bool`

Checks if a key exists in an array (non-dot notation).
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::exists($array, 'age');
var_dump($result);
// true
```
<br>

#### C. first <a id="first"></a>
`first(array $array, ?callable $callback = null, mixed $default = null): mixed`

Gets the first element that passes a given test.
```php
$array = [2, 4, 6, 8];
$result = Arr::first($array, fn($value) => $value > 4);
print_r($result);
// 6
```
<br>

#### D. get <a id="get"></a>
`get(array $array, string $key, mixed $default = null): mixed`

Gets a value from an array using dot notation.
```php
$array = ['name' => 'Alice', 'details' => ['age' => 25]];
$result = Arr::get($array, 'details.age');
print_r($result);
// 25
```
<br>

#### E. groupBy <a id="groupby"></a>
`groupBy(array $array, string $key): array`

Groups an array by a given key.
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

#### F. has <a id="has"></a>
`has(array $array, string $key): bool`

Checks if an array has a given key using dot notation.
```php
$array = ['name' => 'Alice', 'details' => ['age' => 25]];
$result = Arr::has($array, 'details.age');
var_dump($result);
// true
```
<br>

#### G. hasAllKeys <a id="hasallkeys"></a>
`hasAllKeys(array $array, array $keys): bool`

Checks if all given keys exist in the array.
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::hasAllKeys($array, ['name', 'age']);
var_dump($result);
// true
```
<br>

#### H. hasAnyKey <a id="hasanykey"></a>
`hasAnyKey(array $array, array $keys): bool`

Checks if at least one key exists in the array.
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::hasAnyKey($array, ['age', 'gender']);
var_dump($result);
// true
```
<br>

#### I. keys <a id="keys"></a>
`keys(array $array): array`

Gets all the keys from an array.
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::keys($array);
print_r($result);
// ['name', 'age']
```
<br>

#### J. last <a id="last"></a>
`last(array $array, ?callable $callback = null, mixed $default = null): mixed`

Gets the last element that passes a given test.
```php
$array = [2, 4, 6, 8];
$result = Arr::last($array, fn($value) => $value < 7);
print_r($result);
// 6
```
<br>

#### K. only <a id="only"></a>
`only(array $array, array $keys): array`

Gets only the specified keys from an array.
```php
$array = ['name' => 'Alice', 'age' => 25, 'city' => 'New York'];
$result = Arr::only($array, ['name', 'city']);
print_r($result);
// ['name' => 'Alice', 'city' => 'New York']
```
<br>

#### L. pluck <a id="pluck"></a>
`pluck(array $array, string $value, ?string $key = null): array`

Plucks a single key from an array.
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

#### M. values <a id="values"></a>
`values(array $array): array`

Gets all values from an array, resetting numeric keys.
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::values($array);
print_r($result);
// ['Alice', 25]
```
<br>

## 5. Iteration, Sorting, & Transformation <a id="sorting-ordering"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### A. flatten <a id="flatten"></a>
`flatten(array $array, int $depth = INF): array`

Flattens a multi-dimensional array into a single level.
```php
$array = [[1, 2], [3, [4, 5]]];
$result = Arr::flatten($array);
print_r($result);
// [1, 2, 3, 4, 5]
```
<br>

#### B. flattenWithDepth <a id="flattenwithdepth"></a>
`flattenWithDepth(array $array, int $depth = INF): array`

Flattens an array up to a specified depth.
```php
$array = [[1, 2], [3, [4, 5]]];
$result = Arr::flattenWithDepth($array, 1);
print_r($result);
// [1, 2, 3, [4, 5]]
```
<br>

#### C. flattenKeys <a id="flattenkeys"></a>
`flattenKeys(array $array, string $prefix = ''): array`

Converts a multi-dimensional array into dot notation keys.
```php
$array = ['name' => 'Alice', 'details' => ['age' => 25, 'city' => 'New York']];
$result = Arr::flattenKeys($array);
print_r($result);
// ['name' => 'Alice', 'details.age' => 25, 'details.city' => 'New York']
```
<br>

#### D. map <a id="map"></a>
`map(array $array, callable $callback): array`

Applies a callback to each item in an array.
```php
$array = [1, 2, 3];
$result = Arr::map($array, fn($value) => $value * 2);
print_r($result);
// [2, 4, 6]
```
<br>

#### E. mapWithKeys <a id="mapwithkeys"></a>
`mapWithKeys(array $array, callable $callback): array`

Maps an array while preserving keys.
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::mapWithKeys($array, fn($value, $key) => [$key => strtoupper($value)]);
print_r($result);
// ['name' => 'ALICE', 'age' => '25']
```
<br>

#### F. reverse <a id="reverse"></a>
`reverse(array $array, bool $preserveKeys = false): array`

Reverses the order of elements in an array.
```php
$array = [1, 2, 3, 4];
$result = Arr::reverse($array);
print_r($result);
// [4, 3, 2, 1]
```
<br>

#### G. shuffle <a id="shuffle"></a>
`shuffle(array $array, ?int $seed = null): array`

Shuffles the array.
```php
$array = [1, 2, 3, 4, 5];
$result = Arr::shuffle($array);
print_r($result);
// Example output: [3, 1, 4, 5, 2]
```
<br>

#### H. sort <a id="sort"></a>
`sort(array $array, ?callable $callback = null): array`

Sorts an array using a callback function.
```php
$array = [3, 1, 4, 1, 5, 9];
$result = Arr::sort($array);
print_r($result);
// [1, 1, 3, 4, 5, 9]
```
<br>

#### I. sortAssoc <a id="sortassoc"></a>
`sortAssoc(array $array, bool $descending = false): array`

Sorts an associative array by its keys.
```php
$array = ['b' => 2, 'a' => 1, 'c' => 3];
$result = Arr::sortAssoc($array);
print_r($result);
// ['a' => 1, 'b' => 2, 'c' => 3]
```
<br>

#### J. sortBy <a id="sortby"></a>
`sortBy(array $array, string $key, bool $descending = false): array`

Sorts an array by a specific key.
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

#### K. sortByKeys <a id="sortbykeys"></a>
`sortByKeys(array $array): array`

Sorts an array by its keys.
```php
$array = ['b' => 2, 'a' => 1, 'c' => 3];
$result = Arr::sortByKeys($array);
print_r($result);
// ['a' => 1, 'b' => 2, 'c' => 3]
```
<br>

#### L. sortByValues <a id="sortbyvalues"></a>
`sortByValues(array $array): array`

Sorts an array by its values.
```php
$array = ['b' => 3, 'a' => 1, 'c' => 2];
$result = Arr::sortByValues($array);
print_r($result);
// ['a' => 1, 'c' => 2, 'b' => 3]
```
<br>

## 6. Manipulation <a id="manipulation"></a>
#### A. fill <a id="fill"></a>
`fill(int $startIndex, int $count, mixed $value): array`

Fills an array with a specified value.
```php
$result = Arr::fill(0, 3, 'a');
print_r($result);
// ['a', 'a', 'a']
```
<br>

#### B. forget <a id="forget"></a>
`forget(array &$array, string|array $keys): void`

Removes a value from an array using dot notation.
```php
$array = ['name' => 'Alice', 'details' => ['age' => 25]];
Arr::forget($array, 'details.age');
print_r($array);
// ['name' => 'Alice', 'details' => []]
```
<br>

#### C. insertBefore <a id="insertbefore"></a>
`insertBefore(array $array, string|int $key, string|int $newKey, mixed $value): array`

Inserts an element before a given key in an array.
```php
$array = ['a' => 1, 'b' => 2];
$result = Arr::insertBefore($array, 'b', 'x', 99);
print_r($result);
// ['a' => 1, 'x' => 99, 'b' => 2]
```
<br>

#### D. insertAfter <a id="insertafter"></a>
`insertAfter(array $array, string|int $key, string|int $newKey, mixed $value): array`

Inserts an element after a given key in an array.
```php
$array = ['a' => 1, 'b' => 2];
$result = Arr::insertAfter($array, 'a', 'x', 99);
print_r($result);
// ['a' => 1, 'x' => 99, 'b' => 2]
```
<br>

#### E. merge <a id="merge"></a>
`merge(array ...$arrays): array`

Merges one or more arrays together.
```php
$array1 = ['name' => 'Alice'];
$array2 = ['age' => 25];
$result = Arr::merge($array1, $array2);
print_r($result);
// ['name' => 'Alice', 'age' => 25]
```
<br>

#### F. prepend <a id="prepend"></a>
`prepend(array $array, mixed $value, string|int|null $key = null): array`

Prepends a value to an array.
```php
$array = [2, 3, 4];
$result = Arr::prepend($array, 1);
print_r($result);
// [1, 2, 3, 4]
```
<br>

#### G. pull <a id="pull"></a>
`pull(array &$array, string $key, mixed $default = null): mixed`

Retrieves a value from the array and removes it.
```php
$array = ['name' => 'Alice', 'age' => 25];
$age = Arr::pull($array, 'age');
print_r($age);
// 25
print_r($array);
// ['name' => 'Alice']
```
<br>

#### H. push <a id="push"></a>
`push(array &$array, mixed ...$values): array`

Pushes one or more values onto the end of an array.
```php
$array = [1, 2, 3];
Arr::push($array, 4, 5);
print_r($array);
// [1, 2, 3, 4, 5]
```
<br>

#### I. set <a id="set"></a>
`set(array &$array, string $key, mixed $value): void`

Sets a value within an array using dot notation.
```php
$array = ['name' => 'Alice'];
Arr::set($array, 'details.age', 25);
print_r($array);
// ['name' => 'Alice', 'details' => ['age' => 25]]
```
<br>

#### J. shift <a id="shift"></a>
`shift(array &$array): mixed`

Removes and returns the first element of an array.
```php
$array = [1, 2, 3];
$first = Arr::shift($array);
print_r($first);
// 1
print_r($array);
// [2, 3]
```
<br>

#### K. swapKeys <a id="swapkeys"></a>
`swapKeys(array $array, string|int $key1, string|int $key2): array`

Swaps two keys in an array.
```php
$array = ['a' => 1, 'b' => 2];
$result = Arr::swapKeys($array, 'a', 'b');
print_r($result);
// ['a' => 2, 'b' => 1]
```
<br>

#### L. unsetKeys <a id="unsetkeys"></a>
`unsetKeys(array $array, array $keys): array`

Removes multiple keys from an array.
```php
$array = ['name' => 'Alice', 'age' => 25, 'city' => 'New York'];
$result = Arr::unsetKeys($array, ['age', 'city']);
print_r($result);
// ['name' => 'Alice']
```
<br>

## 7. Comparison, Filtering, & Mapping <a id="comparison-filtering-mapping"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### A. contains <a id="contains"></a>
`contains(array $array, mixed $value, bool $strict = false): bool`

Determines if a given value exists in an array.
```php
$array = [1, 2, 3, 'a' => 'apple'];
$result = Arr::contains($array, 'apple');
var_dump($result);
// true
```
<br>

#### B. filter <a id="filter"></a>
`filter(array $array, callable $callback): array`

Filters an array using a callback function.
```php
$array = [1, 2, 3, 4, 5];
$result = Arr::filter($array, fn($value) => $value > 2);
print_r($result);
// [3, 4, 5]
```
<br>

#### C. filterByKeys <a id="filterbykeys"></a>
`filterByKeys(array $array, array $keys): array`

Filters an array to include only the specified keys.
```php
$array = ['name' => 'Alice', 'age' => 25, 'city' => 'New York'];
$result = Arr::filterByKeys($array, ['name', 'city']);
print_r($result);
// ['name' => 'Alice', 'city' => 'New York']
```
<br>

#### D. filterByValue <a id="filterbyvalue"></a>
`filterByValue(array $array, callable $callback): array`

Filters an array by its values.
```php
$array = ['a' => 1, 'b' => 2, 'c' => 3];
$result = Arr::filterByValue($array, fn($value) => $value > 1);
print_r($result);
// ['b' => 2, 'c' => 3]
```
<br>

#### E. partition <a id="partition"></a>
`partition(array $array, callable $callback): array`

Partitions an array into two groups: one where the callback returns true, the other where it returns false.
```php
$array = [1, 2, 3, 4, 5];
list($even, $odd) = Arr::partition($array, fn($value) => $value % 2 === 0);
print_r($even);
// [2, 4]
print_r($odd);
// [1, 3, 5]
```
<br>

#### F. reject <a id="reject"></a>
`reject(array $array, callable $callback): array`

Rejects elements that match a given condition.
```php
$array = [1, 2, 3, 4, 5];
$result = Arr::reject($array, fn($value) => $value > 3);
print_r($result);
// [1, 2, 3]
```
<br>

#### G. unique <a id="unique"></a>
`unique(array $array): array`

Removes duplicate values from an array.
```php
$array = [1, 2, 2, 3, 3, 4];
$result = Arr::unique($array);
print_r($result);
// [1, 2, 3, 4]
```
<br>

#### H. uniqueBy <a id="uniqueby"></a>
`uniqueBy(array $array, string|callable $key): array`

Removes duplicate items from an array based on a key or callback.
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

#### I. where <a id="where"></a>
`where(array $array, callable $callback): array`

Filters an array using a callback function.
```php
$array = [1, 2, 3, 4, 5];
$result = Arr::where($array, fn($value) => $value > 2);
print_r($result);
// [3, 4, 5]
```
<br>

## 8. Chunking & Collapsing <a id="chunking-collapsing"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### A. chunk <a id="chunk"></a>
`chunk(array $array, int $size, bool $preserveKeys = false): array`

Splits an array into chunks of a given size.
```php
$array = [1, 2, 3, 4, 5];
$result = Arr::chunk($array, 2);
print_r($result);
// [[1, 2], [3, 4], [5]]
```
<br>

#### B. chunkBy <a id="chunkby"></a>
`chunkBy(array $array, callable $callback): array`

Chunks an array into groups based on a callback function.
```php
$array = [1, 2, 2, 3, 3, 3, 4];
$result = Arr::chunkBy($array, fn($a, $b) => $a === $b);
print_r($result);
// [[1], [2, 2], [3, 3, 3], [4]]
```
<br>

#### C. collapse <a id="collapse"></a>
`collapse(array $array): array`

Collapses a multi-dimensional array into a single-level array.
```php
$array = [[1, 2], [3, 4], [5]];
$result = Arr::collapse($array);
print_r($result);
// [1, 2, 3, 4, 5]
```
<br>

## 9. Other Utilities <a id="other-utilities"></a>
#### A. isArray <a id="isarray"></a>
`isArray(mixed $value): bool`

Determines if a given value is an array.
```php
$result = Arr::isArray([1, 2, 3]);
var_dump($result);
// true
```
<br>

#### B. isAssoc <a id="isassoc"></a>
`isAssoc(array $array): bool`

Determines if an array is associative (i.e., contains at least one non-numeric key).
```php
$array = ['a' => 1, 'b' => 2];
$result = Arr::isAssoc($array);
var_dump($result);
// true
```
<br>

#### C. isEmpty <a id="isempty"></a>
`isEmpty(?array $array): bool`

Checks if the given array is empty.
```php
$result = Arr::isEmpty([]);
var_dump($result);
// true
```
<br>

#### D. isNotEmpty <a id="isnotempty"></a>
`isNotEmpty(?array $array): bool`

Checks if the given array is not empty.
```php
$result = Arr::isNotEmpty([1, 2, 3]);
var_dump($result);
// true
```
<br>

#### E. random <a id="random"></a>
`random(array $array, ?int $number = null): mixed`

Gets a random value or multiple values from an array.
```php
$array = [1, 2, 3, 4, 5];
$result = Arr::random($array);
print_r($result);
// Example output: 3
```
<br>

#### F. toJson <a id="tojson"></a>
`toJson(array $array, int $options = 0): string`

Converts an array to a JSON string.
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::toJson($array);
print_r($result);
// '{"name":"Alice","age":25}'
```
<br>

#### G. toObject <a id="toobject"></a>
`toObject(array $array): object`

Converts an array to an object.
```php
$array = ['name' => 'Alice', 'age' => 25];
$result = Arr::toObject($array);
print_r($result->name);
// Alice
```
<br>

#### H. unwrap <a id="unwrap"></a>
`unwrap(array $array): mixed`

Unwraps an array if it contains only one item.
```php
$array = ['single'];
$result = Arr::unwrap($array);
print_r($result);
// 'single'
```
<br>

#### I. walkRecursive <a id="walkrecursive"></a>
`walkRecursive(array $array, callable $callback): array`

Recursively applies a callback function to each element in an array.
```php
$array = [1, [2, 3], 4];
$result = Arr::walkRecursive($array, fn($value) => $value * 2);
print_r($result);
// [2, [4, 6], 8]
```
<br>

#### J. weightedRandom <a id="weightedrandom"></a>
`weightedRandom(array $array, array $weights): mixed`

Selects a random element based on weighted probabilities.
```php
$items = ['apple', 'banana', 'cherry'];
$weights = [1, 2, 1];
$result = Arr::weightedRandom($items, $weights);
print_r($result);
// Example output: 'banana'
```
<br>

#### K. wrap <a id="wrap"></a>
`wrap(mixed $value): array`

Wraps a value in an array.
```php
$result = Arr::wrap('hello');
print_r($result);
// ['hello']
```
<br>

#### L. xorDiff <a id="xordiff"></a>
`xorDiff(array $array1, array $array2): array`

Computes the exclusive difference between two arrays.
```php
$array1 = [1, 2, 3];
$array2 = [3, 4, 5];
$result = Arr::xorDiff($array1, $array2);
print_r($result);
// [1, 2, 4, 5]
```