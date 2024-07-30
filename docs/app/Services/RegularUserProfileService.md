# RegularUserProfileService Documentation

## Public Methods

### `__construct()`
Constructor for the `RegularUserProfileService` class.
- **Parameters**: None
- **Returns**: None

### Example
```php
$profileService = new RegularUserProfileService();
```

### `getRegularUserProfile($user): JsonResponse`
Retrieves the profile of a regular user.
- **Parameters**:
    - `$user` (User): The user whose profile is to be retrieved.
- **Returns**: `JsonResponse` containing the user's profile information.

### Example
```php
$user = User::find(1); // Assuming you have a user with ID 1
$profileService = new RegularUserProfileService();
$response = $profileService->getRegularUserProfile($user);

return $response;
```

### `updateUserInformation($user, Request $request): JsonResponse`
Updates the information of a regular user based on the provided request.
- **Parameters**:
    - `$user` (User): The user whose information is to be updated.
    - `$request` (Request): The request containing the updated information.
- **Returns**: `JsonResponse` indicating the success or failure of the update.

### Example
```php
use Illuminate\Http\Request;

$user = User::find(1); // Assuming you have a user with ID 1
$request = new Request([
    'update_type' => [
         'personal_information' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'skills_desc' => 'PHP, Laravel',
            'experience' => '5 years'
        ]
    ]   
]);

$profil eService = new RegularUserProfileService();
$response = $profileService->updateUserInformation($user, $request);

return $response;
```

### `deleteRegularUserProfile($id): JsonResponse`
Deletes a regular user's profile and associated data.
- **Parameters**:
    - `$id` (int): The ID of the user whose profile is to be deleted.
- **Returns**: `JsonResponse` indicating the success or failure of the deletion.

### Example
```php
$profileService = new RegularUserProfileService();
$response = $profileService->deleteRegularUserProfile(1); // Assuming you want to delete the user with ID 1

return $response;
```
