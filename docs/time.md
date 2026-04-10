<h1 style="font-size: 50px; text-align: center;">DateTime</h1>

## Table of contents
1. [Overview](#overview)
2. [formatTime()](#format-time)
3. [nowPlusSeconds](#now-plus-seconds)
4. [timeAgo()](#time-ago)
5. [timeDifference()](#time-difference)
6. [timeStamps()](#time-stamps)
7. [toISO8601()](#to-iso-8601)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Supports ability to manipulate how time is displayed.  Most functions are wrappers for those found in the Carbon class.

<br>

## 2. `formatTime()` <a id="format-time"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns string that describes time.  The results can be set using constants, locale, and timezone.  

Parameters:
- `string $time` - String in format Y-m-d H:i:s A using UTC.
- `string $format` - Set format with a default of FORMAT_12_HOUR.
- `string $locale` - Set locale with 'en' as the default value.
- `string $timeZone` - Override default timezone with 'UTC' as default value.

Returns:
- `string` - The formatted time

<br>

The table below describes constants supported by the format parameter.

| Constant Name | Format_String | Example Output | Use Case |
|-------|-------|-------|-------|
| FORMAT_12_HOUR | 'Y-m-d h:i:s A' | 2025-03-09 03:30:45 PM | Standard 12-hour format with seconds |
| FORMAT_24_HOUR | 'Y-m-d H:i:s' | 2025-03-09 15:30:45 | Standard 24-hour format |
| FORMAT_HUMAN_READABLE | 'l, F j, Y g:i A' | Sunday, March 9, 2025 3:30 PM | Readable full date & time |
| FORMAT_DATE_ONLY | 'Y-m-d' | 2025-03-09 | ISO-style date (no time) |
| FORMAT_TIME_ONLY_12H | 'h:i A' | 03:30 PM | 12-hour time (no date) |
| FORMAT_TIME_ONLY_24H | 'H:i' | 15:30 | 24-hour time (no date) |
| FORMAT_FRIENDLY_DATE | 'F j, Y' | March 9, 2025 | Friendly date format |
| FORMAT_DAY_DATE | 'l, M j' | Sunday, Mar 9 | Weekday + short month |
| FORMAT_ISO_8601 | 'c' | 2025-03-09T15:30:45-05:00 | For APIs & timestamps |
| FORMAT_RFC_2822 | 'r' | Sun, 09 Mar 2025 15:30:45 -0500 | For email headers, logs |
| FORMAT_SQL_DATETIME | 'Y-m-d H:i:s' | 2025-03-09 15:30:45 | For database storage |

<br>

## 3. `nowPlusSeconds()` <a id="now-plus-seconds"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Adds second passed as parameter to current time.

Parameter:
- `int $seconds` - The number of seconds to add.

Returns:
- `string` - The time plus seconds passed as parameter.

<br>

## 4. `timeAgo()` <a id="time-ago"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Accepts UTC time in format Y-m-d H:i:s and returns a string describing how much time has elapsed.  

Parameters:
- `string $time` - String in format Y-m-d H:i:s using UTC.
- `string $locale` - Set locale with 'en' as the default value.
- `string $timeZone` - Override default timezone with 'UTC' as default value.
- `bool $short` - Set to true to show short form time.

Returns:
- `string` - The time represented using language describing time since last change.

Example:
```php
DateTime::timeAgo($user->updated_at, 'en', 'UTC', true);
```

Output: `21m`

<br>

## 5. `timeDifference()` <a id="time-difference"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Shows the difference between two times.  

Parameters:
- `string $startTime` - String in format Y-m-d H:i:s using UTC.
- `string $endTime` - String in format Y-m-d H:i:s using UTC.
- `string $timezone` - Override default timezone with 'UTC' as default value.

Returns:
- `string` - Show exact difference between two times.

Example:
```php
DateTimeHelper::timeDifference('2025-03-09 08:00:00', '2025-03-09 15:30:45');
```

Output: `7 hours before`

<br>

## 6. `timeStamps()` <a id="time-stamps"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generates a timestamp in the Y-m-d H:i:s format set to UTC time.

Returns:
- `string` - A timestamp in the format Y-m-d H:i:s UTC time.

<br>

## 7. `toISO8601()` <a id="to-iso-8601"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Converts time to ISO 8601 format.  

Parameters:
- `string $time` - String in format Y-m-d H:i:s using UTC.
- `string $timezone` - Override default timezone with 'UTC' as default value.

Returns:
- `string` - The time in ISO 8610 format.  Example output: `2025-03-09T15:30:45-05:00`