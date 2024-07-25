# ProfileService

## Description

The `ProfileService` class is responsible for managing user profiles. It utilizes the `ProfileStrategyFactory` to perform various profile operations such as displaying, updating, and deleting profiles.

## Properties

- **$factory**: An instance of the `Factory` interface used to create profile strategy objects.

## Constructor

### __construct

```php
public function __construct(ProfileStrategyFactory $factory)
```

**Parameters**:
- `ProfileStrategyFactory $factory`: An instance of the profile strategy factory.

**Description**: Initializes the service with the given profile strategy factory instance.

## Methods

### show

```php
public function show($id): JsonResponse
```

**Parameters**:
- `$id` (mixed): The ID of the user whose profile is to be displayed.

**Returns**: `JsonResponse`

**Description**: Retrieves and returns the profile information of the user with the given ID by invoking the `show` method of the appropriate profile strategy. In case of an error, it returns a JSON response with an error message and a 404 status code.

### update

```php
public function update(Request $request, $id): JsonResponse
```

**Parameters**:
- `Request $request`: The request object containing data for updating the profile.
- `$id` (mixed): The ID of the user whose profile is to be updated.

**Returns**: `JsonResponse`

**Description**: Updates the profile of the user with the given ID using the data from the request. Invokes the `update` method of the appropriate profile strategy. In case of an error, it returns a JSON response with an error message and a 404 status code.

### deleteProfile

```php
public function deleteProfile($id): JsonResponse
```

**Parameters**:
- `$id` (mixed): The ID of the user whose profile is to be deleted.

**Returns**: `JsonResponse`

**Description**: Deletes the profile of the user with the given ID by invoking the `deleteProfile` method of the appropriate profile strategy. In case of an error, it returns a JSON response with an error message and a 500 status code.
