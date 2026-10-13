<h1 style="font-size: 50px; text-align: center;">DashboardService</h1>

## Table of contents
1. [Overview](#overview)
2. [Public Methods](#public-methods)
3. [Related Components](#related-components)
4. [Notes](#notes)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `DashboardService` class provides helper functions for administrative dashboard operations, including access restrictions and the searchable, filterable, paginated user list. It ensures that the logged-in admin user cannot edit or view their own profile via the dashboard. It supports both PHP and React versions of the dashboard.

<br>

**Setup**
```php
use Core\Services\DashboardService;
```

<br>

**Common Use Cases**
- Prevent the logged-in admin from editing or viewing their own profile via the dashboard.
- Retrieve a paginated list of users, excluding the current admin, with search, filters, and sorting.
- Count the users that match the current search and filters.
- Build props for the React version of the dashboard.

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

### B. `userFilter()`
Builds the search and filter settings for the user list. The current user is always excluded.

- Search: `username`, `email`, `fname`, and `lname`.
- Filters: access level (`acl`) using the levels in the ACL table, and status (`inactive`) using `STATUS_OPTIONS`.
- Sorting: `username`, `acl`, `inactive`, `created_at`, and `updated_at`. The default is newest first.

Parameter:
- `object $request` - The request object.

Returns:
- `SearchFilter` - The filter for the user list.

```php
$filter = DashboardService::userFilter($this->request);
```

<br>

### C. `paginateUsers()`
Returns the users for the current page that match the filter.

Parameters:
- `Pagination $pagination` - Instance of Pagination class.
- `SearchFilter $filter` - The filter from `userFilter()`.

Returns:
- `array` - An array of paginated users.

```php
$users = DashboardService::paginateUsers($pagination, $filter);
```

<br>

### D. `totalUsers()`
Returns the number of users that match the filter. Use it as the total when creating the Pagination object.

Parameter:
- `SearchFilter $filter` - The filter from `userFilter()`.

Returns:
- `int` - The number of matching users.

```php
$pagination = Pagination::fromRequest($this->request, 10, DashboardService::totalUsers($filter))
    ->withFilter($filter);
```

<br>

### E. `userList()`
Builds the complete user list for the React dashboard: the current page of users, the pagination state, and the filter state, all as arrays. Used for both the first render and the JSON responses.

Parameters:
- `object $request` - The request object.
- `int $perPage` - Users per page. The default is 10.

Returns:
- `array` - An array with the keys `users`, `pagination`, and `filter`.

```php
$list = DashboardService::userList($this->request);
```

<br>

### F. `userToArray()`
Converts a user to an array for the React dashboard. Only the fields the table needs are included, so password hashes and tokens are never sent to the browser. Access levels are returned as a list, and dates include a "time ago" version.

Parameter:
- `Users $user` - The user.

Returns:
- `array` - The fields `id`, `username`, `acl`, `inactive`, `created_at`, `updated_at`, `created_ago`, and `updated_ago`.

```php
$users = array_map([DashboardService::class, 'userToArray'], $users);
```

<br>

### G. `optionList()`
Converts a `value => label` array into a list of `value` and `label` pairs for React dropdowns. This keeps the order and avoids PHP turning `'0'` and `'1'` keys into a plain JSON list.

Parameter:
- `array $options` - The options as `value => label`.

Returns:
- `array` - A list of `['value' => ..., 'label' => ...]` arrays.

```php
'statusOptions' => DashboardService::optionList(DashboardService::STATUS_OPTIONS),
```

<br>

### H. `STATUS_OPTIONS`
The options for the status filter, as `value => label`:

```php
public const STATUS_OPTIONS = [
    '0' => 'Active',
    '1' => 'Inactive',
];
```

<br>

## 3. Related Components<a id="related-components"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- `AuthService::currentUser()` – Used to identify and exclude the current user in queries.
- `ACL::getOptionsForForm()` – Provides the access levels for the access level filter.
- `Pagination` and `SearchFilter` – Handle paging, search, filters, and sorting. See the [Pagination](pagination) page.
- `Users` – Model for fetching and counting user records.

<br>

## 4. Notes <a id="notes"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- All queries exclude the current logged-in user to prevent unintentional self-editing or display within administrative lists.
- `totalUserCountExceptCurrent()` was replaced by `totalUsers()`, which counts only the users that match the current search and filters.
- See [Supporting PHP and React Views Together](pagination#both) for a controller that uses this service for both view types.
- This service is intentionally minimal and can be extended with additional dashboard-specific helper methods such as widget registration, statistics blocks, or activity feeds.