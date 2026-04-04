<h1 style="font-size: 50px; text-align: center;">DashboardService</h1>

## Table of contents
1. [Overview](#overview)
2. [Public Methods](#public-methods)
3. [Related Components](#related-components)
4. [Notes](#notes)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `DashboardService` class provides helper functions for administrative dashboard operations, including access restrictions and paginated user management. It ensures that the logged-in admin user cannot edit or view their own profile via the dashboard and supports user list pagination.

<br>

**Setup**
```php
use Core\Services\DashboardService;
```

<br>

**Common Use Cases**
- Prevent the logged-in admin from editing or viewing their own profile via the dashboard.
- Retrieve a paginated list of all users excluding the current admin.
- Get a count of users for dashboard summaries and stats.

<br>

## 2. Public Methods <a id="public-methods"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `checkIfCurrentUser()`
Prevents the current logged-in user from viewing or editing their own user record via the admin dashboard. If the given user matches the currently logged-in user, a danger flash message is set and a redirect occurs.

Parameters:
- `Users $user` - The current user.
- `string $redirect` - The destination for redirect.

```php
DashboardService::checkIfCurrentUser($user, 'admindashboard.users');
```

<br>

### B. `paginateUsers()`
Returns an array of paginated users, excluding the currently logged-in admin. Sorting is done in descending order by `created_at`.

Parameter:
- `Pagination $pagination` - Instance of Pagination class.

Returns:
- `array` - An array of paginated users.

```php
$users = DashboardService::paginateUsers($pagination);
```

<br>

### C. `totalUserCountExceptCurrent()`
Returns the total number of users in the system, excluding the currently logged-in admin. This can be used for admin dashboard statistics or analytics.

Returns:
- `int` - The number of users except current.

```php
$count = DashboardService::totalUserCountExceptCurrent();
```

<br>

## 3. Related Components<a id="related-components"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- `AuthService::currentUser()` – Used to identify and exclude the current user in queries.
- `Pagination` – Responsible for paginating the user listing queries.
- `Users` – Eloquent-style model for fetching and counting user records.

<br>

## 4. Notes <a id="notes"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- All queries exclude the current logged-in user to prevent unintentional self-editing or display within administrative lists.
- The pagination relies on the `Pagination` class to generate SQL query parameters for the `Users` model.
- This service is intentionally minimal and can be extended with additional dashboard-specific helper methods such as widget registration, statistics blocks, or activity feeds.