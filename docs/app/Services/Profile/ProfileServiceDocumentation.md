
---

# Profile Services Documentation

## UserProfileService

### Overview
The `UserProfileService` class is part of the application's service layer responsible for managing user profiles. It extends the base functionalities provided by `BaseProfileService` and includes additional features specific to user profiles, such as creating, updating, and deleting profile information, as well as handling specific user-related profile tasks.

### Methods
- **createProfile($data)**: Creates a new user profile with the provided data.
- **updateProfile($userId, $data)**: Updates the user profile identified by `$userId` with the provided data.
- **deleteProfile($userId)**: Deletes the user profile identified by `$userId`.
- **getProfile($userId)**: Retrieves the profile information for the user identified by `$userId`.

### Usage Example
```php
// Create instances of the dependencies
$userProfileService = new UserProfileService(new ProfileStrategyFactory(), new ResponseService());

$user = User::find('specificTestId')

// Get user profile
$response = $userProfileService->getProfile($user->id);

// Update user profile
$response = $userProfileService->updateProfile($request, $id);

// Delete user profile
$response = $userProfileService->deleteProfile($id);

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
$request =  [
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

## app/Services/Profile Directory

### BaseProfileService.php
The `BaseProfileService` class is the parent service that provides common functionalities for handling profiles. It includes shared methods and properties used by the specific profile services.

### SpecificProfile Services
These services extend `BaseProfileService` and add functionalities unique to different types of profiles.

#### AdminProfileService.php
The `AdminProfileService` class manages admin-specific profile functionalities, including admin-specific settings and permissions.

#### CompanyProfileService.php
The `CompanyProfileService` class handles operations for company profiles, such as managing company information, job postings, and company-specific interactions.

#### RegularUserProfileService.php
The `RegularUserProfileService` class deals with the profile management of regular users, including creating, updating, and retrieving user-specific information.

### Handlers
Handlers are specialized classes responsible for executing specific tasks related to profile operations. They are organized into `Delete`, `Get`, and `Update` handlers.

#### Delete Handlers

##### DeleteHandler.php
The `DeleteHandler` class manages the deletion of user profiles. It ensures that all associated data is correctly removed.

#### Get Handlers

##### GetHandler.php
The `GetHandler` class retrieves profile information. It provides methods to fetch various aspects of a user's profile.

##### Helpers
Helper classes assist `GetHandler` by providing additional methods to fetch detailed information.

- **EducationGetHelper.php**: Assists in fetching education details from profiles.
- **ProfileGetHelper.php**: Aids in obtaining general profile information.
- **SkillsGetHelper.php**: Helps in retrieving skills data from profiles.
- **WorkExperienceGetHelper.php**: Assists in getting work experience information.

#### Update Handlers

##### UpdateHandler.php
The `UpdateHandler` class manages the updating of user profiles. It includes methods to handle the modification of profile data.

##### Helpers
Helper classes assist `UpdateHandler` by providing additional methods to update detailed information.

- **ProfileUpdateHelper.php**: Assists in updating general profile information.
- **SkillsUpdateHelper.php**: Helps in updating skills data.
- **UserEducationUpdateHelper.php**: Aids in updating education details.
- **WorkExperienceUpdateHelper.php**: Assists in updating work experience information.

---
