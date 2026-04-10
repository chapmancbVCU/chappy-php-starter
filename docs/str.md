<h1 style="font-size: 50px; text-align: center;">Str Class</h1>

## Table of Contents
1. [Overview](#overview)
2. [after()](#after)
3. [ascii()](#ascii)
4. [base64Encode()](#base64encode)
5. [base64Decode()](#base64decode)
6. [before()](#before)
7. [between()](#between)
8. [camel()](#camel)
9. [chunk()](#chunk)
10. [compare()](#compare)
11. [contains()](#contains)
12. [crc32()](#crc32)
13. [endsWith()](#endswith)
14. [excerpt()](#excerpt)
15. [finish()](#finish)
16. [headline()](#headline)
17. [isAscii()](#isascii)
18. [isEmpty()](#isempty)
19. [isJson()](#isjson)
20. [isUuid()](#isuuid)
21. [kebab()](#kebab)
22. [lastPosition()](#lastposition)
23. [lcfirst()](#lcfirst)
24. [length()](#length)
25. [levenshtein()](#levenshtein)
26. [limit()](#limit)
27. [lower()](#lower)
28. [mask()](#mask)
29. [md5()](#md5)
30. [numberFormat()](#numberformat)
31. [padLeft()](#padleft)
32. [padRight()](#padright)
33. [pascal()](#pascal)
34. [plural()](#plural)
35. [position()](#position)
36. [random()](#random)
37. [repeat()](#repeat)
38. [replace()](#replace)
39. [replaceArray()](#replacearray)
40. [replaceFirst()](#replacefirst)
41. [replaceLast()](#replacelast)
42. [replaceMultiple()](#replacemultiple)
43. [reverse()](#reverse)
44. [sha1()](#sha1)
45. [shuffle()](#shuffle)
46. [similarity()](#similarity)
47. [snake()](#snake)
48. [slug()](#slug)
49. [squish()](#squish)
50. [startsWith()](#startswith)
51. [stripWhitespace()](#stripwhitespace)
52. [studly()](#studly)
53. [substr()](#substr)
54. [substrCount()](#substrcount)
55. [swapKeyValue()](#swapkeyvalue)
56. [title()](#title)
57. [toArray()](#toarray)
58. [ucfirst()](#ucfirst)
59. [ucwords()](#ucwords)
60. [upper()](#upper)
61. [uuid()](#uuid)
62. [wordCount()](#wordcount)
63. [words()](#words)
64. [wrap()](#wrap)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Overview of the Str utility class providing various methods for string manipulation and checks.
<br>

## 2. `after()` <a id="after"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns the portion of the string after the first occurrence of a given substring.

Parameters:
- `string $subject` - The input string.
- `string $search` - The substring to search for.

Returns:
- `string` - Portion of a string after first occurrence of a given value.

Example:
```php
Str::after('hello world', 'hello'); // ' world'
```
<br>

## 3. `ascii()` <a id="ascii"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Converts a string to its ASCII representation.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The ASCII representation of a string.

Example:
```php
Str::ascii('ü'); // 'u'
```
<br>

## 4. `base64Encode()` <a id="base64encode"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Encodes a string using base64.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The Base64 encoded string.

Example:
```php
Str::base64Encode('hello'); // 'aGVsbG8='
```
<br>

## 5. `base64Decode()` <a id="base64decode"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Decodes a base64 encoded string.

Parameter:
- `string $value` - The base64 encoded string.

Returns:
- `string` - The decoded string.

Example:
```php
Str::base64Decode('aGVsbG8='); // 'hello'
```
<br>

## 6. `before()` <a id="before"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns the portion of the string before the first occurrence of a given substring.

Parameter:
- `string $subject` - The input string.
- `string $search` - The substring to search for.

Returns:
- `string` - The portion of a string before the first occurrence of a given value.

Example:
```php
Str::before('hello world', 'world'); // 'hello '
```
<br>

## 7. `between()` <a id="between"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns the substring between two substrings.

Parameters:
- `string $value` - The input string.
- `string $start` - The starting substring.
- `string $end` - The ending substring.

Returns:
- `string` - The substring between two given substrings.

Example:
```php
Str::between('[a] b [c]', '[', ']'); // 'a'
```
<br>

## 8. `camel()` <a id="camel"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Converts a string to camelCase.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The string in camelCase.

Example:
```php
Str::camel('hello_world'); // 'helloWorld'
```
<br>

## 9. `chunk()` <a id="chunk"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Splits a string into chunks.

Parameters:
- `string $value` - The input string.
- `int $length` - The chunk length.

Returns:
- `array` - An array consisting of a string split into chunks.

Example:
```php
Str::chunk('hello', 2); // ['he', 'll', 'o']
```
<br>

## 10. `compare()` <a id="compare"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Compares two strings.

Parameters:
- `string $string1` - The first string.
- `string $string2` - The second string.

Returns:
- `int` - Less 0 if str1 is less than str2; > 0 if str1 is greater than str2, and 0 if they are equal.

Example:
```php
Str::compare('abc', 'abc'); // 0
Str::compare('abc', 'xyz'); // negative integer
```
<br>

## 11. `contains()` <a id="contains"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Determines if a string contains a given substring.

Parameters:
- `string $haystack` - The string to search within.
- `string $needle` - The substring to search for.

Returns:
- `bool` - True if substring exists, otherwise false.

Example:
```php
Str::contains('hello world', 'world'); // true
```
<br>

## 12. `crc32()` <a id="crc32"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Calculates the CRC32 hash of a string.

Parameter:
- `string $value` - The input string.

Returns:
- `int Returns` - The crc32 checksum of string as an integer.

Example:
```php
Str::crc32('hello'); // e.g., 907060870
```
<br>

## 13. `endsWith()` <a id="endswith"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Checks if a string ends with a given substring.

Parameters:
- `string $haystack` - The string to check.
- `string $needle` - The substring to check for.

Returns:
- `bool` - True if string ends with given substring, otherwise we return false.

Example:
```php
Str::endsWith('hello world', 'world'); // true
```
<br>

## 14. `excerpt()` <a id="excerpt"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Creates an excerpt around a phrase.

Parameters:
- `string $text` - The input string.
- `string $phrase` - The phrase to excerpt around.
- `int $radius` - The number of characters around the phrase.

Returns:
- `string` - The portion of str specified by the start and length parameters.

Example:
```php
Str::excerpt('This is a long sentence.', 'long', 5); // 'is a long sente'
```
<br>

## 15. `finish()` <a id="finish"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Ensures a string ends with a given value.

Parameters:
- `string $value` - The input string.
- `string $cap` - The ending string to append if missing.

Returns:
- `string` - The string with the desired ending.

Example:
```php
Str::finish('hello', '!'); // 'hello!'
```
<br>

## 16. `headline()` <a id="headline"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Converts a string to headline case.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The string in headline case.

Example:
```php
Str::headline('hello_world'); // 'Hello World'
```
<br>

## 17. `isAscii()` <a id="isascii"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Checks if the string contains only ASCII characters.

Parameter:
- `string $value` - The input string.

Returns:
- `bool` - True on success or false on failure.

Example:
```php
Str::isAscii('abc'); // true
Str::isAscii('ü'); // false
```
<br>

## 18. `isEmpty()` <a id="isempty"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Determines if a string is empty.

Parameter:
- `string $value` - The input string.

Returns:
- `bool` - True if empty string, otherwise false.

Example:
```php
Str::isEmpty(''); // true
Str::isEmpty(' '); // true
```
<br>

## 19. `isJson()` <a id="isjson"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Checks if a string is valid JSON.

Parameter:
- `string $value` - The input string.

Returns:
- `bool` - True if valid JSON, otherwise false.

Example:
```php
Str::isJson('{"key":"value"}'); // true
```
<br>

## 20. `isUuid()` <a id="isuuid"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Determines if the given string is a valid UUID.

Parameter:
- `string $value` - The input string.

Returns:
- `bool` - True if the string is a valid UUID, false otherwise.

Example:
```php
Str::isUuid('550e8400-e29b-41d4-a716-446655440000'); // true
```
<br>

## 21. `kebab()` <a id="kebab"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Converts a string to kebab-case.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The string in kebab-case.

Example:
```php
Str::kebab('Hello World'); // 'hello-world'
```
<br>

## 22. `lastPosition()` <a id="lastposition"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Finds the position of the last occurrence of a substring.

Parameters:
- `string $haystack` - The string to search in.
- `string $needle` - The substring to search for.

Returns:
- `int|false` - The position where the needle exists relative to the  beginning of the haystack string (independent of search direction or  offset). Also note that string positions start at 0, and not 1.  Returns FALSE if the needle was not found.
Example:

```php
Str::lastPosition('Hello World', 'o'); // 7
```
<br>

## 23. `lcfirst()` <a id="lcfirst"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Converts the first character of a string to lowercase.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The string with the first character converted to lowercase.

Example:
```php
Str::lcfirst('Hello'); // 'hello'
```
<br>

## 24. `length()` <a id="length"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns the length of a string.

Parameter:
- `string $value` - The input string.

Returns:
- `int` - The number of characters in string str having character encoding. A multi-byte character is counted as 1.

Example:
```php
Str::length('Hello'); // 5
```
<br>

## 25. `levenshtein()` <a id="levenshtein"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Calculates the Levenshtein distance between two strings.

Parameters:
- `string $string1` - The first string.
- `string $string2` - The second string.

Returns:
- `int` - This function returns the Levenshtein-Distance between the two argument strings or -1, if one of the argument strings is longer than the limit of 255 characters.

Example:
```php
Str::levenshtein('kitten', 'sitting'); // 3
```
<br>

## 26. `limit()` <a id="limit"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Limits the number of characters in a string.

Parameters:
- `string $value` - The input string.
- `int $limit` - Maximum number of characters.
- `string $end` - Ending to append if truncated.

Returns:
- `string` - A substring of original string with limit set on length.

Example:
```php
Str::limit('Hello World', 5); // 'Hello...'
```
<br>

## 27. `lower()` <a id="lower"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Converts a string to lowercase.

Parameter:
- `string $value` - The input string.

Return:
- `string` - The lower case string.

Example:
```php
Str::lower('Hello'); // 'hello'
```
<br>

## 28. `mask()` <a id="mask"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Masks portions of a string with a given character.

Parameters:
- `string $string` - The input string.
- `string $character` - The mask character.
- `int $start` - The starting position for masking.
- `int|null $length` - The number of characters to mask.

Returns:
- `string` - The masked string.

Example:
```php
Str::mask('1234567890', '*', 2, 5); // '12*****890'
```
<br>

## 29. `md5()` <a id="md5"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates the MD5 hash of a string.

Parameter:
- `string $value` - The input string.

Return:
- `string` - The MD5 has of a string.

Example:
```php
Str::md5('hello'); // '5d41402abc4b2a76b9719d911017c592'
```
<br>

## 30. `numberFormat()` <a id="numberformat"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Formats a number with grouped thousands.

Parameters:
- `float $number` - The number to format.
- `int $decimals` - Number of decimal points.
- `string $decimalSeparator` - Decimal separator.
- `string $thousandSeparator`-  Thousand separator.

Returns:
- `string` - A formatted version of number.

Example:
```php
Str::numberFormat(12345.678, 2); // '12,345.68'
```
<br>

## 31. `padLeft()` <a id="padleft"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Pads the left side of a string to a specified length with a given character.

Parameters:
- `string $value` - The input string.
- `int $length` - The desired total length after padding.
- `string $pad` - The padding character.

Returns:
- `string` - The string that is padded to the left with a given character.

Example:
```php
Str::padLeft('hello', 8, '_'); // '___hello'
```
<br>

## 32. `padRight()` <a id="padright"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Pads the right side of a string to a specified length with a given character.

Parameters:
- `string $value` - The input string.
- `int $length` - The desired total length after padding.
- `string $pad` - The padding character.

Returns:
- `string` - The string that is padded to the right with a given character.

Example:
```php
Str::padRight('Hello', 10, '-'); // 'Hello-----'
```
<br>

## 33. `pascal()` <a id="pascal"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Converts a string to PascalCase.

Parameter:
- `string $value` - The input string.

Return:
- `string` - The string in PascalCase.

Example:
```php
Str::pascal('hello world'); // 'HelloWorld'
```
<br>

## 34. `plural()` <a id="plural"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Pluralizes a given word based on the count.

Parameters:
- `string $word` - The word to pluralize.
- `int $count` - The number to determine singular or plural.

Returns:
- `string` - The Pluralized string.

Example:
```php
Str::plural('apple', 1); // 'apple'
Str::plural('apple', 2); // 'apples'
```
<br>

## 35. `position()` <a id="position"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Finds the position of the first occurrence of a substring.

Parameters:
- `string $haystack` - The string to search in.
- `string $needle` - The substring to search for.

Returns:
- `int|false` - Returns the position where the needle exists relative to the beginning of the haystack string (independent of search direction or offset). Also note that string positions start at 0, and not 1.  Returns FALSE if the needle was not found.

Example:
```php
Str::position('Hello World', 'World'); // 6
```
<br>

## 36. `random()` <a id="random"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates a random string of specified length.

Parameter:
- `int $length` - The desired length of the random string.

Returns:
- `string` - A random string of a specified length.

Example:
```php
Str::random(8); // e.g., '4f9d2c8a'
```
<br>

## 37. `repeat()` <a id="repeat"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Repeats a given string a specified number of times.

Parameter:
- `string $value` - The input string.
- `int $times` - Number of times to repeat.

Returns:
- `string` - The repeated string.

Example:
```php
Str::repeat('abc', 3); // 'abcabcabc'
```
<br>

## 38. `replace()` <a id="replace"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Replaces all occurrences of the search string(s) with the given replacement(s) in the subject string.

Parameters:
- `string|string[]` - $search The value(s) being searched for.
- `string|string[]` - $replace The replacement value(s).
- `string $subject` - The string being searched and replaced on.

Returns:
- `string` - A string with the replaced values.

Example:
```php
Str::replace('apple', 'orange', 'apple pie'); 
// 'orange pie'

Str::replace(['a', 'e'], ['A', 'E'], 'banana'); 
// 'bAnAnA'
```
<br>

## 39. `replaceArray()` <a id="replacearray"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Sequentially replaces placeholders with values from an array.

Parameters:
- `string $search` - The placeholder string to replace.
- `array $replace` - Array of replacement values.
- `string $subject` - The string to perform replacements on.

Returns:
- `string` - The placeholders replaced with values from an array.

Example:
```php
Str::replaceArray('?', ['one', 'two'], '? ?'); // 'one two'
```
<br>

## 40. `replaceFirst()` <a id="replacefirst"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Replaces the first occurrence of a substring.

Parameters:
- `string $search` - The substring to find.
- `string $replace` - The substring to replace with.
- `string $subject` - The string to perform replacement on.

Returns:
- `string` - The updated string.

Example:
```php
Str::replaceFirst('cat', 'dog', 'cat cat'); // 'cat dog'
```
<br>

## 41. `replaceLast()` <a id="replacelast"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Replaces the last occurrence of a substring.

Parameters:
- `string $search` - The substring to find.
- `string $replace` - The substring to replace with.
- `string $subject` - The string to perform replacement on.

Returns:
- `string` - The updated string.

Example:
```php
Str::replaceLast('apple', 'orange', 'apple pie, apple pie'); 
// 'apple pie, orange pie'
```
<br>

## 42. `replaceMultiple()` <a id="replacemultiple"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Replaces multiple substrings simultaneously.

Parameters:
- `array $replacements` - Associative array of replacements [search => replace].
- `string $subject` - The string to perform replacements on.

Returns:
- `string` - The updated string.

Example:
```php
Str::replaceMultiple(['cat' => 'dog', 'blue' => 'red'], 'cat and dog'); 
// 'cat dog'
```
<br>

## 43. `reverse()` <a id="reverse"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Reverses the given string.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The reversed string.

Example:
```php
Str::reverse('hello'); // 'olleh'
```
<br>

## 44. `sha1()` <a id="sha1"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns the SHA1 hash of a string.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The SHA1 hash of a string.

Example:
```php
Str::sha1('hello'); // 'f7ff9e8b7bb2e09b70935d20b8a76a62cbd30d2f'
```
<br>

## 45. `shuffle()` <a id="shuffle"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Randomly shuffles the characters in a string.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The shuffled string.

Example:
```php
Str::shuffle('hello'); // e.g., 'eholl'
```
<br>

## 46. `similarity()` <a id="similarity"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Calculates similarity percentage between two strings.

Parameters:
- `string $string1` - The first string.
- `string $string2` - The second string.

Returns:
- `int` - The number of matching chars in both strings.

Example:
```php
Str::similarity('hello', 'hallo'); // e.g., 80
```
<br>

## 47. `snake()` <a id="snake"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Converts a string to snake_case.

Parameters:
- `string $value` - The input string.
- `string $delimiter` - The delimiter used for snake casing.

Returns:
- `string` - The string in snake case.
Example:
```php
Str::snake('Hello World'); // 'hello_world'
```
<br>

## 48. `slug()` <a id="slug"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Creates a URL-friendly slug from a given string.

Parameter:
- `string $value` - The input string.
- `string $separator` - The separator used in the slug.

Returns:
- `string` - The string represented as a URL-friendly slug.

Example:
```php
Str::slug('Hello World!'); // 'hello-world'
```
<br>

## 49. `squish()` <a id="squish"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Removes excessive whitespace from a string.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The modified string.

Example:
```php
Str::squish('  Hello    World  '); // 'Hello World'
```
<br>

## 50. `startsWith()` <a id="startswith"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Determines if a string starts with a given substring.

Parameters:
- `string $haystack` - The string to search within.
- `string $needle` - The substring to check for.

Returns:
- `bool` - True if string starts with given substring, otherwise false.

Example:
```php
Str::startsWith('Hello World', 'Hello'); // true
```
<br>

## 51. `stripWhitespace()` <a id="stripwhitespace"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Removes all whitespace from a given string.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The string with all whitespace removed.

Example:
```php
Str::stripWhitespace('Hello World'); // 'HelloWorld'
```
<br>

## 52. `studly()` <a id="studly"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Converts a string to StudlyCase (PascalCase).

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The string in StudlyCase.

Example:
```php
Str::studly('hello_world'); // 'HelloWorld'
```
<br>

## 53. `substr()` <a id="substr"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Extracts a substring from a given string.

Parameter:
- `string $value` - The input string.
- `int $start` - The starting position.
- `int|null $length` - The number of characters to extract.

Returns:
- `string` - The portion of str specified by the start and length parameters.

Example:
```php
Str::substr('Hello World', 0, 5); // 'Hello'
```
<br>

## 54. `substrCount()` <a id="substrcount"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Counts the number of occurrences of a substring within a string.

Parameters:
- `string $haystack` - The input string.
- `string $needle` - The substring to count.

Returns:
- `int` - The number of occurrences of a substring.

Example:
```php
Str::substrCount('apple pie apple', 'apple'); // 2
```
<br>

## 55. `swapKeyValue()` <a id="swapkeyvalue"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Swaps keys with values in an array and returns a formatted string.

Parameter:
- `array $array` - The input array.

Returns:
- `string` - The swapped keys and values as a string.

Example:
```php
Str::swapKeyValue(['a' => 1, 'b' => 2]); // '1 => a, 2 => b'
```
<br>

## 56. `title()` <a id="title"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Converts a string to title case.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The string in title case.

Example:
```php
Str::title('hello world'); // 'Hello World'
```
<br>

## 57. `toArray()` <a id="toarray"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Splits a string into an array of characters.

Parameter:
- `string $value` - The input string.

Returns:
- `array` - The string as an array of characters.

Example:
```php
Str::toArray('Hello'); // ['H', 'e', 'l', 'l', 'o']
```
<br>

## 58. `ucfirst()` <a id="ucfirst"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Capitalizes the first character of a string.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The string with first character capitalized.

Example:
```php
Str::ucfirst('hello'); // 'Hello'
```
<br>

## 59. `ucwords()` <a id="ucwords"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Capitalizes the first letter of each word in the string, preserving the original casing of other characters.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The modified string where first letter of each word is capitalized.

Example:
```php
Str::ucwords('hello world');         // 'Hello World'
Str::ucwords('hELLO tHeRE frIEnd');  // 'HELLO THeRE FrIEnd'
```
<br>

## 60. `upper()` <a id="upper"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Converts a string to uppercase.

Parameter:
- `string $value` - The input string.

Returns:
- `string` - The string in all upper case characters.

Example:
```php
Str::upper('hello'); // 'HELLO'
```
<br>

## 61. `uuid()` <a id="uuid"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates a UUID (Universally Unique Identifier).

Returns:
- `string` - A UUID string.

Example:
```php
Str::uuid(); // '550e8400-e29b-41d4-a716-446655440000'
```
<br>

## 62. `wordCount()` <a id="wordcount"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Counts the number of words in a string.

Parameter:
- `string $value` - The input string.

Returns:
- `int` - The number of words in a string.

Example:
```php
Str::wordCount('Hello world'); // 2
```
<br>

## 63. `words()` <a id="words"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Limits a string to a certain number of words.

Parameters:
- `string $value` - The input string.
- `int $words` - Number of words to limit to.
- `string $end` - Ending to append if truncated.

Returns:
- `string` - The string with the limited number of words.

Example:
```php
Str::words('Hello world of PHP', 2); // 'Hello world...'
```
<br>

## 64. `wrap()` <a id="wrap"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Wraps a string with a given value.

Parameter:
- `string $value` - The input string.
- `string $wrapWith` - The wrapping string.

Returns:
- `string` - The string wrapped with a given value.

Example:
```php
Str::wrap('hello', '*'); // '*hello*'
```