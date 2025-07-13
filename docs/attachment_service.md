<h1 style="font-size: 50px; text-align: center;">AttachmentService</h1>

## Table of contents
1. [Overview](#overview)
2. [Public Methods](#public-methods)
3. [Related Components](#related-components)
4. [Notes](#notes)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The AttachmentService class provides static methods for managing file attachments uploaded through the admin interface. It handles uploading, previewing, deleting, and metadata processing for email-related attachments.

This service ensures file integrity and security, while integrating with your framework's upload and email systems.

**Setup**
```php
use Core\Services\AttachmentService;
```

✅ **Common Use Cases**
- Upload and save attachment files
- Link uploaded files to email records
- Display previews inline in the browser
- Safely delete attachments from both storage and database

<br>

## 2. ⚙️ Public Methods <a id="public-methods"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
`attachmentUpload(EmailAttachments $attachment): ?Uploads`

Generates an upload handler for a new attachment and processes the file upload.

Returns `null` if the attachment already exists.
```php
$upload = AttachmentService::attachmentUpload($attachment);
```

<br>

`attachmentUploader(int $id): Users`

Retrieves the user who uploaded a given attachment by their user ID.
```php
$user = AttachmentService::attachmentUploader($attachment->user_id);
```

<br>

`deleteAttachment(EmailAttachments $attachment): void`

Deletes an attachment file from the filesystem and removes the associated database record.
```php
AttachmentService::deleteAttachment($attachment);
```

<br>

`name(EmailAttachments $attachment): string`

Determines the filename to store in the database:
- For new uploads, uses the sanitized filename from `$_FILES`.
- For existing records, returns the saved filename.
```php
$filename = AttachmentService::name($attachment);
```

<br>

`previewAttachment(int $id): void`

Outputs an attachment file inline in the browser for previewing. Automatically sets proper headers and handles 404 if missing.
```php
AttachmentService::previewAttachment($attachmentId);
```
**Commonly used to display file previews in a new browser tab.**

<br>

`processAttachment(EmailAttachments $attachment, Input $request): void`

Handles the complete lifecycle of processing an uploaded attachment:
- Accepts upload
- Sets name, description, and user ID
- Saves metadata
- Moves file to the correct upload path
- Sets MIME type and final file path
```php
AttachmentService::processAttachment($attachment, $request);
```
This method should be used in both attachment create and update flows.

<br>

## 4. 📦 Related Components<a id="related-components"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
📦 Related Components
- `EmailAttachments` – The model representing stored attachment records.
- `Uploads` – Upload handler responsible for file processing.
- `Attachments` – Utility class for supporting processing of attachments.
- `AuthService` – Used to get the current user ID for audit fields.

<br>

## 4. 🧠 Notes <a id="notes"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- Uploaded attachments are stored in the path defined by `EmailAttachments::$_uploadPath`.
- The service assumes the file input name is `attachment_name` and that the file is uploaded via `$_FILES`.
- The `processAttachment()` method is safe to call for both new and existing records; it checks if uploads are needed.