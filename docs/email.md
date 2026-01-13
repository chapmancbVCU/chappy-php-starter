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
    * H. [Example Use Case - Welcome Email](#example-use-case)
    * I. [Notes](#notes)
3. [Using sendTemplate() in Chappy.php](#send-template)
    * A. [Test Case Examples](#test-case-examples)
    * B. [Adding Attachments](#adding-attachments)
    * C. [Overriding Default Paths](#overriding-defaults)
4. [sendTo() with Advanced Parameters](#send-to)
5. [Attachment Management](#attachment-management)
    * A. [Attachments Listing](#attachments-listing)
    * B. [Add or Edit Attachments](#add-or-edit)
    * C. [Attachment Details](#attachment-details)
    * D. [Usage with MailerService](#usage-with-mailerservice)
    * E. [Additional Notes](#additional-notes)

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
- AccountDeactivatedMailer - Notifies user when account is deactivated.  More info in UserService section.
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
php console make:email:layout
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

### H. Example Use Case  - Welcome Email <a id="example-use-case"></a>
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

<br>

## 3. 📬 Using `sendTemplate()` in Chappy.php <a id="send-template"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `MailerService::sendTemplate()` method allows you to send HTML (and optionally plain-text) emails using reusable templates, layouts, and CSS stylesheets. This method is powerful and flexible, supporting both embedded and path-based file attachments.

🧠 Basic Usage
```php
$mail = new MailerService();
$user = Users::findById(1);

$mail->sendTemplate(
    $user->email,
    'Welcome Subject',
    'welcome',                   // Template name (without extension)
    ['user' => $user],           // Data passed to template
    'default',                   // Layout name
    [],                          // Attachments
    MailerService::FRAMEWORK_LAYOUT_PATH,
    MailerService::FRAMEWORK_TEMPLATE_PATH,
    'default',                   // CSS filename (no extension)
    MailerService::FRAMEWORK_STYLES_PATH
);
```

<br>

### A. Test Case Examples <a id="test-case-examples"></a>
#### 1. Basic HTML E-mail
Sends a raw HTML string without templates:
```php
$mail->send($user->email, 'test_email_is_sent', '<p>Your account is ready!</p>');
```

<br>

#### 2. HTML Template with Layout and Stylesheet
```php
$mail->sendTemplate(
    $user->email,
    'test_email_template',
    'welcome',                   // welcome.php under views/emails/
    ['user' => $user],
    'default',                   // default.php under views/emails/layouts/
    [],
    MailerService::FRAMEWORK_LAYOUT_PATH,
    MailerService::FRAMEWORK_TEMPLATE_PATH,
    'default',                   // default.css under views/emails/styles/
    MailerService::FRAMEWORK_STYLES_PATH
);
```

<br>

#### 3. HTML + Text Email from Dual Template
Includes `welcome_text.txt` if found in `views/emails/`:
```php
$mail->sendTemplate(
    $user->email,
    'test_email_text_template',
    'welcome_text',
    ['user' => $user->username],
    'default',
    [],
    MailerService::FRAMEWORK_LAYOUT_PATH,
    MailerService::FRAMEWORK_TEMPLATE_PATH,
    'default',
    MailerService::FRAMEWORK_STYLES_PATH
);
```

<br>

### B. Adding Attachments <a id="adding-attachments"></a>
#### 1. Single Attachment
```php
$attachment = EmailAttachments::findById(2);

$mail->sendTemplate(
    $user->email,
    'test_email_single_attachment_and_template',
    'welcome',
    ['user' => $user->username],
    'default',
    Attachments::content($attachment),
    ...
);
```

<br>

#### 2. Multiple Attachments (Content + Path)
```php
$attachments = [
    Attachments::content($attachment2),
    Attachments::path($attachment1)
];

$mail->sendTemplate(
    $user->email,
    'test_email_multiple_attachments_and_template',
    'welcome',
    ['user' => $user->username],
    'default',
    $attachments,
    ...
);
```

#### 3. Text Template with Attachments
Combines `.txt` content fallback and multiple files:
```php
$mail->sendTemplate(
    $user->email,
    'test_email_text_template_with_attachments',
    'welcome_text',
    ['user' => $user->username],
    'default',
    $attachments,
    ...
);
```

### C. 🗂 Overriding Default Paths <a id="overriding-defaults"></a>
To use custom layout, template, or styles from your own application:

| Parameter       | Default Constant                         | Custom Example                    |
| --------------- | ---------------------------------------- | --------------------------------- |
| `$layoutPath`   | `MailerService::FRAMEWORK_LAYOUT_PATH`   | `resources/views/emails/layouts/` |
| `$templatePath` | `MailerService::FRAMEWORK_TEMPLATE_PATH` | `resources/views/emails/`         |
| `$stylesPath`   | `MailerService::FRAMEWORK_STYLES_PATH`   | `resources/css/`                  |

Pass custom paths manually to override framework defaults:
```php
$mail->sendTemplate(
    $to,
    $subject,
    $template,
    $data,
    'custom_layout',
    [],
    CHAPPY_BASE_PATH . '/resources/views/emails/layouts/',
    CHAPPY_BASE_PATH . '/resources/views/emails/',
    'custom_style',
    CHAPPY_BASE_PATH . '/resources/css/'
);
```

🧭 Using Default Application Paths

If you want to use your own customized email templates, layouts, and styles located in:
- `resources/views/emails/layouts/`
- `resources/views/emails/`
- `resources/css/`

You do not need to pass path parameters at all — just set them to `null`, or omit them entirely:
```php
$mail->sendTemplate(
    $user->email,
    'Welcome to Chappy.php!',
    'welcome',
    ['user' => $user],
    'default',     // Layout name (e.g., default.php)
    [],            // No attachments
    null,          // Use default layout path: /resources/views/emails/layouts/
    null,          // Use default template path: /resources/views/emails/
    'default',     // CSS file: default.css
    null           // Use default style path: /resources/css/
);
```

These values are automatically resolved using:
- self::$layoutPath
- self::$templatePath
- self::$stylesPath

…which are set to point to your `resources/` directory.

<br>

## 4. sendTo() with Advanced Parameters <a id="send-to"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
By default, `AbstractMailer::sendTo()` uses the base `send()` method, which calls `buildAndSend()` with default parameters. However, if your custom mailer requires attachments or custom layout/template/style paths, you can override `sendTo()` directly.

🛠 Example: Overriding `sendTo()` to Add Attachments
```php
use App\Models\Users;
use Core\Models\EmailAttachments;
use Core\Lib\Mail\AbstractMailer;
use Core\Lib\Mail\Attachments;

class WelcomeMailer extends AbstractMailer
{
    protected function getData(): array {
        return ['user' => $this->user];
    }

    protected function getSubject(): string {
        return 'Welcome to ' . env('SITE_TITLE');
    }

    protected function getTemplate(): string {
        return 'welcome';
    }

    /**
     * Override the static sendTo method to include custom build options.
     */
    public static function sendTo(Users $user): bool {
        $mailer = new static($user);

        $attachment1 = EmailAttachments::findById(1);
        $attachment2 = EmailAttachments::findById(2);

        return $mailer->buildAndSend(
            null, // layout (defaults to 'default')
            [
                Attachments::content($attachment1),
                Attachments::path($attachment2)
            ],
            null, // layout path (uses /resources/views/emails/layouts/)
            null, // template path (uses /resources/views/emails/)
            'default', // CSS filename
            null  // CSS path (uses /resources/css/)
        );
    }
}
```

🔍 Usage in Controller or Console
```php
$user = Users::findById(1);
WelcomeMailer::sendTo($user); // Sends with attachments and full customization
```

✅ When Should You Override `sendTo()`?
You should override sendTo() when:
- You want to embed logic unique to that mailer (e.g. attachments, path overrides)
- You need to inject data not handled by the getData() method
- You want to isolate one-off logic from your controller or service layer

📌 Reminder: `buildAndSend()` Signature
```php
protected function buildAndSend(
    ?string $layout = null,
    array $attachments = [],
    ?string $layoutPath = null,
    ?string $templatePath = null,
    ?string $styles = null,
    ?string $stylesPath = null
): bool
```

🧼 Best Practice
If your attachments or logic are conditional, wrap them cleanly in the overridden `sendTo()` to keep your mailer class readable.
<br>

## 5. Attachment Management <a id="attachment-management"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The admin dashboard allows administrators to upload, preview, update, and delete email attachments. These attachments can later be used with the framework's `MailerService::sendTemplate()` functionality. This section documents the related views and controller actions.

### A. 📄 Attachments Listing <a id="attachments-listing"></a>
**View:** `admindashboard.attachments`

This page displays a table of all uploaded attachments, including:
- Original filename (with a link to the attachment details)
- Uploader username
- File size (formatted)
-Action buttons: Edit / Delete

Add New Attachment: A button is available to navigate to the upload form:`/admindashboard/editAttachments/new`

<br>

### B. 📋 Add or Edit Attachment <a id="add-or-edit"></a>
**View:** `admindashboard.attachments_form`
This form supports both creating and updating attachments:
- Description (WYSIWYG via TinyMCE)
- File Upload (only for new records)
- CSRF Protection enabled via `csrf()`
- Save and Cancel buttons

<br>

### C. 📁 Attachment Details <a id="attachment-details"></a>
**View:** `admindashboard.attachment_details`

This view shows metadata and a preview link:
- Created and Updated timestamps (in human-readable format)
- File size and MIME type
- Uploader username
- Description (rich text rendered safely)
- Link to Preview the file: `/admindashboard/preview/:id`

<br>

### D. 🧪 Usage with MailerService <a id="usage-with-mailerservice"></a>
Once uploaded, attachments can be used in `MailerService::sendTemplate()`:
```php
$mailer->sendTemplate(
    $to,
    'Subject Line',
    'template_name',
    ['user' => $user],
    'default',
    [
        Attachments::content($attachment),
        Attachments::path($attachment)
    ],
    null, // layout path (null uses /resources/views/emails/layouts)
    null, // template path (null uses /resources/views/emails/templates)
    'default',
    null  // styles path
);
```

<br>

### E. ✅ Additional Notes <a id="additional-notes"></a>
- Uploaded files are stored and accessible only within admin.
- Attachments are associated with users via the `user_id` column.
- File metadata such as MIME type and size are captured and stored during upload.
- Descriptions are HTML-safe and stored with support for rich formatting.

This section allows administrators to manage reusable file assets securely and efficiently for use in transactional emails.