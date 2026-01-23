<h1 style="font-size: 50px; text-align: center;">Unit Tests</h1>

## Table of contents
1. [Overview](#overview)



<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

The Unit Test system in the Chappy PHP framework enables developers to write automated tests for their applications using a clean, expressive API layered on top of PHPUnit and Vitest. It includes a custom ApplicationTestCase base class that simulates HTTP requests (get, post, put, patch, and delete) and provides convenient methods for asserting database state, capturing controller output, and validating view data.

This setup is ideal for:
- Testing controller logic in isolation
- Verifying database insertions or updates
- Ensuring CSRF protection works as expected
- Simulating file uploads or form submissions
- Confirming correct redirects and rendered content

The test layer integrates tightly with your MVC routing system, allowing you to call routes directly using URI patterns like /auth/register, just as a real user would in the browser.

All tests run in an isolated environment with support for:
- In-memory SQLite or custom test databases
- Auto-migration and seeding via your console commands
- Custom response wrappers for fluent test assertions

🧪 Whether you're testing form validation, user registration, or database interactions, the Chappy framework's testing system provides the power and flexibility to help ensure your application is reliable and maintainable.

Guides for running PHPUnit and Vitest test from the console can be found in their respective sections.