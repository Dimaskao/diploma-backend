# Interfaces Documentation

This documentation covers the interfaces used in the application, detailing their purpose and methods.

## Factory Interface

The `Factory` interface defines a contract for creating instances based on given parameters. Implementing classes are responsible for providing the actual creation logic.

### Methods

#### create

```php
public function create(array $params = []);
```

Creates an instance based on the provided parameters.

- **Parameters:**
    - `params`: An associative array of parameters required for creating the instance.

- **Returns:**
    - `mixed`: The created instance.

## Example Implementation

```php
use App\Interfaces\Factory;

class UserFactory implements Factory
{
    public function create(array $params = [])
    {
        // Implementation of user creation logic
    }
}
```

## ProfileStrategy Interface

The `ProfileStrategy` interface defines a contract for managing user profiles. Implementing classes are responsible for providing the logic for displaying, updating, and deleting profiles.

### Methods

#### show

```php
public function show($id): JsonResponse;
```

Displays the profile of the user with the given ID.

- **Parameters:**
    - `id`: The ID of the user whose profile is to be displayed.

- **Returns:**
    - `JsonResponse`: The JSON response containing the user's profile.

#### update

```php
public function update(Request $request, $id): JsonResponse;
```

Updates the profile of the user with the given ID based on the provided request data.

- **Parameters:**
    - `request`: The request containing the data to update the profile.
    - `id`: The ID of the user whose profile is to be updated.

- **Returns:**
    - `JsonResponse`: The JSON response indicating the result of the update operation.

#### deleteProfile

```php
public function deleteProfile($id): JsonResponse;
```

Deletes the profile of the user with the given ID.

- **Parameters:**
    - `id`: The ID of the user whose profile is to be deleted.

- **Returns:**
    - `JsonResponse`: The JSON response indicating the result of the delete operation.

## Example Implementation

```php
use App\Interfaces\ProfileStrategy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegularUserProfileStrategy implements ProfileStrategy
{
    public function show($id): JsonResponse
    {
        // Implementation of showing user profile
    }

    public function update(Request $request, $id): JsonResponse
    {
        // Implementation of updating user profile
    }

    public function deleteProfile($id): JsonResponse
    {
        // Implementation of deleting user profile
    }
}
```

## SpecificProfileService Interface

The `SpecificProfileService` interface defines a contract for specific profile services, such as those for regular users, companies, and admins. Implementing classes are responsible for providing the logic for retrieving, updating, and deleting profiles.

### Methods

#### getProfile

```php
public function getProfile(User $user): JsonResponse;
```

Retrieves the profile of the given user.

- **Parameters:**
    - `user`: The user whose profile is to be retrieved.

- **Returns:**
    - `JsonResponse`: The JSON response containing the user's profile.

#### updateProfile

```php
public function updateProfile(User $user, Request $request): JsonResponse;
```

Updates the profile of the given user based on the provided request data.

- **Parameters:**
    - `user`: The user whose profile is to be updated.
    - `request`: The request containing the data to update the profile.

- **Returns:**
    - `JsonResponse`: The JSON response indicating the result of the update operation.

#### deleteProfile

```php
public function deleteProfile($id): JsonResponse;
```

Deletes the profile with the given ID.

- **Parameters:**
    - `id`: The ID of the profile to be deleted.

- **Returns:**
    - `JsonResponse`: The JSON response indicating the result of the delete operation.

## Example Implementation

```php
use App\Interfaces\SpecificProfileService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegularUserProfileService implements SpecificProfileService
{
    public function getProfile(User $user): JsonResponse
    {
        // Implementation of retrieving user profile
    }

    public function updateProfile(User $user, Request $request): JsonResponse
    {
        // Implementation of updating user profile
    }

    public function deleteProfile($id): JsonResponse
    {
        // Implementation of deleting user profile
    }
}
```

These interfaces ensure that the implementations follow a consistent structure and provide the required functionality for user profile management and object creation within the application.
