<h1 style="font-size: 50px; text-align: center;">LAMP Stack</h1>

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