<h1 style="font-size: 50px; text-align: center;">Events/Listeners</h1>

## Table of contents
1. [Overview](#overview)
2. [How It Works](#how-it-works)
3. [File Locations](#file-locations)
4. [Bootstrapping](#bootstrapping)
5. [Creating Events & Listeners](#creating)
6. [Replacing Direct Service Calls](#example)
7. [Writing Core vs Userland Events](#types)
8. [Advanced](#advanced)
9. [Queued Events](#queued-events)
    * A. [Creating a Queued Event](#create-queued-event)
    * B. [Creating a Queued Listener](#create-queued-listener)
    * C. [Registering the Listener](#registration)
    * D. [Dispatching the Event](#dispatching)
    * E. [Running the Queue Worker](#running-worker)
    * F. [Advantages of Using Queued Listeners](#advantages)
    * G. [Key Notes](#keynotes)
10. [Summary](#summary)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The Event/Listener system in Chappy.php allows you to decouple your application logic using the Observer Pattern.
Instead of calling functions directly, you dispatch events, and listeners handle them automatically.

This makes your code cleaner, easier to maintain, and extensible — similar to Laravel’s event system.

<br>

## 2.🔹How It Works <a id="how-it-works"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- Events: Simple classes that describe something that happened (e.g., `UserRegistered`, `OrderShipped`).
- Listeners: Classes that respond to specific events.
- EventDispatcher: Core service that registers listeners and dispatches events.
- EventServiceProvider: Registers all your event → listener mappings.
- EventManager: Boots all core and app providers and stores a shared dispatcher.

<br>

## 3. 📂 File Locations <a id="file-locations"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

| Type      | Core Location            | App (Userland) Location           |
| --------- | ------------------------ | --------------------------------- |
| Events    | `src/core/Lib/Events`    | `app/Events`                      |
| Listeners | `src/core/Lib/Listeners` | `app/Listeners`                   |
| Providers | `src/core/Lib/Providers` | `app/Providers`                   |
| Config    | `config/providers.php`   | Same file, add your app providers |

<br>

## 4. ⚙ Bootstrapping <a id="bootstrapping"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Events and listeners are booted automatically via `EventManager::boot()` in `bootstrap.php`.

Config file: `config/providers.php`
Example:
```php
<?php
return [
    Core\Lib\Providers\EventServiceProvider::class, // Core events
    App\Providers\EventServiceProvider::class,      // Userland events
];
```

Add your app’s event provider here to register custom events/listeners.

<br>

## 5. 🚀 Creating Events & Listeners <a id="creating"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
1️⃣ Make a new Event
```bash
php console make:event UserPromoted
```

This creates:
```swift
app/Events/UserPromoted.php
```

Example:
```php
namespace App\Events;

use App\Models\Users;

class UserPromoted
{
    public $user;

    public function __construct(Users $user)
    {
        $this->user = $user;
    }
}
```

<br>

2️⃣ Make a new Listener
```bash
php console make:listener UserPromoted NotifyAdminOfPromotion
```

This creates:
```swift
app/Listeners/NotifyAdminOfPromotion.php
```

Example:
```php
namespace App\Listeners;

use App\Events\UserPromoted;

class NotifyAdminOfPromotion
{
    public function handle(UserPromoted $event): void
    {
        $user = $event->user;
        // Perform your action (e.g., send email)
    }
}
```

<br>

3️⃣ Make an EventServiceProvider (userland)
```bash
php console make:provider EventServiceProvider
```

This creates:
```swift
app/Providers/EventServiceProvider.php
```

Example:
```php
namespace App\Providers;

use Core\Lib\Events\EventDispatcher;
use Core\Lib\Providers\ServiceProvider;
use App\Events\UserPromoted;
use App\Listeners\NotifyAdminOfPromotion;

class EventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        UserPromoted::class => [
            NotifyAdminOfPromotion::class,
        ],
    ];

    public function boot(EventDispatcher $dispatcher): void
    {
        parent::boot($dispatcher);
    }
}
```

<br>

🔄 Dispatching Events

You can dispatch an event anywhere after boot:
```php
use Core\Lib\Events\EventManager;
use App\Events\UserPromoted;

// Get a user instance
$user = Users::find(1);

// Dispatch the event
EventManager::dispatcher()->dispatch(new UserPromoted($user));
```

This will automatically call `handle()` on every listener registered for `UserPromoted`.

<br>

## 6. 🧪 Example — Replacing Direct Service Calls <a id="example"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Instead of:
```php
NotificationService::sendUserRegistrationNotification($user);
```

You can:
```php
EventManager::dispatcher()->dispatch(
    new \Core\Lib\Events\UserRegistered($user, true)
);
```

Your `SendRegistrationEmail` listener will handle sending notifications and welcome emails.

<br>

## 7. 🔧 Writing Core vs Userland Events <a id="types"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- Core events/listeners live in src/core/Lib/Events and src/core/Lib/Listeners.
- Userland events/listeners live in app/Events and app/Listeners.

Core is maintained by the framework and ships with default functionality.
Userland is for your app-specific needs.

Built End Listeners:
- SendAccountDeactivatedEmail - Sends E-mail when account is deactivated
- SendPasswordResetEmail - Sends E-mail requesting user update their password
- SendRegistrationEmail - Sends E-mail when user registers for an account
- SendPasswordUpdatedEmail - Sends E-mail when user updates their password

<br>

## 8. 🛠 Advanced <a id="advanced"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Multiple Listeners
You can register multiple listeners for the same event in `$listen`:
```php
protected array $listen = [
    UserPromoted::class => [
        NotifyAdminOfPromotion::class,
        LogPromotionActivity::class,
    ],
];
```

Conditional Logic
Listeners can use event data to decide what to do:
```php
public function handle(UserRegistered $event): void
{
    if ($event->shouldSendEmail) {
        WelcomeMailer::sendTo($event->user);
    }
}
```

<br>

## 9. 📦 Queued Event Listeners <a id="queued-events"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Queued event listeners allow you to defer the execution of an event listener until it is processed by a queue worker, rather than executing it immediately when the event is fired.
This is ideal for time-consuming tasks such as sending emails, processing images, or performing external API calls, which should not delay the user’s request.

<br>

### A. Creating a Queued Event <a id="create-queued-event"></a>
When generating an event with the `--queue` flag, the event class will automatically include payload serialization (`toPayload()`) and rehydration (`fromPayload()`) methods.
These methods ensure that the event data can be stored in the queue and restored later when processed.

Example:
```bash
php console make:event TestEvent --queue
```

Generated Event Class:
```php
<?php
namespace Core\Lib\Events;

use App\Models\Users;

/**
 * Document class here.
 */
class TestEvent {
    public $user;

    /**
     * Constructor
     *
     * @param User $user User associated with event.
     */
    public function __construct(Users $user) {
        $this->user = $user;
    }

    /**
     * Adds instance variables to payload.
     *
     * @return array An associative array containing values of instance 
     * variables.
     */
    public function toPayload(): array {
        return [];
    }

    /**
     * Retrieves information from payload array and returns new instance of 
     * this class.
     *
     * @param array $data The payload array.
     * @return self New instance of this class.
     */
    public static function fromPayload(array $data): self {
        $user = Users::findById((int)$data['user_id']);
        return new self($user);
    }
}
```

Working example built into framework:
```php
<?php
declare(strict_types=1);
namespace Core\Lib\Events;

use App\Models\Users;

class UserRegistered {
    public $user;

    public function __construct(Users $user) {
        $this->user = $user;
    }

    public function toPayload(): array {
        return [
            'user_id' => (int)$this->user->id,
        ];
    }

    public static function fromPayload(array $data): self {
        $user   = Users::findById((int)$data['user_id']);
        return new self($user);
    }
}
```

<br>

### B. Creating a Queued Listener <a id="create-queued-listener"></a>
When generating a listener with the --queue flag, the listener will automatically implement:
- ShouldQueue – Marks this listener for queueing.
- QueuePreferences – Allows you to configure the queue name, delay, backoff, and max attempts.

Example:
```bash
php console make:listener TestListener --queue
```

Generated Listener Class:
```php
<?php
namespace Core\Lib\Listeners;

use App\Events\TestEvent;
use Core\Lib\Events\Contracts\ShouldQueue;
use Core\Lib\Events\Contracts\QueuePreferences;

/**
 * Add description for class here
 */
class TestListener implements ShouldQueue, QueuePreferences {
    /**
     * Handle the event.
     *
     * @param TestEvent $event The event.
     * @return void
     */
    public function handle(TestEvent $event) : void {
        $user = $event->user;
    }

    /**
     * Set name of queue to be used.
     *
     * @return string|null
     */
    public function viaQueue(): ?string { 
        return 'default'; 
    }

    /**
     * Set the delay in seconds.
     *
     * @return int The delay in seconds.
     */
    public function delay(): int { 
        return 60; 
    }

    /**
     * Get backoff for job.  Can be an array of integers or a single in 
     * seconds.
     *
     * @return int|array The backoff times.
     */
    public function backoff(): int|array { 
        return [10, 30, 60];
    }

    /**
     * Gets number of maximum allowed attempts.
     *
     * @return int The maximum allowed number of attempts.
     */
    public function maxAttempts(): int { 
        return 5; 
    }
}
```

Working example built into framework:
```php
<?php
namespace Core\Lib\Listeners;

use Core\Lib\Events\UserRegistered;
use Core\Lib\Events\Contracts\ShouldQueue;
use Core\Lib\Events\Contracts\QueuePreferences;
use Core\Services\UserService;
use Core\Services\NotificationService;

class SendWelcomeEmailListener implements ShouldQueue, QueuePreferences {
    public function handle(UserRegistered $event) : void {
        NotificationService::sendUserRegistrationNotification($event->user);
        UserService::queueWelcomeMailer((int)$event->user->id, $this->viaQueue());
    }

    public function viaQueue(): ?string { return 'mail'; }
    public function delay(): int { return 60; }
    public function backoff(): int|array { return [10, 30, 60]; }
    public function maxAttempts(): int { return 5; }
}
```

Implementation of `queueWelcomeMailer`:
```php
public static function queueWelcomeMailer(int $user_id, string $queueName = 'default') {
    $queue = new QueueManager();
    $job   = new SendWelcomeEmail(['user_id' => $user_id], 0); // delay=0

    $payload = $job->toPayload();
    // Fix DATETIME format
    $payload['available_at'] = DateTime::nowPlusSeconds($job->delay());

    $queue->push($payload, $queueName);
}
```

When pushing job into the queue make sure you use `mail` via the `$queueName` parameter.  Otherwise, the job will not run due to conflicts.

<br>

### C. Registering the Listener <a id="registration"></a>
All listeners (queued or synchronous) must be registered in your `EventServiceProvider`:
```php
protected array $listen = [
    UserRegistered::class => [
        SendWelcomeEmailListener::class,
    ],
];
```
<br>

### D. Dispatching the Event <a id="dispatching"></a>
You can dispatch the event normally.
If the listener implements `ShouldQueue`, it will be serialized into the queue rather than executed immediately.
```php
EventManager::dispatcher()->dispatch(new UserRegistered($user,));
```

<br>

### E. Running the Queue Worker <a id="running-worker"></a>
Queued listeners won’t run until a queue worker processes them:
```bash
php console queue:work
```

You can run multiple workers, or run them in the background using a process manager such as `supervisord` or `systemd`.

<br>

### F. Advantages of Using Queued Listeners <a id="advantages"></a>
- Improves response time for user requests.
- Decouples time-consuming tasks from your application’s main request lifecycle.
- Allows retry handling with `maxAttempts()` and `backoff()`.

<br>

### G. Key Notes <a id="keynotes"></a>
- Always implement `toPayload()` and f`romPayload()` for queued events to ensure safe serialization.
- Be careful not to include sensitive data (e.g., raw passwords) in the payload.
- You can change the queue name with `viaQueue()`, or leave it as `'default'`.
- Listeners without `ShouldQueue` will run immediately.

<br>

## 10. 📌 Summary <a id="summary"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- Define your events in app/Events.
- Create listeners in app/Listeners.
- Register them in an EventServiceProvider.
- Add your provider to config/providers.php.
- Dispatch events anywhere in your code.
- Listeners automatically execute when their event is fired.