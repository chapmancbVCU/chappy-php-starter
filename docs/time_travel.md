<h1 style="font-size: 50px; text-align: center;">Time Travel in Tests</h1>

## Table of contents
1. [Overview](#overview)
2. [Setup](#setup)
3. [freezeTime()](#freeze-time)
4. [freezeSecond()](#freeze-second)
5. [travelTo()](#travel-to)
6. [travel()](#travel)
7. [travelBack()](#travel-back)
8. [Running Code at Another Time](#callbacks)
9. [Example](#example)
10. [JavaScript Tests](#javascript-tests)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `Core\Lib\Testing\InteractsWithTime` trait lets PHPUnit tests freeze the clock or move it to a specific moment.  Use it to test anything that depends on the current time, such as expirations, relative time text, or scheduled jobs.

Every helper on the [Date and Time](time) page gets the current time from `DateFactory`, so time travel affects all of them, along with any code that calls Carbon directly.

The real clock is restored automatically after each test.

<br>

## 2. Setup <a id="setup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Add the trait to your test class.  It works in both unit tests and feature tests:

```php
namespace Tests\Unit;

use Core\Lib\Testing\InteractsWithTime;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase {
    use InteractsWithTime;
}
```

You do not need a `tearDown()` method to reset the clock.  The trait restores it after every test using PHPUnit's `#[After]` attribute.

<br>

## 3. `freezeTime()` <a id="freeze-time"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Freezes time at the current moment.  Time stays frozen until `travelBack()` is called or the test ends.

Parameter:
- `?callable $callback` - Optional.  When provided, time is frozen only while the callback runs.  The callback receives the frozen instance.

Returns:
- `mixed` - The frozen `CarbonInterface` instance, or the callback's return value when a callback is provided.

```php
$frozen = $this->freezeTime();
// DateFactory::now() returns $frozen for the rest of the test
```

<br>

## 4. `freezeSecond()` <a id="freeze-second"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Same as `freezeTime()`, but drops microseconds.  Use it when comparing against values from the database, which do not store microseconds.

<br>

## 5. `travelTo()` <a id="travel-to"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Moves time to a specific moment.

Parameters:
- `DateTimeInterface|string|int $date` - The moment to travel to.  Strings are read as UTC, matching how dates are stored.  Integers are Unix timestamps.
- `?callable $callback` - Optional.  When provided, time is moved only while the callback runs.

Returns:
- `mixed` - The callback's return value, or null without a callback.

```php
$this->travelTo('2026-01-15 20:30:45');

DateTime::timeStamps();           // 2026-01-15 20:30:45
DateFactory::now()->format('H:i');   // 15:30 with TIME_ZONE=America/New_York
```

<br>

## 6. `travel()` <a id="travel"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Moves time relative to the current time.  If time is already frozen, it moves from the frozen time.  Use negative values to move backward.

Parameter:
- `int $value` - The amount of time to move.

Returns:
- `Wormhole` - An object with a method for each unit.

Available units, in plural and singular forms:

| Plural | Singular |
|-------|-------|
| `seconds()` | `second()` |
| `minutes()` | `minute()` |
| `hours()` | `hour()` |
| `days()` | `day()` |
| `weeks()` | `week()` |
| `months()` | `month()` |
| `years()` | `year()` |

Each unit method accepts an optional callback and returns the new `CarbonInterface` instance, or the callback's return value when a callback is provided.

```php
$this->travelTo('2026-01-15 20:30:45');

$this->travel(5)->minutes();     // 2026-01-15 20:35:45
$this->travel(-2)->days();       // 2026-01-13 20:35:45
$this->travel(1)->hour();        // 2026-01-13 21:35:45
```

Months and years never overflow.  January 31 plus one month is February 28 (or 29), not March 3.  February 29 plus one year is February 28.

<br>

## 7. `travelBack()` <a id="travel-back"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Restores the real clock before the test ends.

Returns:
- `CarbonInterface` - The current real time.

<br>

## 8. Running Code at Another Time <a id="callbacks"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Pass a callback to `freezeTime()`, `travelTo()`, or any `travel()` unit to change time only while the callback runs.  Afterward, the clock returns to its previous state, even if the callback throws an exception.

If time was already frozen, it returns to the frozen time, not the real clock:

```php
$this->travelTo('2026-01-15 20:30:45');

$later = $this->travel(1)->week(fn() => DateTime::timeStamps());

$later;                    // 2026-01-22 20:30:45
DateTime::timeStamps();    // 2026-01-15 20:30:45
```

<br>

## 9. Example <a id="example"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

```php
namespace Tests\Unit;

use Core\Lib\Testing\InteractsWithTime;
use Core\Lib\Utilities\DateTime;
use PHPUnit\Framework\TestCase;

class TimeAgoTest extends TestCase {
    use InteractsWithTime;

    public function test_time_ago_after_three_hours(): void {
        $this->travelTo('2026-01-15 20:30:45');
        $this->travel(3)->hours();

        $this->assertSame('3 hours ago', DateTime::timeAgo('2026-01-15 20:30:45'));
    }
}
```

<br>

## 10. JavaScript Tests <a id="javascript-tests"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The [JavaScript datetime utilities](react_datetime) read the current time from `Date`, so Vitest's fake timers freeze them the same way:

```js
import { vi, beforeEach, afterEach } from 'vitest';

beforeEach(() => {
    vi.useFakeTimers();
    vi.setSystemTime(new Date('2026-01-15T20:30:45Z'));
});

afterEach(() => {
    vi.useRealTimers();
});
```

See [Vitest](vitest) for more about JavaScript tests.