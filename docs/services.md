<h1 style="font-size: 50px; text-align: center;">Services</h1>

## Table of contents
1. [Overview](#overview)
2. [User Defined Services](#user-services)
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
In Chappy.php, services are dedicated classes that encapsulate reusable, domain-specific logic to keep controllers and models clean and focused. They follow the Single Responsibility Principle (SRP) and are ideal for handling complex workflows, cross-cutting concerns, or business logic that doesn’t belong in a model or controller.
🛠 Purpose

Services act as the bridge between your application’s components — controllers call services to perform tasks, and services interact with models or libraries as needed. This pattern improves testability, readability, and long-term maintainability.
🧭 When to Use Services

Use a service class when:
- Logic spans multiple models or system components
- Code is repeated across multiple controllers
- Logic doesn’t belong in a controller (which should orchestrate) or model (which should persist data)
- You want to keep code modular and testable

📁 Location
Builtin service classes reside in:
```bash
/src/core/Services
```

Each service class is namespaced as:
```php
namespace Core\Services;
```
You may group services by domain if your application scales (e.g., AuthService, UserService, MailService).

📌 Available Services
- ACLService: Manages tasks related to access control levels.
- AuthService: Manages login, logout, and session handling.
- AttachmentService: Processes email attachments and handles preview or deletion.
- DashboardService: Coordinates admin dashboard actions like pagination or access checks.
- UserService: Handles profile updates, password changes, image uploads, and account status logic.

<br>

## 2. User Defined Services <a id="service-services"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
