<h1 style="font-size: 50px; text-align: center;">Arr Class</h1>

## Table of contents
1. [Overview](#overview)
2. [Basic Usage](#usage)
3. [Methods](#methods)
    * A. [Constructor](#constructor)
    * B. [make](#make)
4. [Retrieving Data](#retrieving-data)
    * A. [all](#all)
    * B. [column](#column)
    * C. [count](#count)
    * D. [exists](#exists)
    * E. [first](#first)
    * F. [firstKey](#first-key)
    * G. [get](#get)
    * H. [has](#has)
    * I. [hasAny](#has-any)
    * J. [keys](#keys)
    * K. [last](#last)
    * L. [lastKey](#lastKey)
    * M. [result](#result)
    * N. [search](#search)
    * O. [shift](#shift)
    * P. [values](#values)
5. [Iteration, Sorting, Ordering, & Transformation](#sorting-ordering)
    * A. [asort](#asort)
    * B. [arsort](#arsort)
    * C. [flatten](#flatten)
    * D. [flip](#flip)
    * E. [keyBy](#key-by)
    * F. [krsort](#krsort)
    * G. [ksort](#ksort)
    * H. [rsort](#rsort)
    * I. [sort](#sort)
    * J. [usort](#usort)
    * K. [walk](#walk)
    * L. [walkRecursive](#walk-recursive)
6. [Manipulation](#manipulation)
    * A. [add](#add)
    * B. [clear](#clear)
    * C. [combine](#combine)
    * D. [crossJoin](#cross-join)
    * E. [dot](#dot)
    * F. [each](#each)
    * G. [except](#except)
    * H. [fill](#fill)
    * I. [forget](#forget)
    * J. [merge](#merge)
    * K. [only](#only)
    * L. [pad](#pad)
    * M. [pluck](#pluck)
    * N. [prepend](#prepend)
    * O. [pull](#pull)
    * P. [push](#push)
    * Q. [reduce](#reduce)
    * R. [replace](#replace)
    * S. [set](#set)
    * T. [shuffle](#shuffle)
    * U. [shuffleAssociative](#shuffle-associative)
    * V. [slice](#slice)
    * W. [splice](#splice)
    * X. [udiff](#udiff)
7. [Comparison, Checking, Filtering, & Mapping](#comparison-filtering-mapping)
    * A. [contains](#contains)
    * B. [diff](#diff)
    * C. [filter](#filter)
    * D. [intersect](#intersect)
    * E. [intersectKeys](#intersect-keys)
    * F. [isArray](#is-array)
    * G. [isEmpty](#is-empty)
    * H. [map](#map)
    * I. [unique](#unique)
    * J. [where](#where)
8. [Chunking and Collapsing](#chunking-collapsing)
    * A. [chunk](#chunk)
    * B. [collapse](#collapse)
9. [Mapping and Recursive Operations](#mapping-recursion)
    * A. [mapRecursive](#map-recursive)
    * B. [mapWithKeys](#map-with-keys)
    * C. [multiSort](#multi-sort)
10. [Other Utilities](#other-utilities)
    * A. [implode](#implode)
    * B. [random](#random)
    * C. [reverse](#reverse)
    * D. [wrap](#wrap)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
A collection of functions for manipulating arrays that are chainable.
To use this class make sure it is properly loaded:
```php
use Core\Lib\Utilities\ArraySet;
```
<br>

## 2. Basic Usage <a id="usage"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
To use the `ArraySet` class, instantiate it with an array:
```php
$arr = new ArraySet([1, 2, 3, 4]);
```
<br>

---

Initializing when you don't know if the variable is an instance of the `Arr` class or an `array` type.
```php
$errors = $errors instanceof Arr ? $errors : new ArraySet($errors);
```
<br>

---

Most methods support chaining:
```php
$arr = (new ArraySet([5, 3, 8, 1]))
    ->sort()
    ->reverse()
    ->all(); 

print_r($arr); // Output: [8, 5, 3, 1]
```
<br>

---

Examples
```php
$data = new ArraySet(['name' => 'John', 'age' => 30]);

// Get a value
echo $data->get('name'); // John

// Sort values
$sorted = (new ArraySet([3, 1, 2]))->sort()->all(); // [1, 2, 3]

// Filter
$filtered = (new ArraySet([1, 2, 3, 4]))->where(fn($n) => $n > 2)->all(); // [3, 4]

$data = new ArraySet([
    ['id' => 1, 'name' => 'Alice'],
    ['id' => 2, 'name' => 'Bob'],
]);

// Get only the names
$names = $data->column('name')->all(); 
// ['Alice', 'Bob']

// Remove a key
$filtered = (new ArraySet(['name' => 'John', 'age' => 30]))->except('age')->all();
// ['name' => 'John']

// Check for a value
$hasTwo = (new ArraySet([1, 2, 3, 4]))->contains(2)->result(); 
// true

$arr = new ArraySet([
    ['name' => 'Alice', 'age' => 30],
    ['name' => 'Bob', 'age' => 25]
]);

$arr->multiSort(SORT_ASC)->all();
/*
[
    ['name' => 'Bob', 'age' => 25],
    ['name' => 'Alice', 'age' => 30]
]
*/
```
<br>

## 3. Methods <a id="methods"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `construct()` <a id="constructor">
Initializes an `Arr` instance.

Parameter:
- `array $items` - The initial array.

Example:
```php
$arr = new ArraySet(['name' => 'John', 'age' => 30]);
```
<br>

### B. `make()` <a id="make">
Wraps a value into an array if it's not already an array.

Parameters:
- `array $items` - The initial array.
- `mixed $value` - The value to add.

Returns:
- `self`

Example:
```php
$arr = Arr::make('Hello')->all(); // Output: ['Hello']
```
<br>

## 4. Retrieving Data <a id="retrieving-data"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `all()` <a id="all">
Get all items in the array.

Returns:
- `array` - The stored array.

Example:
```php
$arr = new ArraySet([1, 2, 3]);
print_r($arr->all()); // [1, 2, 3]
```
<br>

### B. `column()` <a id="column">
Extracts values from a specific column in a multi-dimensional array.

Parameter:
- `string|int $columnKey` - The column key.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([
    ['id' => 1, 'name' => 'Alice'],
    ['id' => 2, 'name' => 'Bob']
]);
$arr->column('name')->all();
// ['Alice', 'Bob']
```
<br>

### C. `count()` <a id="count">
Returns the number of elements in the array.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3, 4]);
echo $arr->count()->result(); 
// 4
```
<br>

### D. `exists()` <a id="exists">
Checks if a given key exists in the array.

Parameter:
- `string $key` - The key to check.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'John', 'age' => 30]);
var_dump($arr->exists('age')->result());
// true
```
<br>

### E. `first()` <a id="first">
Retrieves the first element of the array, or the first element that matches a given condition.

Parameter:
- `callable|null $callback` - A callback function to test elements.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([10, 20, 30, 40]);
echo $arr->first()->result(); 
// 10

// With a condition
$arr = new ArraySet([10, 20, 30, 40]);
echo $arr->first(fn($v) => $v > 25)->result(); 
// 30
```
<br>

### F. `firstKey()` <a id="firstKey">
Retrieves the first key of the array.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'Alice', 'age' => 30]);
echo $arr->firstKey()->result();
// name
```
<br>

### G. `get()` <a id="get">
Retrieves a value by key, supporting dot notation.

Parameters:
- `string $key` - The key in dot notation.
- `mixed $default` - The default value if the key is not found.

Returns:
- `self`

Example:
```php
$data = new ArraySet(['user' => ['name' => 'John']]);
echo $data->get('user.name'); // John
```
<br>

### H. `has()` <a id="has">
Checks if a key exists.

Parameter:
- `string|int $key` - The key to check (can be a string or integer).

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'John']);
var_dump($arr->has('name')); // true
```
<br>

### I. `hasAny()` <a id="has-any">
Checks if at least one key exists.

Parameter:
- `array|string $keys` - The keys to check.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'John', 'age' => 30]);
var_dump($arr->hasAny(['name', 'email'])); // true
```
<br>

### J. `keys()` <a id="keys">
Returns the array keys.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['a' => 1, 'b' => 2]);
print_r($arr->keys()->all()); // ['a', 'b']
```
<br>

### K. `last()` <a id="last">
Retrieves the last element of the array, or the last element that matches a given condition.

Parameter:
- `callable|null $callback` - A callback function to test elements.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([10, 20, 30, 40]);
echo $arr->last()->result(); 
// 40

// With a condition
$arr = new ArraySet([10, 20, 30, 40]);
echo $arr->last(fn($v) => $v < 25)->result(); 
// 20
```
<br>

### L. `lastKey()` <a id="lastKey">
Retrieves the last key of the array.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'Alice', 'age' => 30]);
echo $arr->lastKey()->result();
// age
```
<br>

### M. `result()` <a id="result">
Retrieves the last computed result from a function that does not modify the original array.

Returns:
- `mixed`

Example:
```php
$arr = new ArraySet([1, 2, 3]);
$arr->count();
print_r($arr->result());
// 3
```
<br>

### N. `search()` <a id="search">
Searches for a value in the array and returns its key.

Parameter:
- `mixed $value` - The value to search for.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['apple' => 'red', 'banana' => 'yellow']);
$arr->search('yellow');
print_r($arr->result());
// 'banana'
```
<br>

### O. `shift()` <a id="shift">
Removes and returns the first item from the array.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3, 4]);
$arr->shift();
print_r($arr->all());
print_r($arr->result());
// [2, 3, 4]
// 1
```
<br>

### P. `values()` <a id="values">
Returns only the array values.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['a' => 1, 'b' => 2]);
print_r($arr->values()->all()); // [1, 2]
```
<br>

## 5. Iteration, Sorting, Ordering, & Transformation <a id="sorting-ordering-transformation"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `asort()` <a id="asort">
Sorts while maintaining key association.

Returns:
- `self` - The modified ArraySet instance.

Example:
```php
$arr = new ArraySet(['b' => 3, 'a' => 1, 'c' => 2]);
$arr->asort()->all(); // ['a' => 1, 'c' => 2, 'b' => 3]
```
<br>

### B. `arsort()` <a id="arsort">
Sorts in descending order while maintaining key association.

Returns:
- `self` - The modified ArraySet instance.

Example:
```php
$arr = new ArraySet(['b' => 3, 'a' => 1, 'c' => 2]);
$arr->arsort()->all(); // ['b' => 3, 'c' => 2, 'a' => 1]
```
<br>

### C. `flatten()` <a id="flatten">
Flattens a multi-dimensional array into a single-level array.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([[1, 2], [3, 4], [5]]);
$arr->flatten()->all();
// [1, 2, 3, 4, 5]
```
<br>

### D. `flip()` <a id="flip">
Swaps the keys and values of an array.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'Alice', 'age' => 30]);
$arr->flip()->all();
// ['Alice' => 'name', '30' => 'age']
```
<br>

### E. `keyBy()` <a id="key-by">
Uses a specific field in a multi-dimensional array as the key.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([
    ['id' => 1, 'name' => 'Alice'],
    ['id' => 2, 'name' => 'Bob']
]);
$arr->keyBy('id')->all();
/*
[
    1 => ['id' => 1, 'name' => 'Alice'],
    2 => ['id' => 2, 'name' => 'Bob']
]
*/
```
<br>

### F. `krsort()` <a id="krsort">
Sorts an array by keys in descending order.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['b' => 2, 'a' => 1, 'c' => 3]);
$arr->krsort()->all();
// ['c' => 3, 'b' => 2, 'a' => 1]
```
<br>

### G. `ksort()` <a id="ksort">
Sorts an array by keys in ascending order.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['b' => 2, 'a' => 1, 'c' => 3]);
$arr->ksort()->all();
// ['a' => 1, 'b' => 2, 'c' => 3]
```
<br>


### H. `rsort()` <a id="rsort">
Sorts in descending order.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([5, 3, 8, 1]);
$arr->rsort()->all(); // [8, 5, 3, 1]
```
<br>

### I. sort <a id="sort">
Sorts values in ascending order.

Parameter:
- `int $sortFlags` - Flags for sorting (default: SORT_REGULAR).

Returns:
- `self`

Example:
```php
$arr = new ArraySet([5, 3, 8, 1]);
$arr->sort()->all(); // [1, 3, 5, 8]
```
<br>

### J. `usort()` <a id="usort">
Sorts the array using a user-defined comparison function.

Parameter:
- `callable $callback` - The comparison function.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([3, 1, 4, 2]);
$arr->usort(fn($a, $b) => $a <=> $b);
print_r($arr->all());
// [1, 2, 3, 4]
```
<br>

### K. `walk()` <a id="walk">
Applies a user function to every item in the array.

Parameter:
- `callable $callback` - The function to apply.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3]);
$arr->walk(fn(&$value) => $value *= 2);
print_r($arr->all());
// [2, 4, 6]
```
<br>

### L. `walkRecursive()` <a id="walk-recursive">
Applies a user function to every item in a multi-dimensional array.

Parameter:
- `callable $callback` - The function to apply.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([
    ['value' => 1],
    ['value' => 2]
]);
$arr->walkRecursive(fn(&$value) => is_numeric($value) ? $value *= 2 : $value);
print_r($arr->all());
//  [['value' => 2], ['value' => 4]]
```
<br>

## 6. Manipulation <a id="manipulation"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `add()` <a id="add">
Adds a value if the key does not exist.

Parameters:
- `string $key` - The key to check.
- `mixed $value` - The value to add.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'John']);
$arr->add('age', 30)->all(); // ['name' => 'John', 'age' => 30]
```
<br>

### B. `clear()` <a id="clear">
Removes all elements.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3]);
$arr->clear()->all(); // []
```
<br>

### C. `combine()` <a id="combine">
Combines two arrays, one as keys and one as values.

Parameters:
- `array $keys` - The keys.
- `array $values` - The values.

Returns:
- `self`

Throws:
- \InvalidArgumentException Thrown when array of values and keys are not the same length.

Example:
```php
$keys = ['name', 'age', 'city'];
$values = ['John', 30, 'New York'];

$arr = Arr::combine($keys, $values);
$arr->all();
// ['name' => 'John', 'age' => 30, 'city' => 'New York']
```
<br>

### D. `crossJoin()` <a id="crossJoin">
Computes the Cartesian product of multiple arrays.

Parameter:
- `array ...$arrays` - Arrays to join.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2]);
$arr->crossJoin(['A', 'B'])->all();
/*
[
    [1, 'A'], [1, 'B'],
    [2, 'A'], [2, 'B']
]
*/
```
<br>

### E. `dot()` <a id="dot">
Converts a multi-dimensional array into a dot notation format.

Parameter:
- `string $prepend` - A string to prepend before keys.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([
    'user' => ['name' => 'John', 'age' => 30]
]);
$arr->dot()->all();
// ['user.name' => 'John', 'user.age' => 30]
```
<br>

### F. `each()` <a id="each">
Applies a callback to each element in the array.

Parameter:
- `callable $callback` - The function to apply.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3]);
$arr->each(function ($value, $key) {
    echo "$key => $value\n";
});
/*
0 => 1
1 => 2
2 => 3
*/
```
<br>

### G. `except()` <a id="except">
Removes specific keys from the array.

Parameter:
- `array|string $keys` - The keys to remove.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'John', 'age' => 30, 'city' => 'New York']);
$arr->except('age')->all();
// ['name' => 'John', 'city' => 'New York']
```
<br>

### H. `fill()` <a id="fill">
Fills the array with a specified value starting at a given index and continuing for a specified number of elements.

Parameters:
- `int $start` - Index to start filling.
- `int $count` - Number of elements to insert.
- `mixed $value` - The value to insert.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3]);
$arr->fill(1, 3, "X");

print_r($arr->all());
//[1, "X", "X", "X"]
```
<br>

### I. `forget()` <a id="forget">
Removes an item by key.

Parameter:
- `string $key` - The key to remove.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'John', 'age' => 30]);
$arr->forget('name')->all(); // ['age' => 30]
```
<br>

### J. `merge()` <a id="merge">
Merges another array.

Parameter:
- `array $array` - The array to merge with.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'John']);
$arr->merge(['age' => 30])->all(); // ['name' => 'John', 'age' => 30]
```
<br>

### K. `only()` <a id="only">
Returns a new array containing only the specified keys.

Parameter:
- `array|string $keys` - The keys to include.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'John', 'age' => 30, 'city' => 'New York']);
$arr->only(['name', 'city']);
print_r($arr->all());
// ['name' => 'John', 'city' => 'New York']
```
<br>

### L. `pad()` <a id="pad">
Expands the array to a specified size by padding it with a given value.

Parameter:
- `int $size` - The required size of the array.
- `mixed $value` - The value to pad with.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3]);
$arr->pad(5, 0);
print_r($arr->all());
// [1, 2, 3, 0, 0]
```
<br>

### M. `pluck()` <a id="pluck">
Extracts values from an array of associative arrays based on a given key.

Parameter:
- `string $key` - The key to pluck.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([
    ['name' => 'John', 'age' => 30],
    ['name' => 'Jane', 'age' => 25]
]);
$arr->pluck('name');
print_r($arr->all());
// ['John', 'Jane']
```
<br>

### N. `prepend()` <a id="prepend">
Adds a value to the beginning of the array.

Parameter:
- `mixed $value` - The value to prepend.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([2, 3, 4]);
$arr->prepend(1);
print_r($arr->all());
// [1, 2, 3, 4]
```
<br>

### O. `pull()` <a id="pull">
Retrieves a value from the array and removes it.

Parameters:
- `string $key` - The key to retrieve and remove.
- `mixed $default` - The default value if the key is not found.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'John', 'age' => 30]);
$arr->pull('age');
print_r($arr->all());
print_r($arr->result());
// ['name' => 'John']
// 30
```
<br>

### P. `push()` <a id="push">
Adds one or more values to the end of the array.

Parameter:
- `mixed ...$values` - The values to add.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3]);
$arr->push(4, 5);
print_r($arr->all());
// [1, 2, 3, 4, 5]
```
<br>

### Q. `reduce()` <a id="reduce">
Reduces the array to a single value using a callback function.

Parameter:
- `callable $callback` - The callback function.
- `mixed $initial` - The initial value.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3, 4]);
$arr->reduce(fn($carry, $item) => $carry + $item, 0);
print_r($arr->result());
// 10
```
<br>

### R. `replace()` <a id="replace">
Replaces values in the current array with values from another array.

Parameter:
- array $array The array with replacement values.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'John', 'age' => 30]);
$arr->replace(['age' => 35, 'city' => 'New York']);
print_r($arr->all());
// ['name' => 'John', 'age' => 35, 'city' => 'New York']
```
<br>

### S. `set()` <a id="set">
Sets a value using dot notation.

Parameters:
- `string $key` - The key in dot notation.
- `mixed $value` -  The value to set.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([]);
$arr->set('user.name', 'John')->all(); // ['user' => ['name' => 'John']]
```
<br>

### T. `shuffle()` <a id="shuffle">
Randomly shuffles the elements in the array.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3, 4, 5]);
$arr->shuffle();
print_r($arr->all());

// Output may vary
// [3, 5, 1, 4, 2] 
```
<br>

### U. `shuffleAssociative()` <a id="shuffleAssociative">
Shuffles the elements of an associative array while preserving key-value relationships.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['a' => 1, 'b' => 2, 'c' => 3]);
$arr->shuffleAssociative();
print_r($arr->all());

// Output may vary
// ['c' => 3, 'a' => 1, 'b' => 2]
```
<br>

### V. `slice()` <a id="slice">
Extracts a portion of the array.

Parameters:
- `int $offset` - The index to start the slice.
- `int|null $length` - The number of elements to extract.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3, 4, 5]);
$arr->slice(1, 3);
print_r($arr->all());
// [2, 3, 4]
```
<br>

### W. `splice()` <a id="splice">
Removes and replaces a portion of the array.

Parameters:
- `int $offset` - The index to start the splice.
- `int|null $length` - The number of elements to remove.
- `array $replacement` - The replacement values.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3, 4, 5]);
$arr->splice(2, 2, [6, 7]);
print_r($arr->all());
// [1, 2, 6, 7, 5]
```
<br>

### X. `udiff()` <a id="udiff">
Computes the difference between arrays using a custom comparison function.

Parameters:
- `array $array` - The array to compare.
- `callable $callback` - The comparison function.

Returns:
- `self`

Example:
```php
$arr1 = new ArraySet([1, 2, 3, 4, 5]);
$arr2 = [3, 4];

$arr1->udiff($arr2, fn($a, $b) => $a <=> $b);
print_r($arr1->all());
// [1, 2, 5]
```
<br>

## 7. Comparison, Checking, Filtering, & Mapping <a id="comparison-filtering-mapping"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `contains()` <a id="contains">
Checks if an array contains a specific value.

Parameters:
- `mixed $value` - The value to search for.
- `bool $strict` - Whether to perform strict comparison.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3, 4]);
var_dump($arr->contains(3)->result());
// true
```
<br>

### B. `diff()` <a id="diff">
Finds the difference between the current array and another array.

Parameter:
- `array $array` - The array to compare.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3, 4]);
$arr->diff([2, 4])->all();
// [1, 3]
```
<br>

### C. `filter()` <a id="filter">
Filters elements based on a condition.

Parameter:
- `callable $callback` - The function to apply for filtering.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3, 4]);
$arr->filter(fn($n) => $n % 2 === 0)->all(); // [2, 4]
```
<br>

### D. `intersect()` <a id="intersect">
Finds the common values between the current array and another array.

Parameter:
- `array $array` - The array to compare.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3, 4]);
$arr->intersect([2, 4, 6])->all();
// [2, 4]
```
<br>

### E. `intersectKeys()` <a id="intersect-keys">
Finds elements whose keys exist in another array.

Parameter:
- `array $array` - The array to compare.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'Alice', 'age' => 30, 'city' => 'New York']);
$arr->intersectKeys(['age' => '', 'city' => ''])->all();
// ['age' => 30, 'city' => 'New York']
```
<br>

### F. `isArray()` <a id="is-array">
Checks if the given value is an array.

Parameter:
- `mixed $value` - The value to check.

Returns:
- `self`

Example:
```php
$arr = new ArraySet();
var_dump($arr->isArray([1, 2, 3])->result()); 
// true
```
<br>

### G. `isEmpty()` <a id="is-empty">
Checks if the array is empty.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([]);
var_dump($arr->isEmpty()->result());
// true
```
<br>

### H. `map()` <a id="map">
Applies a function to each item.

Parameter:
- `callable $callback` - The function to apply.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3]);
$arr->map(fn($n) => $n * 2)->all(); // [2, 4, 6]
```
<br>

### I. `unique()` <a id="unique">
Removes duplicate values.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 2, 3, 3]);
$arr->unique()->all(); // [1, 2, 3]
```
<br>

### J. `where()` <a id="where">
Filters values where callback returns true.

Parameter:
- `callable $callback` - The function to apply.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([['age' => 18], ['age' => 25], ['age' => 30]]);
$arr->where(fn($item) => $item['age'] >= 25)->all(); 
// [['age' => 25], ['age' => 30]]
```
<br>

## 8. Chunking & Collapsing <a id="chunking-collapsing"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `chunk()` <a id="chunk">
Splits an array into chunks of the specified size.

Parameter:
- `int $size` - The size of each chunk.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3, 4, 5, 6]);
$arr->chunk(2)->all(); 
// [[1, 2], [3, 4], [5, 6]]
```
<br>

### B. `collapse()` <a id="collapse">
Flattens a multi-dimensional array into a single-level array.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([[1, 2], [3, 4], [5]]);
$arr->collapse()->all();
// [1, 2, 3, 4, 5]
```
<br>

## 9. Mapping and Recursive Operations <a id="mapping-recursion"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `mapRecursive()` <a id="mapRecursive">
Recursively applies a callback function to each element in the array.

Parameter:
- `callable $callback` - The function to apply.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([
    [1, 2, 3],
    [4, 5, [6, 7]]
]);

$arr->mapRecursive(fn($v) => $v * 2)->all();
/*
[
    [2, 4, 6],
    [8, 10, [12, 14]]
]
*/
```
<br>

### B. `mapWithKeys()` <a id="mapWithKeys">
Maps an array using a callback that defines both keys and values.

Parameter:
- `callable $callback` - The function to apply.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['name' => 'Alice', 'age' => 30]);
$arr->mapWithKeys(fn($v, $k) => [$k . '_modified' => $v])->all();
/*
[
    'name_modified' => 'Alice',
    'age_modified' => 30
]
*/
```
<br>

### C. `multiSort()` <a id="multiSort">
Sorts multiple arrays or multi-dimensional arrays.

Parameter:
- `int $sortFlags` - The sorting flags.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([
    ['name' => 'Alice', 'age' => 30],
    ['name' => 'Bob', 'age' => 25]
]);

$arr->multiSort(SORT_ASC)->all();
/*
[
    ['name' => 'Bob', 'age' => 25],
    ['name' => 'Alice', 'age' => 30]
]
*/
```
<br>

## 10. Other Utilities <a id="other-utilities"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `implode()` <a id="implode">
Joins array values into a string.

Parameter:
- `string $separator` - The separator string.

Returns:
- `self`

Example:
```php
$arr = new ArraySet(['apple', 'banana', 'cherry']);
echo $arr->implode(', '); // "apple, banana, cherry"
```
<br>

### B. `random()` <a id="random">
Retrieves a random value or values.

Parameter:
- `int|null $number` - Number of elements to retrieve.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3, 4]);
echo $arr->random(); // Random value from the array
```
<br>

### C. `reverse()` <a id="reverse">
Reverses the order.

Returns:
- `self`

Example:
```php
$arr = new ArraySet([1, 2, 3]);
$arr->reverse()->all(); // [3, 2, 1]
```

### D. `wrap()` <a id="wrap">
Ensures the given value is an array. If it's not, wraps it in an array.

Parameter:
- `mixed $value` - The value to wrap.

Returns:
- `self`

Example:
```php
$arr = new ArraySet();
$arr->wrap('hello');
print_r($arr->all());
// ['hello']
```