<h1 style="font-size: 50px; text-align: center;">JavaScript Datetime Utilities</h1>

## Table of contents
1. [Overview](#overview)
2. [Setup](#setup)
3. [Accepted Date Values](#accepted-values)
4. [formatTime()](#format-time)
5. [timeAgo()](#time-ago)
6. [timeDifference()](#time-difference)
7. [now() and today()](#now-and-today)
8. [timeStamps() and toISO8601()](#time-stamps-and-iso)
9. [parseDate()](#parse-date)
10. [formatPhp()](#format-php)
11. [Defaults](#defaults)
12. [Example View](#example-view)
13. [Differences from PHP](#differences-from-php)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The datetime utilities bring the PHP [DateTime](time) helpers to React views.  The same function names, format strings, and `FORMAT_*` constants work on both sides, so a date looks the same whether PHP or React renders it.

The utilities use the browser's built-in `Intl` APIs and have no dependencies.

<br>

## 2. Setup <a id="setup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Import the `DateTime` namespace, or individual functions, from `@chappy/utils`:

```jsx
import { DateTime } from '@chappy/utils';
import { formatTime, timeAgo } from '@chappy/utils';
```

Add these values to your `.env` file so React uses the same timezone and locale as PHP:

```ini
VITE_TIME_ZONE=${TIME_ZONE}
VITE_LOCALE=${LOCALE}
```

Without `VITE_TIME_ZONE`, dates display in each visitor's own timezone.

<br>

## 3. Accepted Date Values <a id="accepted-values"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Every function that takes a date accepts the formats PHP sends.  Each one is read the same way the PHP side reads it:

| Value | Example | Read As |
|-------|-------|-------|
| ISO 8601 with `Z` or an offset | `2026-01-15T20:30:45Z` | That exact moment.  This is what [model date casting](date_casting) sends. |
| Database format | `2026-01-15 20:30:45` | UTC, the same as PHP. |
| Date only | `1990-07-04` | That calendar date, never shifted to another day. |
| Number | `1768509045` | A Unix timestamp in seconds, the same as PHP. |
| `Date` object | `new Date()` | That moment. |

Passing a database-format string to `new Date()` reads it as the browser's local time, and passing a date-only string reads it as midnight UTC.  Both display the wrong value in most timezones.  These utilities avoid both problems, so pass dates from PHP to them directly.

Empty values (`null`, `undefined`, `''`, and `0000-00-00 00:00:00`) are treated as no date.  Values that cannot be read unambiguously, such as `03/15/2026`, throw a `RangeError`.

<br>

## 4. `formatTime()` <a id="format-time"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Formats a date for display.  Mirrors `DateTime::formatTime()`.

Parameters:
- `{Date|string|number} value` - The date.  See [Accepted Date Values](#accepted-values).
- `{string} format` - A PHP format string or a `FORMAT_*` constant.  The default is `FORMAT_12_HOUR`.
- `{object} options` - Optional `{ timeZone, locale, fromTimeZone }`.

Returns:
- `{string}` - The formatted date, or an empty string for empty values.

Example:
```jsx
DateTime.formatTime(user.created_at, DateTime.FORMAT_HUMAN_READABLE);
DateTime.formatTime(user.created_at, 'H:i', { timeZone: 'Europe/Berlin' });
DateTime.formatTime(user.created_at, 'l j F Y', { locale: 'fr' });
```

Output for `2026-01-15T20:30:45Z` with `VITE_TIME_ZONE=America/New_York`:
```
Thursday, January 15, 2026 3:30 PM
21:30
jeudi 15 janvier 2026
```

All `FORMAT_*` constants from the [Date and Time](time#format-constants) page are available, either on the `DateTime` namespace or as named exports.

<br>

## 5. `timeAgo()` <a id="time-ago"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Describes how long ago a date was.  Mirrors `DateTime::timeAgo()`.

Parameters:
- `{Date|string|number} value` - The date.
- `{object} options` - Optional `{ locale, short, fromTimeZone }`.  Set `short` to `true` for the compact form.

Returns:
- `{string}` - The elapsed time.

Example:
```jsx
DateTime.timeAgo(acl.created_at);
DateTime.timeAgo(acl.created_at, { short: true });
DateTime.timeAgo(acl.created_at, { locale: 'fr' });
```

Output for a date two hours ago:
```
2 hours ago
2h ago
il y a 2 heures
```

Like Carbon, the result truncates instead of rounding (1 hour 59 minutes is `1 hour ago`) and uses calendar months and years.

<br>

## 6. `timeDifference()` <a id="time-difference"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Describes the difference between two dates.  Mirrors `DateTime::timeDifference()`.

Parameters:
- `{Date|string|number} start` - The first date.
- `{Date|string|number} end` - The second date.
- `{object} options` - Optional `{ locale, fromTimeZone }`.

Returns:
- `{string}` - The difference, such as `7 hours before`.

```jsx
DateTime.timeDifference('2025-03-09 08:00:00', '2025-03-09 15:30:45');   // 7 hours before
```

<br>

## 7. `now()` and `today()` <a id="now-and-today"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Return the current time, or midnight of the current day, as a formatted string.  Mirror `DateTime::now()` and `DateTime::today()`.

Parameters:
- `{string} format` - The default is `FORMAT_12_HOUR`.
- `{object} options` - Optional `{ timeZone, locale }`.

```jsx
DateTime.now();                                         // 2026-01-15 03:30:45 PM
DateTime.now('H:i', { timeZone: 'Europe/Berlin' });     // 21:30
DateTime.today(DateTime.FORMAT_FRIENDLY_DATE);          // January 15, 2026
```

<br>

## 8. `timeStamps()` and `toISO8601()` <a id="time-stamps-and-iso"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
`timeStamps()` returns the current time in the database format, in UTC.  Mirrors `DateTime::timeStamps()`.

`toISO8601(value, options)` returns ISO 8601 with the offset of the display timezone.

```jsx
DateTime.timeStamps();                     // 2026-01-15 20:30:45
DateTime.toISO8601(user.created_at);       // 2026-01-15T15:30:45-05:00
```

<br>

## 9. `parseDate()` <a id="parse-date"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Converts a value into a JavaScript `Date` using the rules in [Accepted Date Values](#accepted-values).  Use it instead of `new Date()` whenever a value comes from PHP.

Parameters:
- `{Date|string|number} value` - The value to convert.
- `{object} options` - Optional.  `timeZone` sets the zone date-only strings are anchored in, and `fromTimeZone` sets the zone of database-format strings (the default is `'UTC'`).

Returns:
- `{Date|null}` - The date, or null for empty values.

Throws:
- `RangeError` - If the value cannot be read.

```jsx
const created = DateTime.parseDate(user.created_at);
const isRecent = Date.now() - created.getTime() < 24 * 60 * 60 * 1000;
```

<br>

## 10. `formatPhp()` <a id="format-php"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Formats a `Date` object using PHP format characters.  `formatTime()` uses it internally; call it directly when you already have a `Date`.

Parameters:
- `{Date} date` - The date to format.
- `{string} format` - A PHP format string.  Escape literal characters with a backslash.
- `{object} options` - Optional `{ timeZone, locale }`.

Supported format characters: `d D j l N S w z F M m n t L Y y a A g G h H i s u v e T P p O c r U`

```jsx
DateTime.formatPhp(new Date(), '\\T\\o\\d\\a\\y \\i\\s l');   // Today is Thursday
```

<br>

## 11. Defaults <a id="defaults"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The default timezone comes from `VITE_TIME_ZONE`, falling back to the browser's timezone.  The default locale comes from `VITE_LOCALE`, falling back to `'en'`.

`setDateDefaults({ timeZone, locale })` changes the defaults at runtime, for example to show times in the visitor's own timezone.  It throws a `RangeError` for an invalid timezone.  `getDateDefaults()` returns the current values.

```jsx
import { setDateDefaults } from '@chappy/utils';

setDateDefaults({ timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone });
```

<br>

## 12. Example View <a id="example-view"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Show relative time in the table and the full date on hover:

```jsx
import { DateTime } from '@chappy/utils';

function AclRow({ acl }) {
    return (
        <tr>
            <td>{acl.acl}</td>
            <td title={DateTime.formatTime(acl.created_at, DateTime.FORMAT_HUMAN_READABLE)}>
                {DateTime.timeAgo(acl.created_at)}
            </td>
        </tr>
    );
}
```

The controller passes models straight through.  Add [`$casts`](date_casting) to the model so dates arrive in ISO 8601:

```php
$this->view->renderJsx('admindashboard.ManageAcls', [
    'usedAcls' => ACLService::usedACLs(),
]);
```

<br>

## 13. Differences from PHP <a id="differences-from-php"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
English output matches the PHP helpers exactly.  A few differences remain because Carbon and the browser use different translation data:

- **Options objects.** JavaScript functions take an options object, such as `{ timeZone, locale }`, instead of PHP's positional parameters.
- **Future times.** `timeAgo()` says `in 2 hours` where Carbon says `2 hours from now`.
- **Abbreviations in other languages.** Short month and weekday names, and the short form of `timeAgo()`, can differ slightly.  For example, German `So` instead of `So.`, or Spanish `mar` instead of `mar.`.  Full names and the long form of `timeAgo()` match.
- **Ordinal suffix.** The `S` format character always uses English suffixes (`st`, `nd`, `rd`, `th`).
- **Timezone abbreviations.** The `T` format character uses the browser's names, so some zones show an offset such as `GMT+1` instead of `CET`.
- **timeDifference().** The amount is translated, but the `before` and `after` suffix is always English.