<h1 style="font-size: 50px; text-align: center;">ACLService</h1>

## Table of contents
1. [Overview](#overview)
2. [Public Methods](#public-methods)
3. [Notes](#notes)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
The `ACLService` class provides a collection of static methods for managing Access Control Lists (ACLs) associated with users. ACLs define role-based permissions and are stored as a JSON-encoded array in each user's `acl` field.

This service helps enforce permission rules across the application while maintaining clean separation from controller logic.

<br>

**Setup**
```php
use Core\Services\AclService;
```

<br>

**Common Use Cases**
- Assign or remove ACLs from users
- Determine which ACLs are in use
- Prevent deletion of ACLs assigned to users
- Set default ACLs at registration

<br>

## 2. Public Methods <a id="public-methods"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
### A. `aclsForUser()`

Returns an array containing access control list information.  When the $acl instance variable is empty an empty array is returned.

Parameter:
- `Users $user` - The user whose ACLs we want to use.

Return:
- `array` - The array containing access control list information.

```php
$acls = ACLService::aclsForUser($user);
```
<br>

### B. `aclToArray()`
Ensures that we are always dealing with an array of ACLs.

Parameter:
- `mixed $acls` - An array or any type that we want to add to an array.

Returns:
- `array` - An array of acls.

```php
$normalized = ACLService::aclToArray(['Admin', 'Manager']);
```

<br>

### C. `addAcl(int $user_id, string $acl): bool`

Adds a new ACL string to a user's acl field.
```php
ACLService::addAcl(3, 'Manager');
```

<br>

### D. `removeAcl(int $user_id, string $acl): bool`

Removes an ACL string from a user's acl field.
```php
ACLService::removeAcl(3, 'Viewer');
```

<br>

### E. `checkACL(ACL $acl): void`

Redirects with a flash message if:
- The ACL does not exist, or
- The ACL is already assigned to users and cannot be modified.
```php
ACLService::checkACL($acl);
```

<br>

### F. `deleteIfAllowed(int $id): bool`

Deletes an ACL only if it is not assigned to any users.
```php
ACLService::deleteIfAllowed($aclId);
```

<br>

### G. `manageAcls(array $acls, Users $user, array $newAcls, array $userAcls): void`

Adds or removes ACLs from a user based on the differences between the new ACLs and existing ACLs.

Used internally by `updateUserACLs()`.

<br>

### H. `updateUserACLs(Users $user, array $userAcls, array $acls, ?array $postAcls = null): void`

Central method for updating a user's ACLs. Compares current ACLs with selected ones and saves the result.
```php
ACLService::updateUserACLs($user, $existing, $all, $posted);
```

<br>

### I. `setAclAtRegistration(): string`

Returns the default ACL value for a new user:
- "Admin" if no users exist yet
- "" (blank string) otherwise
```php
ACLService::saveACL($acl, $request);
```

<br>

### J. `saveACL(ACL $acl, Input $request): bool`

Assigns data to an ACL and saves it, using a blacklist if the ACL already exists.
```php
ACLService::saveACL($acl, $request);
```

<br>

### K. `usedACLs(): array`

Returns all ACL records currently assigned to at least one user.
```php
$used = ACLService::usedACLs();
```

<br>

### L. `unUsedACLs(): array`

Returns all ACL records not assigned to any users.
```php
$unused = ACLService::unUsedACLs();
```

<br>

## 3. Notes <a id="notes"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
- User ACLs are stored as JSON in the acl column of the users table.
- The service ensures consistent encoding and decoding of ACL data.
- Methods like checkACL() and deleteIfAllowed() are designed for use in admin-facing ACL management interfaces.
- This service should be used instead of direct access to $user->acl.