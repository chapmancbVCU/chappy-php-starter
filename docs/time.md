<h1 style="font-size: 50px; text-align: center;">Date and Time</h1>

## Table of contents
1. [Overview](#overview)
2. [Storage and Display Rules](#storage-and-display-rules)
3. [Configuration](#configuration)
4. [DateFactory](#date-factory)
5. [DateTime Format Constants](#format-constants)
6. [formatTime()](#format-time)
7. [now()](#now)
8. [nowPlusSeconds()](#now-plus-seconds)
9. [timeAgo()](#time-ago)
10. [timeDifference()](#time-difference)
11. [timeStamps()](#time-stamps)
12. [today()](#today)
13. [toISO8601()](#to-iso-8601)
14. [Related Pages](#related-pages)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Chappy.php handles dates with two classes in the `Core\Lib\Utilities` namespace.  Both are built on [Carbon](https://carbon.nesbot.com/docs/).

- `DateFactory` creates every date object in the framework.  It applies your application's timezone, locale, and choice of mutable or immutable Carbon in one place.
- `DateTime` provides static helpers that return formatted strings for views, logs, and database values.

Because all dates are created through `DateFactory`, freezing time in a test freezes every helper on this page.  See [Time Travel in Tests](time_travel) for details.

<br>

## 2. Storage and Display Rules <a id="storage-and-display-rules"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Dates move through three stages.  Each stage has one format.

| Stage | Format | Example | Produced by |
|-------|-------|-------|-------|
| Database | `Y-m-d H:i:s` in UTC | `2026-01-15 20:30:45` | `DateTime::timeStamps()`, `HasTimestamps` |
| JSON / React props | ISO 8601 in UTC with a `Z` suffix | `2026-01-15T20:30:45Z` | [Model date casting](date_casting) |
| Display | Any format, in the application timezone | `Thursday, January 15, 2026 3:30 PM` | `DateTime` helpers, [JavaScript datetime utilities](react_datetime) |

Follow these rules to keep the stages consistent:
- Always store UTC in `Y-m-d H:i:s` format.  Use `DateTime::timeStamps()` for the current time.
- Do not store ISO 8601 strings such as `2026-01-15T20:30:45.000000Z`.  SQLite compares dates as text, so mixed formats sort in the wrong order and break range queries like `created_at < ?`.
- Convert to the application timezone only when displaying a value.

To store a value from another source, such as a fixture file or an API response, convert it first:

```php
use Core\Lib\Utilities\DateFactory;
use Core\Lib\Utilities\DateTime;

$stored = DateFactory::parse('2026-01-15T20:30:45.000000Z')
    ->utc()
    ->format(DateTime::FORMAT_SQL_DATETIME);
// 2026-01-15 20:30:45
```

<br>

## 3. Configuration <a id="configuration"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Set the following values in your `.env` file:

| Variable | Default | Description |
|-------|-------|-------|
| `TIME_ZONE` | `UTC` | Timezone used to display dates, e.g. `America/New_York`. |
| `LOCALE` | `en` | Language for month names, weekday names, and relative time. |
| `DATE_CLASS` | `mutable` | Set to `immutable` to make `DateFactory` return `CarbonImmutable` instances.  Takes effect when `DateFactory::boot()` is called. |
| `VITE_TIME_ZONE` | Browser timezone | Timezone used by the JavaScript datetime utilities. |
| `VITE_LOCALE` | `en` | Locale used by the JavaScript datetime utilities. |

Keep the Vite values in sync with their PHP counterparts so PHP and React views show the same times:

```ini
TIME_ZONE='America/New_York'
LOCALE='en'

VITE_TIME_ZONE=${TIME_ZONE}
VITE_LOCALE=${LOCALE}
```

<br>

## 4. DateFactory <a id="date-factory"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Use `DateFactory` whenever you need a Carbon instance rather than a formatted string.

```php
use Core\Lib\Utilities\DateFactory;

$now = DateFactory::now();                     // Current time in TIME_ZONE
$deadline = DateFactory::now()->addDays(7);
$created = DateFactory::parse($user->created_at);  // UTC string to TIME_ZONE
```

#### A. `now()`
Returns the current time.

Parameter:
- `?string $timezone` - The timezone to use.  Falls back to `TIME_ZONE` when null.

Returns:
- `CarbonInterface` - The current time.

#### B. `today()`
Returns midnight of the current day.

Parameter:
- `?string $timezone` - The timezone to use.  Falls back to `TIME_ZONE` when null.

Returns:
- `CarbonInterface` - Midnight of the current day.

#### C. `parse()`
Converts a value into a Carbon instance in the application timezone.  This is the single conversion point used throughout the framework.

Parameters:
- `mixed $value` - A date string, an integer Unix timestamp (always treated as UTC), or a `DateTimeInterface` instance.
- `string $fromTimezone` - The timezone a date string is stored in.  The default is `'UTC'`.  Strings that include an offset or a `Z` suffix ignore this value.

Returns:
- `CarbonInterface` - The date in the application timezone.

Example:
```php
DateFactory::parse('2026-01-15 20:30:45')->format('Y-m-d H:i:s T');
DateFactory::parse(1768509045)->format('Y-m-d H:i:s T');
```

Output for both: `2026-01-15 15:30:45 EST`

#### D. `use()`
Sets the Carbon class used for all new instances.  Call this once during bootstrap.

Parameter:
- `string $class` - `Carbon::class`, `CarbonImmutable::class`, or a subclass of either.

Throws:
- `InvalidArgumentException` - If the class does not implement `CarbonInterface`.

```php
use Carbon\CarbonImmutable;

DateFactory::use(CarbonImmutable::class);
```

With immutable dates, methods such as `addDay()` return a new instance instead of changing the original.  This prevents bugs where `$start->addDay()` silently changes `$start`.

#### E. `boot()`
Reads `DATE_CLASS` from your `.env` file and calls `use()` with the matching class.  Accepts `immutable` or `mutable`.

#### F. `getClass()`
Returns the Carbon class currently in use.

#### G. `timezone()`
Returns the application timezone from `TIME_ZONE`, or `'UTC'` if it is not set.

<br>

## 5. DateTime Format Constants <a id="format-constants"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `$format` parameter of the `DateTime` helpers accepts any [PHP date format string](https://www.php.net/manual/en/datetime.format.php) or one of the constants below.  The same constants are available in React through the [JavaScript datetime utilities](react_datetime).

Example output is for `2026-01-15 20:30:45` UTC displayed in `America/New_York`.

| Constant Name | Format String | Example Output | Use Case |
|-------|-------|-------|-------|
| FORMAT_12_HOUR | 'Y-m-d h:i:s A' | 2026-01-15 03:30:45 PM | Standard 12-hour format with seconds |
| FORMAT_24_HOUR | 'Y-m-d H:i:s' | 2026-01-15 15:30:45 | Standard 24-hour format |
| FORMAT_HUMAN_READABLE | 'l, F j, Y g:i A' | Thursday, January 15, 2026 3:30 PM | Readable full date & time |
| FORMAT_DATE_ONLY | 'Y-m-d' | 2026-01-15 | ISO-style date (no time) |
| FORMAT_TIME_ONLY_12H | 'h:i A' | 03:30 PM | 12-hour time (no date) |
| FORMAT_TIME_ONLY_24H | 'H:i' | 15:30 | 24-hour time (no date) |
| FORMAT_FRIENDLY_DATE | 'F j, Y' | January 15, 2026 | Friendly date format |
| FORMAT_DAY_DATE | 'l, M j' | Thursday, Jan 15 | Weekday + short month |
| FORMAT_ISO_8601 | 'c' | 2026-01-15T15:30:45-05:00 | For APIs & timestamps |
| FORMAT_RFC_2822 | 'r' | Thu, 15 Jan 2026 15:30:45 -0500 | For email headers, logs |
| FORMAT_SQL_DATETIME | 'Y-m-d H:i:s' | 2026-01-15 20:30:45 | Database storage (see note) |

**Note:** `FORMAT_SQL_DATETIME` is the storage format, and stored values must be UTC.  Use it with `DateTime::timeStamps()` or a UTC Carbon instance.  Passing it to `formatTime()` produces the application timezone, which is correct for display but not for storage.

<br>

## 6. `formatTime()` <a id="format-time"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns a formatted string in the application timezone.

Parameters:
- `string|DateTimeInterface $time` - A string in `Y-m-d H:i:s` format, or a date object.  Date objects keep their own timezone and are not shifted again.
- `string $format` - The format to use.  The default is `FORMAT_12_HOUR`.
- `string $locale` - The locale for month and weekday names.  The default is `'en'`.
- `string $timezone` - The timezone a string `$time` is stored in.  The default is `'UTC'`.  Ignored for date objects.

Returns:
- `string` - The formatted time.

Example output is for `2026-01-15 20:30:45.123` UTC displayed in `America/New_York` with the `en` locale.

| Character | Description | Example Output |
|-------|-------|-------|
| **Day** | | |
| `d` | Day of the month, 2 digits with leading zero | `15` (`01` to `31`) |
| `D` | Short day name, translated by locale | `Thu` |
| `j` | Day of the month without leading zero | `15` (`1` to `31`) |
| `l` | Full day name, translated by locale | `Thursday` |
| `N` | ISO 8601 day of the week, Monday = 1 | `4` (`1` to `7`) |
| `S` | English ordinal suffix for the day, use with `j` | `th` (`st`, `nd`, `rd`, `th`) |
| `w` | Day of the week, Sunday = 0 | `4` (`0` to `6`) |
| `z` | Day of the year, starting from 0 | `14` (`0` to `365`) |
| **Month** | | |
| `F` | Full month name, translated by locale | `January` |
| `M` | Short month name, translated by locale | `Jan` |
| `m` | Month number with leading zero | `01` (`01` to `12`) |
| `n` | Month number without leading zero | `1` (`1` to `12`) |
| `t` | Number of days in the month | `31` (`28` to `31`) |
| **Year** | | |
| `L` | Leap year flag | `0` (`1` if leap year) |
| `Y` | Full year | `2026` |
| `y` | 2-digit year | `26` |
| **Time** | | |
| `a` | Lowercase meridiem, translated by locale | `pm` |
| `A` | Uppercase meridiem, translated by locale | `PM` |
| `g` | 12-hour format without leading zero | `3` (`1` to `12`) |
| `G` | 24-hour format without leading zero | `15` (`0` to `23`) |
| `h` | 12-hour format with leading zero | `03` (`01` to `12`) |
| `H` | 24-hour format with leading zero | `15` (`00` to `23`) |
| `i` | Minutes with leading zero | `30` (`00` to `59`) |
| `s` | Seconds with leading zero | `45` (`00` to `59`) |
| `u` | Microseconds | `123000` |
| `v` | Milliseconds | `123` |
| **Timezone** | | |
| `e` | Timezone identifier | `America/New_York` |
| `T` | Timezone abbreviation | `EST` (`EDT` during daylight saving time) |
| `P` | UTC offset with colon | `-05:00` |
| `p` | Same as `P`, but returns `Z` for UTC | `-05:00` (`Z` in UTC) |
| `O` | UTC offset without colon | `-0500` |
| **Full Date/Time** | | |
| `c` | ISO 8601 date | `2026-01-15T15:30:45-05:00` |
| `r` | RFC 2822 date | `Thu, 15 Jan 2026 15:30:45 -0500` |
| `U` | Seconds since the Unix epoch (January 1, 1970 UTC) | `1768509045` |

To print one of these characters literally, escape it with a backslash.  For example, `'\T\o\d\a\y \i\s l'` outputs `Today is Thursday`.  In JavaScript strings, double the backslash: `'\\T\\o\\d\\a\\y \\i\\s l'`.

Example:
```php
DateTime::formatTime('2026-01-15 20:30:45', DateTime::FORMAT_HUMAN_READABLE);
DateTime::formatTime('2026-01-15 20:30:45', 'l j F Y', 'fr');
```

Output:
```
Thursday, January 15, 2026 3:30 PM
jeudi 15 janvier 2026
```

<br>

## 7. `now()` <a id="now"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns the current time as a formatted string.

Parameters:
- `?string $timezone` - The timezone to use, e.g. `'Europe/Berlin'`.  Falls back to `TIME_ZONE` when null.
- `?string $format` - The format to use.  Falls back to `FORMAT_12_HOUR` when null.
- `?string $locale` - The locale to use.  Falls back to `LOCALE` when null.

Returns:
- `string` - The current time.

Example:
```php
DateTime::now();
DateTime::now('Europe/Berlin', 'H:i');
```

Output when it is 20:30:45 UTC:
```
2026-01-15 03:30:45 PM
21:30
```

<br>

## 8. `nowPlusSeconds()` <a id="now-plus-seconds"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Adds seconds to the current UTC time.  Useful for expiration times stored in the database.

Parameter:
- `int $seconds` - The number of seconds to add.

Returns:
- `string` - The result in `Y-m-d H:i:s` format, in UTC.

Example:
```php
DateTime::nowPlusSeconds(3600);
```

Output when it is 20:30:45 UTC: `2026-01-15 21:30:45`

<br>

## 9. `timeAgo()` <a id="time-ago"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns a string describing how much time has passed.

Parameters:
- `string|DateTimeInterface $time` - A string in `Y-m-d H:i:s` format, or a date object.
- `string $locale` - The locale to use.  The default is `'en'`.
- `string $timezone` - The timezone a string `$time` is stored in.  The default is `'UTC'`.  Ignored for date objects.
- `bool $short` - Set to true for the short form.

Returns:
- `string` - The elapsed time, such as `2 hours ago`.

Example:
```php
DateTime::timeAgo($user->updated_at);
DateTime::timeAgo($user->updated_at, 'en', 'UTC', true);
DateTime::timeAgo($user->updated_at, 'fr');
```

Output:
```
2 hours ago
21m ago
il y a 2 heures
```

On a model, `$user->date('updated_at')->diffForHumans()` gives the same result.  See [Model Date Casting](date_casting).

<br>

## 10. `timeDifference()` <a id="time-difference"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Describes the difference between two times.

Parameters:
- `string|DateTimeInterface $startTime` - A string in `Y-m-d H:i:s` format, or a date object.
- `string|DateTimeInterface $endTime` - A string in `Y-m-d H:i:s` format, or a date object.
- `string $timezone` - The timezone string values are stored in.  The default is `'UTC'`.

Returns:
- `string` - The difference between the two times.

Example:
```php
DateTime::timeDifference('2025-03-09 08:00:00', '2025-03-09 15:30:45');
```

Output: `7 hours before`

<br>

## 11. `timeStamps()` <a id="time-stamps"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns the current time in the storage format.  Use this whenever you write a timestamp to the database.

Returns:
- `string` - The current time in `Y-m-d H:i:s` format, in UTC.

Example:
```php
$job->reserved_at = DateTime::timeStamps();
```

Output when it is 20:30:45 UTC: `2026-01-15 20:30:45`

<br>

## 12. `today()` <a id="today"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns midnight of the current day as a formatted string.  The date depends on the timezone: at 23:30 UTC it is already the next day in Tokyo.

Parameters:
- `?string $timezone` - The timezone to use.  Falls back to `TIME_ZONE` when null.
- `?string $format` - The format to use.  Falls back to `FORMAT_12_HOUR` when null.
- `?string $locale` - The locale to use.  Falls back to `LOCALE` when null.

Returns:
- `string` - Midnight of the current day.

Example:
```php
DateTime::today();
DateTime::today(null, DateTime::FORMAT_FRIENDLY_DATE);
```

Output:
```
2026-01-15 12:00:00 AM
January 15, 2026
```

<br>

## 13. `toISO8601()` <a id="to-iso-8601"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Converts a time to ISO 8601 format with an offset.

Parameters:
- `string|DateTimeInterface $time` - A string in `Y-m-d H:i:s` format, or a date object.
- `string $timezone` - The timezone a string `$time` is stored in.  The default is `'UTC'`.  Ignored for date objects.

Returns:
- `string` - The time in ISO 8601 format.

The offset in the result comes from the input.  A UTC string keeps a `+00:00` offset, while a date object keeps its own offset:

```php
DateTime::toISO8601('2026-01-15 20:30:45');
DateTime::toISO8601(DateFactory::parse('2026-01-15 20:30:45'));
```

Output:
```
2026-01-15T20:30:45+00:00
2026-01-15T15:30:45-05:00
```

<br>

## 14. Related Pages <a id="related-pages"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- [Model Date Casting](date_casting) - Read model columns as Carbon instances and control JSON output.
- [JavaScript Datetime Utilities](react_datetime) - The same helpers and constants for React views.
- [Time Travel in Tests](time_travel) - Freeze and move time in PHPUnit tests.