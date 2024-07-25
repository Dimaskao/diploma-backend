# CompanyProfileService

## Description

The `CompanyProfileService` class is responsible for managing company profiles. It provides methods to get, update, and delete profiles, as well as manage related data such as job offers and posts.

## Properties

- **$validator**: An instance of the `ValidationService` used for validating input data.

## Constructor

### __construct

```php
public function __construct()
```

**Description**: Initializes the service with a new instance of `ValidationService`.

## Methods

### getCompanyProfile

```php
public function getCompanyProfile(mixed $user): JsonResponse
```

**Parameters**:
- `User $user`: The user whose company profile is to be retrieved.

**Returns**: `JsonResponse`

**Description**: Retrieves and returns the profile information of the given user's company.

### updateCompanyInformation

```php
public function updateCompanyInformation($user, Request $request): JsonResponse
```

**Parameters**:
- `User $user`: The user whose company profile is to be updated.
- `Request $request`: The request object containing data for updating the profile.

**Returns**: `JsonResponse`

**Description**: Updates the company information of the given user using the data from the request. Returns a JSON response indicating the result of the operation.

### deleteCompanyProfile

```php
public function deleteCompanyProfile($id): JsonResponse
```

**Parameters**:
- `$id` (User::id): The ID of the user whose company profile is to be deleted.

**Returns**: `JsonResponse`

**Description**: Deletes the company profile of the user with the given ID and returns a JSON response indicating the result of the operation.

### updateCompanyProfile

```php
private function updateCompanyProfile(array $personalInformation, $user, $baseUser)
```

**Parameters**:
- `array $personalInformation`: The personal information to be updated.
- `Company $user`: The user to be updated.
- `User $baseUser` (mixed): The base user record.

**Throws**:
- `ValidationException`: If validation fails.
- `Exception`: If there is an error while updating the profile.

**Description**: Updates the profile of the company with the provided personal information.

### updateByUpdateType

```php
private function updateByUpdateType($updateType, $user, $baseUser, array $updatedResults): array
```

**Parameters**:
- `$updateType` (mixed): The type of update to be performed.
- `Company $user`: The user to be updated.
- `User $baseUser`: The base user record.
- `array $updatedResults`: The results of the update.

**Returns**: `array`

**Throws**:
- `Exception`: If an error occurs during the update.

**Description**: Updates the company profile based on the update type and returns the updated results.

### getUserUpdateData

```php
private function getUserUpdateData(array $data): array
```

**Parameters**:
- `array $data`: The input data.

**Returns**: `array`

**Description**: Processes the input data and returns the updated base user data.

### getCompanyUpdateData

```php
private function getCompanyUpdateData(array $data): array
```

**Parameters**:
- `array $data`: The input data.

**Returns**: `array`

**Description**: Processes the input data and returns the updated company data.

### getCompanyJobOffers

```php
public function getCompanyJobOffers($company): array
```

**Parameters**:
- `Company $company`: The company whose job offers are to be retrieved.

**Returns**: `array`

**Description**: Retrieves and returns the job offers of the given company.

### getJobOfferSkills

```php
public function getJobOfferSkills($jobOffer): array
```

**Parameters**:
- `JobOffer $jobOffer`: The job offer whose skills are to be retrieved.

**Returns**: `array`

**Description**: Retrieves and returns the skills required for the given job offer.

### getUserPosts

```php
public function getUserPosts($user): array
```

**Parameters**:
- `User $user`: The user whose posts are to be retrieved.

**Returns**: `array`

**Description**: Retrieves and returns the posts of the given user.

### getPostImages

```php
public function getPostImages($post): array
```

**Parameters**:
- `Post $post`: The post whose images are to be retrieved.

**Returns**: `array`

**Description**: Retrieves and returns the images associated with the given post.
