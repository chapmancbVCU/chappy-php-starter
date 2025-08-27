<h1 style="font-size: 50px; text-align: center;">LAMP Stack</h1>

## Table of contents
1. [Overview](#overview)
2. [Channel Registry](#channel-registry)
3. [Channels & Payloads](#channels-and-payloads)
    * A. [Database Channel](#database-channel)
    * B. [Log Channel](#log-channel)
    * C. [Mail Channel](#mail-channel)
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
