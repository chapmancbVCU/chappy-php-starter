<h1 style="font-size: 50px; text-align: center;">Mailer Service</h1>

## Table of contents
1. [Overview](#overview)
2. [Key Components](#key-components)
    * A. [MailerService](#mailer-service)
    * B. [AbstractMailer](#abstract-mailer)
    * C. [CustomMailer](#custom-mailers)
    * D. [Templates and Layouts](#templates-and-layouts)
    * E. [Logging](#logging)
    * F. [Attachments](#attachments)
    * G. [Environment Configuration](#environment-configuration)
    * H. [Example Use Case](#example-use-case)
    * I. [Notes](#notes)
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The Chappy.php E-mail system is a modular, extensible implementation built on Symfony Mailer. It allows developers to send fully styled, template-driven emails with optional layouts and attachments. Emails are logged for auditing, and inline CSS rendering is supported via CssToInlineStyles.

## 2. Key Components <a id="key-components"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

### A. MailerService <a id="mailer-service"></a>
Central service that composes and sends emails.

**Features:**
- Send raw HTML or styled template-based emails
- Inline CSS support
- Optional plain text alternative
- Full logging of email attempts
- Attachment support

**Paths Used:**
- Layouts: `resources/views/emails/layouts/`
- Templates: `resources/views/emails/`
- Stylesheets: `resources/css/`

<br>

### B. AbstractMailer <a id="abstract-mailer"></a>
Base class for creating structured mailers. Encapsulates logic for defining recipient, template, subject, and data payload.

**Default behavior:**
- Uses `MailerService::sendTemplate`
- Layout: `default`
- Style: `default`

<br>

### C. Custom Mailers (e.g. `WelcomeMailer`) <a id="custom-mailers"></a>
Extend `AbstractMailer` to create reusable mail definitions.

```php
class WelcomeMailer extends AbstractMailer {
    protected function getData(): array {
        return ['user' => $this->user];
    }

    protected function getSubject(): string {
        return 'Welcome to ' . env('SITE_TITLE');
    }

    protected function getTemplate(): string {
        return 'welcome';
    }
}
```

**Built In Mailers**
- AccountDeactivatedMailer - Notifies user when account is deactivated
- PasswordResetMailer - Notifies user when password needs to be reset
- UpdatePasswordMailer - Notifies user when password is updated
- WelcomeMailer - Sent when user creates an account

**Example Usage**
```php
WelcomeMailer::sendTo($user);
```

**Build Your Own Mailer**
Run the following command:
```sh
php console make:mailer MyMailer
```

File will be generated at `app\CustomMailers`.  The modify the `getData`, `getSubject`, and `getTemplate` functions.

<br>

### D. Templates and Layouts <a id="templates-and-layouts"></a>
**Making a template:**
```sh
php console make:email
```

You can find the new template at `resources/views/emails/`
```php
// Example: welcome.php (template)
<h1>Welcome, <?= $user->name ?>!</h1>
<p>Thank you for signing up.</p>
```

**Making a layout**
```sh
php console make:email-layout
```

```php
// Example: default.php (layout)
<html>
  <body>
    <?= $content ?>
  </body>
</html>
```

You can find the layout at `resources/views/emails/layouts/`

**Adding Styles**
```sh
php console make:styles
```

Your styles can be found at `resources/css/`

**Optional** 
.txt files with the same name as the template can be used for plain text versions.

<br>

### E. Logging <a id="logging"></a>
Every email attempt (success or failure) is logged using the framework's Logger class. Fields include:
- Status (success or failed)
- Timestamp
- Recipient
- Subject
- HTML body (escaped)
- Text body (if provided)
- Template name
- DSN transport
- Error message (if failed)
- Attachment metadata
- Logs use the error or info log levels.

<br>

### F. Attachments <a id="attachments"></a>
Use the `Attachments` class to prepare data arrays.
```php
Attachments::path($emailAttachment);
Attachments::content($emailAttachment);
```

Pass these into the `sendTemplate()` method or `buildAndSend()`.

<br>

### G. Environment Configuration <a id="environment-configuration"></a>
Set the following in your .env file:

```rust
MAILER_DSN=smtp://user:pass@smtp.mailtrap.io:2525
MAIL_FROM_ADDRESS=noreply@example.com
```

<br>

### H. Example Use Case <a id="example-use-case"></a>
```php
$user = Users::findById(1);
WelcomeMailer::sendTo($user);
```

This sends a templated welcome email using the default layout and style.

<br>

### I. Notes <a id="notes"></a>
- HTML and text versions improve deliverability
- CSS is inlined to ensure better rendering across clients
- All template rendering uses output buffering and extract() for dynamic data injection