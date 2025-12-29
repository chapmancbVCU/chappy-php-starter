<h1 style="font-size: 50px; text-align: center;">Notification</h1>

## Table of contents
1. [Overview](#overview)
2. [Channel Registry](#channel-registry)
3. [Channels & Payloads](#channels-and-payloads)
    * A. [Database Channel](#database-channel)
    * B. [Log Channel](#log-channel)
    * C. [Mail Channel](#mail-channel)
4. [Writing a Notification](#writing-a-notification)
5. [Making a Model Notifiable](#making-model-notifiable)
6. [Events and Listeners](#events-and-listeners)
7. [CLI Commands](#cli-commands)
8. [Testing](#testing)
9. [Notifications Model](#model)
    * A. [Fields](#fields)
    * B. [Common Queries](#common-queries)
10. [Troubleshooting](#troubleshooting)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This guide shows you how to register channels, write notifications, send them from code, and exercise everything from the CLI.  

<br>

## 2. Channels Registry <a id="channels-registry"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Channel registry is run automatically.  Here is an overview of what happens.
- Use `ChannelRegistry::register('log', LogChannel::class)` to add/override channels.
- `ChannelRegistry::resolve('log')` returns a channel driver.
- `ChannelRegistry::has('log')` to check registration.
- Registry stores names as lowercase.

<br>

## 3. Channels & Payloads <a id="channels-and-payloads"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
We currently support 3 channels for notifications
- Database
- E-mail
- Log

<br>

### A. Database Channel <a id="database-channel"></a>
- Persists records into `notifications` table via `Core\Models\Notifications`.
- Expects payload from `Notification::toDatabase()` (array).
- Requires `$notifiable->id`.

To display information from this driver to a user in flash messages you can call the `\Core\Services\NotificationService::flashUnreadNotifications` function.

<br>

### B. Log Channel <a id="log-channel"></a>
- Writes a structured JSON entry via Logger::log().
- Reads:
    - `message` (string or any JSON-encodable)
    - `level` (default `info`)
    - `_meta/meta` (array)
    - all other keys treated as “data” fields.
- Falls back to `Notification::toLog()` or `data['message']` or a synthesized message.

<br>

### C. Mail Channel <a id="mail-channel"></a>

Supports three payload shapes from `toMail()`:

#### 1. Template mode (preferred for templates)

    ```php
    return [
    'subject'      => 'Welcome!',
    'template'     => 'welcome_user',
    'data'         => ['user' => $user],
    // optional:
    'layout'       => 'default',
    'attachments'  => [...],
    'layoutPath'   => null,
    'templatePath' => null,
    'styles'       => 'default',
    'stylesPath'   => null,
    ];
    ```

<br>

#### 2. Raw HTML (with optional text fallback)

```php
return [
  'subject'     => 'Subject',
  'html'        => '<p>Hello</p>',
  'text'        => 'Hello',           // optional; triggers sendWithText()
  'attachments' => [...],             // optional
];
```

<br>

#### 3. Custom mailer

```php
return [
  'mailer'      => \Core\Lib\Mail\WelcomeMailer::class,
  // optional overrides used by buildAndSend():
  'layout'      => null,
  'attachments' => [],
  'layoutPath'  => null,
  'templatePath'=> null,
  'styles'      => null,
  'stylesPath'  => null,
];
```

If none of `template`, `html`, or `mailer` is present, MailChannel throws:
“Mail payload must include one of: "template", "html", or "mailer".”

<br>

## 4. Writing a Notification <a id="writing-a-notification"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
`Core\Lib\Notifications\Notification` is the base class. Below is the default template for the `make:notifications` command:
```php
<?php
namespace App\Notifications;

use App\Models\Users;
use Core\Lib\Notifications\Notification;

/**
 * NewUser notification.
 */
class NewUser extends Notification {
    protected $user;

    /**
     * Undocumented function
     *
     * @param Users $user
     */
    public function __construct(Users $user) {
        $this->user = $user;
    }

    /**
    * Data stored in the notifications table.
    *
    * @param object $notifiable Any model/object that uses the Notifiable trait.
    * @return array<string,mixed>
    */
    public function toDatabase(object $notifiable): array {
        return [
            'user_id'   => (int)$this->user->id,
            'username'  => $this->user->username ?? $this->user->email,
            'message'   => "Temp notification for user #{$this->user->id}",
            'created_at'=> \Core\Lib\Utilities\DateTime::timeStamps(), // optional
        ];
    }

    /**
    * Logs notification to log file.
    *
    * @param object $notifiable Any model/object that uses the Notifiable trait.
    * @return string Contents for the log.
    */
    public function toLog(object $notifiable): string {
        return "";
    }

    /**
    * Handles notification via E-mail.
    *
    * @param object $notifiable Any model/object that uses the Notifiable trait.
    * @return array<string,mixed>
    */
    public function toMail(object $notifiable): array {
        return [];
    }

    /**
    * Specify which channels to deliver to.
    * 
    * @param object $notifiable Any model/object that uses the Notifiable trait.
    * @return list<'database'|'mail'|'log'
    */
    public function via(object $notifiable): array {
        return Notification::channelValues();
    }
}
```

**Fallbacks**
- `toArray()` defaults to `toDatabase()`.
- If a `to{Channel}` method is missing, that channel will receive `['message' => null]` plus any extra payload.

<br>

## 5. Making a Model Notifiable <a id="making-model-notifiable"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Use the `Notifiable` trait on your model (e.g., `Users`):
```php
use Core\Lib\Notifications\Notifiable;

class Users {
    use Notifiable;
    public int $id;
    public string $email;
}
```

Send a notification:
```php
$user->notify(new \App\Notifications\UserRegistered($user));
// or override channels/payload:
$user->notify(
    new \App\Notifications\UserRegistered($user),
    ['log'],                       // channels override
    ['level' => 'warning', 'meta' => ['source' => 'cli']]
);
```
The trait ensures array payloads stay top-level (for Mail/DB) and strings are wrapped as `['message' => '...']` (for Log).

<br>

## 6. Events and Listeners <a id="events-and-listeners"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You can hook notifications into this framework's events/listener and queue system.  The events/listener example is as follows:
```php
class SendRegistrationEmail {
    /**
     * Handles event for sending user registered E-mail.
     *
     * @param UserRegistered $event The event.
     * @return void
     */
    public function handle(UserRegistered $event): void {
        $user = $event->user;
        $shouldSendEmail = $event->shouldSendEmail;
        NotificationService::notifyUsers(new UserRegisteredNotification($user));
        if($shouldSendEmail) {
            WelcomeMailer::sendTo($user);
        }
    }
}
```

The following example is for a queued event listener:
```php
class SendWelcomeEmailListener implements ShouldQueue, QueuePreferences {
    public function handle(UserRegistered $event) : void {
        NotificationService::notifyUsers(new UserRegisteredNotification($event->user));
        UserService::queueWelcomeMailer((int)$event->user->id, $this->viaQueue());
    }

    public function viaQueue(): ?string { return 'mail'; }
    public function delay(): int { return 60; }               // 1 min
    public function backoff(): int|array { return [10, 30, 60]; }
    public function maxAttempts(): int { return 5; }
}
```

This demonstrates two approaches: synchronous mail vs. queued mail. Both use `NotificationService::notifyAdmins(new UserRegisteredNotification($user))`.

<br>

## 7. CLI Commands <a id="cli-commands"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
#### A. Test a Notification (no side-effects by default)
```bash
php console notification:test DummyNotification --dry-run
```

Options:
- `--user=<id|email|username>` — target notifiable (falls back to `"dummy"` if omitted).
- `--channels=log,database` — override channels; omit to use `via()`.
- `--with=key:value,key2:value2` — attach additional payload fields (e.g., `level:warning`).

Examples:
```bash
# Dry run with defaults (uses via()):
php console notification:test UserRegistered --dry-run

# Dry run with channel override and payload overrides
php console notification:test UserRegistered --dry-run --channels=log,database --with=level:warning,tag:cli

# Actually send (remove --dry-run)
php console notification:test UserRegistered --user=42 --channels=mail
```

The command uses `Tools::setOutput($output)` so output is captured in tests.
On `--dry-run`, it prints the “Would send … via [X,Y]” line plus the JSON payload.

<br>

#### B. Generate a Notification class <a id="notification-class"></a>
```bash
php console make:notification UserRegistered --channels=log,database,mail
```

- Creates `app/Notifications/UserRegistered.php`.
- Includes channel methods matching the provided list and a `via()` that returns them.
- If you omit `--channels`, it scaffolds with all available channels.
<a id="notification-migration"></a>

<br>

#### C. Generate the notifications migration
```bash
php console notifications:migration
```
- Writes a migration class that creates the notifications table with useful indexes.
<a id="notification-prune"></a>

<br>

#### D. Prune old notifications
```bash
php console notifications:prune --days=90
```
- Deletes notifications older than `N` days (default 90).
- Uses `Core\Models\Notifications::notificationsToPrune($days)` internally.

<br>

## 8. Testing <a id="testing"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- For console tests, use Symfony’s `CommandTester` and ensure:
    - Your command calls `Tools::setOutput($output)` at the start of `execute()`.
    - The notification class exists under `App\Notifications\YourNotification`.
- Typical assertions:
    - error path: prints “does not exist”
    - dry-run path: prints `[DRY-RUN] … via [log]`
    - payload echo contains "level": "info", etc.

<br>

## 9. Notifications Model <a id="model"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `Core\Models\Notifications` model represents rows in the `notifications` table and gives you simple helpers to mark notifications as read and prune old rows. Most apps let the notification system write these rows automatically (via the Database channel). Use this model when you need to build an inbox, mark items read, or clean up.

<br>

#### A. Fields <a id="fields"></a>

| Property          | Type            | Notes                                                         |                          |
| ----------------- | --------------- | ------------------------------------------------------------- | ------------------------ |
| `id`              | string          | Primary key (UUID or string).                                 |                          |
| `type`            | string          | FQCN of the notification class that created the row.          |                          |
| `notifiable_type` | string          | FQCN of the target entity (e.g., `App\Models\Users`).         |                          |
| `notifiable_id`   | int             | string                                                        | ID of the target entity. |
| `data`            | text (JSON)     | Payload from `Notification::toDatabase()` (stringified JSON). |                          |
| `read_at`         | timestamp\|null | `null` if unread; timestamp when marked read.                 |                          |
| `created_at`      | timestamp       | Auto-managed in `beforeSave()`.                               |                          |
| `updated_at`      | timestamp       | Auto-managed in `beforeSave()`.                               |                          |


<br>

#### Common Queries. Fields <a id="common-queries"></a>
**Get a user’s notifications**
```php
use Core\Models\Notifications;
use App\Models\Users;

$user = Users::findById(42);

// All (read + unread), newest first
$rows = Notifications::find([
    'conditions' => 'notifiable_type = ? AND notifiable_id = ?',
    'bind'       => [Users::class, $user->id],
    'order'      => 'created_at DESC',
]);
```

**Only unread**
```php
$unread = Notifications::find([
    'conditions' => 'notifiable_type = ? AND notifiable_id = ? AND read_at IS NULL',
    'bind'       => [Users::class, $user->id],
    'order'      => 'created_at DESC',
]);
```

**Count unread**
```php
$unreadCount = Notifications::count([
    'conditions' => 'notifiable_type = ? AND notifiable_id = ? AND read_at IS NULL',
    'bind'       => [Users::class, $user->id],
]);
```

**Marking as Read**

1) Mark a single record (instance method)
```php
$note = Notifications::findFirst(['conditions' => 'id = ?', 'bind' => [$id]]);
if ($note) {
    $note->markAsRead(); // sets read_at = now and saves
}
```

2) Mark by ID (static helper)
```php
$ok = Notifications::markAsReadById($id); // true if updated, false otherwise
```

**Pruning Old Notifications**
Use this to reclaim space and keep the table fast.
Basic prune (all rows older than N days):
```php
// Delete notifications older than 90 days (read or unread)
$deleted = Notifications::notificationsToPrune(90);
```

Prune only read rows:
```php
// Delete only read notifications older than 30 days
$deleted = Notifications::notificationsToPrune(30, true);
```

Return value: Number of rows deleted.
Constraints: `$days` must be ≥ 1 (throws `InvalidArgumentException` otherwise).

Indexing tip: Add indexes on `created_at` and `(notifiable_type, notifiable_id)` for best performance. If you plan to prune by “read only”, consider `(read_at, created_at)`.

**Working with the data JSON**
The `data` column stores whatever your `Notification::toDatabase()` returned. Typical shape:
```php
[
  'user_id'       => 42,
  'username'      => 'alice',
  'message'       => 'A new user has registered: alice',
  'registered_at' => '2025-08-01 12:00:00'
]
```

To use it:
```php
$note = Notifications::findFirst(['conditions' => 'id = ?', 'bind' => [$id]]);
if ($note) {
    $payload = json_decode((string) $note->data, true) ?: [];
    $message = $payload['message'] ?? '';
}
```

**Quick Recipes**
Show last 10 unread messages for a user:
```php
$rows = Notifications::find([
    'conditions' => 'notifiable_type = ? AND notifiable_id = ? AND read_at IS NULL',
    'bind'       => [Users::class, $user->id],
    'order'      => 'created_at DESC',
    'limit'      => 10,
]);

foreach ($rows as $row) {
    $data = json_decode((string)$row->data, true) ?: [];
    echo $data['message'] ?? '[no message]', PHP_EOL;
}
```

Mark all of a user’s notifications as read (simple loop):
```php
$rows = Notifications::find([
    'conditions' => 'notifiable_type = ? AND notifiable_id = ? AND read_at IS NULL',
    'bind'       => [Users::class, $user->id],
]);

foreach ($rows as $row) {
    $row->markAsRead();
}
```

<br>

## 10. Troubleshooting <a id="troubleshooting"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- “Unsupported notification channel X”:  Make sure `NotificationManager::boot()` ran and your channel is registered in `config('notifications.channels')`.
- MailChannel errors: Ensure `toMail()` returns one of: `template`, `html` (optionally `text`), or `mailer`. Missing these throws `InvalidPayloadException`.
- DatabaseChannel errors: Your notifiable must have a public `id`. If you send to a dummy string in “simulate” mode, the helper logs instead of saving.
- LogChannel message is empty: Provide `toLog()` or include a `message` key in your payload/`toArray()`.
- Namespaces & autoload: Place app notifications in `app/Notifications` with namespace `App\Notifications`;. Run `composer dump-autoload` after adding files.