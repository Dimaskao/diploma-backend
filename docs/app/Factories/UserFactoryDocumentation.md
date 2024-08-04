
# UserFactory Documentation

The `UserFactory` class is responsible for creating different types of users (regular users, companies, admins) and their associated profiles. It implements the `Factory` interface and provides methods for creating users based on their roles.

## Constants

- `REGULAR_USER_ID`: Key for regular user profile ID.
- `COMPANY_ID`: Key for company profile ID.
- `ADMIN_ID`: Key for admin profile ID.

## Methods

### create

```php
public function create($params = []): array
```

Creates a user based on the provided parameters and returns an array containing the user and the specific profile type.

- **Parameters:**
    - `params`: An associative array containing user details including `password`, `role`, and other role-specific information.

- **Returns:**
    - `array`: An array containing the created user and the specific profile type (e.g., `regular_user`, `company`, `admin`).

- **Throws:**
    - `Exception`: If required parameters are missing or the role is invalid.

### createRegularUser

```php
protected function createRegularUser(array $data): array
```

Creates a regular user and their associated profile.

- **Parameters:**
    - `data`: An associative array containing user details.

- **Returns:**
    - `array`: An array containing the created user and `regular_user`.

### createCompanyUser

```php
protected function createCompanyUser(array $data): array
```

Creates a company user and their associated profile.

- **Parameters:**
    - `data`: An associative array containing user details.

- **Returns:**
    - `array`: An array containing the created user and `company`.

### createAdminUser

```php
protected function createAdminUser(array $data): array
```

Creates an admin user and their associated profile.

- **Parameters:**
    - `data`: An associative array containing user details.

- **Returns:**
    - `array`: An array containing the created user and `admin`.

### profileData

```php
private function profileData($data, $key, $specificUser)
```

Creates a profile and updates the user data with the profile ID.

- **Parameters:**
    - `data`: An associative array containing user details.
    - `key`: The key for the specific profile type.
    - `specificUser`: The specific user instance (regular user, company, admin).

- **Returns:**
    - `array`: Updated user data with the profile ID.

### createBaseUser

```php
private function createBaseUser($data, $key, $specificUser)
```

Creates the base user and associates it with the specific profile type.

- **Parameters:**
    - `data`: An associative array containing user details.
    - `key`: The key for the specific profile type.
    - `specificUser`: The specific user instance (regular user, company, admin).

- **Returns:**
    - `User`: The created user instance.

## Example Usage

```php
use App\Factories\UserFactory;

$factory = new UserFactory();
$userData = [
    'password' => 'securepassword',
    'role' => 'regular_user',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john.doe@example.com',
];
$result = $factory->create($userData);

$user = $result['user'];
$regularUser = $result['regular_user'];
```
