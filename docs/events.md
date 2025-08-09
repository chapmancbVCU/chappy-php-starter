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
9. [Summary](#summary)
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

## 9. 📌 Summary <a id="summary"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- Define your events in app/Events.
- Create listeners in app/Listeners.
- Register them in an EventServiceProvider.
- Add your provider to config/providers.php.
- Dispatch events anywhere in your code.
- Listeners automatically execute when their event is fired.