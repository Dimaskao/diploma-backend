# UserProfileController Documentation

## Overview
The `UserProfileController` class is responsible for handling HTTP requests related to user profiles. It acts as an intermediary between the client and the `UserProfileService`, processing input from the client, invoking the appropriate service methods, and returning the responses.

## Dependencies
- **UserProfileService**: A service class for managing user profiles.

## Methods

### `__construct(UserProfileService $service)`
Constructor for initializing the `UserProfileController` with a `UserProfileService`.

#### Parameters
- **UserProfileService $service**: An instance of the `UserProfileService` class.

### `show($id): JsonResponse`
Retrieves the profile for the user identified by `$id`.

#### Parameters
- **$id**: The ID of the base user whose profile is to be retrieved.

#### Returns
- **JsonResponse**: The profile data or an error message.

### `update(Request $request, $id): JsonResponse`
Updates the profile for the user identified by `$id` with the data provided in the `$request`.

#### Parameters
- **Request $request**: The request object containing the profile data to be updated.
- **$id**: The ID of the user whose profile is to be updated.

#### Returns
- **JsonResponse**: The updated profile data or an error message.

### `destroy($id): JsonResponse`
Deletes the profile for the user identified by `$id`.

#### Parameters
- **$id**: The ID of the user whose profile is to be deleted.

#### Returns
- **JsonResponse**: A success message or an error message.

## Usage Example

```php
use App\Http\Controllers\ProfileController;
use App\Services\Profile\UserProfileService;
use App\Factories\ProfileStrategyFactory;
use App\Services\Response\ResponseService;
use Illuminate\Http\Request;
use App\Models\User;

// Create instances of the dependencies
$profileStrategyFactory = new ProfileStrategyFactory();
$responseService = new ResponseService();
$userProfileService = new UserProfileService($profileStrategyFactory, $responseService);

// Create an instance of ProfileController with the dependencies
$profileController = new ProfileController($userProfileService);

// Find a user
$user = User::find('specificTestId');

// Show user profile
$response = $profileController->show($user->id);

// Update user profile
$request = new Request([
    // This would be replaced by getRegularUserUpdateRequestData() or getAdminUpdateRequestData() method output
]);
$response = $profileController->update($request, $user->id);

// Delete user profile
$response = $profileController->destroy($user->id);
```

### Update Requests Structure

#### *UserRole::ADMIN*

```php
$request = [
    'update_type' => [
        'self' => [
            'personal_information' => [
                'name' => 'Moderator test admin',
                'password' => 'test admin password',
                'avatar_url' => 'https://avatar-url.test.com'
            ]
        ],
        'another_admin_permissions' => [
            'update_admin_id' => $base->id,
            'permissions' => [
                'edit' => true,
                'read' => true,
                'write' => false,
                'full' => false
            ]
        ],
        'ban_unban' => [
            'edit_info' => [
                'ban' => [
                    'ban_users' => [
                        [
                            'user_id_to_ban' => $base->id,
                            'reason' => 'Bot'
                        ]
                    ],
                    'ban_posts' => [
                        [
                            'post_id_to_ban' => $post->id,
                            'reason' => 'Harm content'
                        ]
                    ],
                ],
                'unban' => [
                    'unban_users' => [
                        [
                            'user_id_to_unban' => $base->id
                        ]
                    ],
                    'unban_posts' => [
                        [
                            'post_id_to_unban' => $post->id
                        ]
                    ]
                ]
            ]
        ],
        "skills" => [
            [
                "edit_info" => 'add',
                "name" => 'team lead'
            ],
            [
                "edit_info" => 'remove',
                'name' => 'team lead'
            ]
        ],
    ]
];
```

#### *UserRole::REGULAR_USER*

```php
$request = [
    'update_type' => [
        'personal_information' => [
            'first_name' => 'Johnny',
            'last_name' => 'Johnson',
            "skills_desc" => "Senior Developer",
            "experience" => "7 years",
        ],
        'education' => [
            [
                'id' => 1,
                'institution' => 'University X',
                'degree' => 'Bachelor"s in Computer Science',
                'field_of_study' => 'Computer Science',
                'contact_url' => 'https://universityx.edu/'
            ]
        ],
        'work_experience' => [
            [
                "position" => "Senior Developer",
                "company_name" => "Tech Company",
                "date_start" => "2022-01-01",
                "date_end" => 'present',
                "description" => "Leading development teams"
            ]
        ],
        "skills" => [
            [
                "id" => "1",
                "edit_info" => 'add',
            ],
            [
                "id" => "1",
                "edit_info" => 'remove'
            ]
        ]
    ]
];
```

#### *UserRole::COMPANY*

```php
$request = [
    'update_type' => [
        'personal_information' => [
            'name' => 'Company X update test',
            'contact_email' => 'company_x@test.com',
            "contact_phone" => "+14189481515",
            "password" => "new password",
            "contact_url" => "https://contact-url.test.com",
            "avatar_url" => "https://avatar-url.test.com",
        ]
    ]
];
```

---
