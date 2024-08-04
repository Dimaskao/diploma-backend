
## BaseEnum

This documentation covers the enums used in the application, detailing their purpose and constants. 

### *Additionally, it specifies that the ID in all operations involving a user is passed and returned from the User model.*

### Methods

#### values

```php
public static function values(): array
```

Returns an array of the enum values.

- **Returns:**
    - `array`: An array of the enum values.

#### toArray

```php
public static function toArray(): array
```

Converts the enum to an associative array with values as keys and names as values.

- **Returns:**
    - `array`: An associative array of the enum values and names.

## Edit

The `Edit` enum defines constants for different edit actions.

### Constants

- `ADD`: `'add'`
- `REMOVE`: `'remove'`
- `BAN`: `'ban'`
- `UNBAN`: `'unban'`
- `BAN_USERS`: `'ban_users'`
- `EDIT_INFO`: `'edit_info'`
- `BAN_POSTS`: `'ban_posts'`
- `UNBAN_USERS`: `'unban_users'`
- `UNBAN_POSTS`: `'unban_posts'`

## Method

The `Method` enum defines constants for different HTTP methods.

### Constants

- `GET`: `'get'`
- `UPDATE`: `'update'`
- `DELETE`: `'delete'`

## Period

The `Period` enum defines constants for different periods.

### Constants

- `PRESENT`: `'present'`

## Permission

The `Permission` enum defines constants for different permission levels.

### Constants

- `READ`: `'read'`
- `WRITE`: `'write'`
- `EDIT`: `'edit'`
- `FULL`: `'full'`

## ResponseKey

The `ResponseKey` enum defines constants for different response keys used in JSON responses.

### Constants

- `PROFILE`: `'profile'`
- `USER`: `'user'`
- `USERS`: `'users'`
- `COMPANIES`: `'companies'`
- `EDUCATION`: `'education'`
- `WORK_EXPERIENCE`: `'work_experience'`
- `SKILLS`: `'skills'`
- `MESSAGE`: `'message'`
- `UPDATED_INFORMATION`: `'updated_information'`
- `RESULT`: `'success'`
- `ERROR`: `'error'`
- `TOKEN`: `'token'`
- `COMPANY`: `'company'`
- `POSTS`: `'posts'`
- `JOB_OFFERS`: `'job_offers'`
- `ADMIN`: `'admin'`

## SearchType

The `SearchType` enum defines constants for different search types.

### Constants

- `USERS`: `'users'`
- `COMPANIES`: `'companies'`
- `ALL`: `'all'`
- `SEARCH_TYPE`: `'search_type'`

## SubscriptionAction

The `SubscriptionAction` enum defines constants for different subscription actions.

### Constants

- `SUBSCRIBE`: `'subscribe'`
- `UNSUBSCRIBE`: `'unsubscribe'`

## UpdateType

The `UpdateType` enum defines constants for different update types.

### Constants

- `UPDATE_TYPE`: `'update_type'`
- `PERSONAL_INFORMATION`: `'personal_information'`
- `EDUCATION`: `'education'`
- `WORK_EXPERIENCE`: `'work_experience'`
- `SKILLS`: `'skills'`
- `SELF`: `'self'`
- `ANOTHER_ADMIN_PERMISSIONS`: `'another_admin_permissions'`
- `BAN_UNBAN`: `'ban_unban'`

## UserRole

The `UserRole` enum defines constants for different user roles.

### Constants

- `REGULAR_USER`: `'user'`
- `COMPANY`: `'company'`
- `ADMIN`: `'admin'`

These enums provide a structured and consistent way to handle various constant values within the application, improving code readability and maintainability.
