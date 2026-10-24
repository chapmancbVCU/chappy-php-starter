<h1 style="font-size: 50px; text-align: center;">Model Date Casting</h1>

## Table of contents
1. [Overview](#overview)
2. [Reading Dates with date()](#date)
3. [Declaring Casts](#declaring-casts)
4. [Cast Types](#cast-types)
5. [JSON and toArray()](#json-and-to-array)
6. [Customizing Serialization](#customizing-serialization)
7. [Saving Dates](#saving-dates)
8. [Common Mistakes](#common-mistakes)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Every model includes the `HasCasts` trait through the base `Core\Model` class.  It lets you:
- Read any date column as a Carbon instance in the application timezone.
- Control how date columns appear in JSON responses and React props.

Model properties are never changed.  `$user->created_at` always holds the string stored in the database, such as `2026-01-15 20:30:45`.  Loading, saving, and the [HasTimestamps](traits#has-timestamps) trait work exactly as before.  Casting happens only when you ask for it.

<br>

## 2. Reading Dates with `date()` <a id="date"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Returns an attribute as a Carbon instance in the application timezone.  It works on any attribute that holds a date, whether or not the attribute is declared in `$casts`.

Parameter:
- `string $attribute` - The property name, e.g. `'created_at'`.

Returns:
- `?CarbonInterface` - The date, or null when the attribute is unset, null, or an empty date such as `0000-00-00 00:00:00`.

Throws:
- `InvalidArgumentException` - If the attribute is cast to an unsupported type.

Example:
```php
$user = Users::findById(1);

$user->date('created_at')->diffForHumans();     // 2 hours ago
$user->date('created_at')->format('F j, Y');    // January 15, 2026
$user->date('created_at')->isToday();           // true
```

The result is a copy.  Changing it does not change the model:

```php
$user->date('created_at')->addYear();
echo $user->created_at;   // Still 2026-01-15 20:30:45
```

For columns that can be empty, use the nullsafe operator:

```php
$notification->date('read_at')?->diffForHumans() ?? 'Unread';
```

<br>

## 3. Declaring Casts <a id="declaring-casts"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Declare a `protected array $casts` property that maps column names to cast types:

```php
namespace App\Models;

use Core\Model;
use Core\Traits\HasTimestamps;

class Users extends Model {
    use HasTimestamps;

    /**
     * Columns converted when the model is serialized with toArray() or json_encode().
     * @var array<string, string>
     */
    protected array $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
```

The property must be `protected`.  A `public` property would appear in the model's JSON output.

Models created with the `make:model` command include a commented-out `$casts` example.

When the casts depend on logic, override the `casts()` method instead.  You can use both; entries from the method take priority.

```php
protected function casts(): array {
    return ['published_at' => 'immutable_datetime'];
}
```

<br>

## 4. Cast Types <a id="cast-types"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

| Cast | Use For | `date()` Returns | JSON Output |
|-------|-------|-------|-------|
| `datetime` | DATETIME columns stored in UTC | `Carbon` in `TIME_ZONE` | `2026-01-15T20:30:45Z` |
| `immutable_datetime` | Same as `datetime` | `CarbonImmutable` | `2026-01-15T20:30:45Z` |
| `date` | DATE columns, such as birthdays and due dates | `Carbon` at midnight | `1990-07-04` |
| `immutable_date` | Same as `date` | `CarbonImmutable` at midnight | `1990-07-04` |

Append `:format` to any type to choose the JSON format.  Custom formats are rendered in the application timezone:

```php
protected array $casts = [
    'starts_at' => 'datetime:Y-m-d H:i',   // "2026-01-15 15:30"
];
```

Attributes not listed in `$casts` are treated as `datetime` by `date()` and are left unchanged in JSON.

#### Calendar dates
A `date` cast represents a calendar date with no time or timezone.  The framework never moves it to a different day.  Reading `1990-07-04` as midnight UTC would display as July 3 anywhere west of UTC.  The `date` cast avoids this on both the PHP and JavaScript sides.

<br>

## 5. JSON and `toArray()` <a id="json-and-to-array"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Models implement `JsonSerializable`, so casts apply automatically wherever a model is converted to JSON:
- `json_encode($model)`
- `$this->jsonResponse($model)` in API controllers
- Props passed to `renderJsx()` in [React views](controllers_and_views)

```php
json_encode(Users::findById(1));
```

Output (other columns omitted):
```json
{"id":1,"username":"admin","created_at":"2026-01-15T20:30:45Z","updated_at":"2026-01-15T20:30:45Z"}
```

`toArray()` returns the same values as an array.  Use it when you build response arrays by hand:

```php
$dates = $user->toArray();

return [
    'username'   => $user->username,
    'created_at' => $dates['created_at'],
];
```

Both methods include only public properties, exactly as `json_encode()` did before casting existed.  A model without `$casts` produces the same JSON it always has.

<br>

## 6. Customizing Serialization <a id="customizing-serialization"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
To change the format of every `datetime` cast on a model that has no `:format` suffix, override `serializeDate()`:

```php
use Carbon\CarbonInterface;

protected function serializeDate(CarbonInterface $date): string {
    return $date->toImmutable()->utc()->format('Y-m-d\TH:i:s.v\Z');
}
```

The default, ISO 8601 in UTC with a `Z` suffix, is read the same way by every browser and by the [JavaScript datetime utilities](react_datetime).  Keep a timezone marker in any custom format so that JavaScript does not read the value as the browser's local time.

<br>

## 7. Saving Dates <a id="saving-dates"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Casting only affects reading and serialization.  When you assign a date, assign a UTC string in `Y-m-d H:i:s` format:

```php
use Core\Lib\Utilities\DateFactory;
use Core\Lib\Utilities\DateTime;

$event->starts_at = DateTime::timeStamps();     // Current time

$event->starts_at = DateFactory::now()
    ->addDays(7)
    ->utc()
    ->format(DateTime::FORMAT_SQL_DATETIME);    // A Carbon value
```

See [Storage and Display Rules](time#storage-and-display-rules) for the full set of rules.

<br>

## 8. Common Mistakes <a id="common-mistakes"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

#### Assigning a Carbon instance back to the property
```php
$acl->created_at = $acl->date('created_at');   // TypeError
```

`HasTimestamps` declares `created_at` and `updated_at` as strings, so this throws `Cannot assign Carbon\Carbon to property ... of type string`.  Use the Carbon instance directly, or store it in a separate variable.

#### Storing display text in a date property
```php
$acl->created_at = $acl->date('created_at')->diffForHumans();   // Avoid
```

This avoids the error, but the property now holds text like `3 hours ago`.  If the model is saved, that text is written to the database.  If the attribute is cast, `toArray()` reads it relative to the current time and returns the wrong date.  Format dates where you display them instead:

```php
// PHP view
<?= $acl->date('created_at')->diffForHumans() ?>
```

```jsx
// React view
{DateTime.timeAgo(acl.created_at)}
```

#### Calling `date()` on a plain object
`date()` exists only on models.  Rows returned by `DB` queries without a model class are `stdClass` objects.  Use `DateFactory::parse($row->created_at)` for those.