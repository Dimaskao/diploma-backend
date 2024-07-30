# ProfileController Documentation

## Description

The ProfileController is responsible for handling requests related to user profiles. It interacts with the ProfileService to perform various operations such as showing, updating, and deleting user profiles.

## Class: ProfileController

### Properties

* __$service__: An instance of the __ProfileService__ class. This service is used to handle the business logic related to user profiles.

### Constructor

```
	public function __construct(ProfileService $profileService)
```

* __Parameters:__
  An instance of the __ProfileService__ class. This service is used to handle the business logic related to user profiles.
    * __ProfileService $profileService:__ : An instance of the __ProfileService__ class.
* __Description:__ Initializes the controller with the given ProfileService instance.

###  Methods:

__show__

```
	public function show($id): JsonResponse
```
* __Parameters:__
    * __$id (mixed):__ The ID of the user whose profile is to be shown from the 'users' table.

* __Returns:__ _`JsonResponse`_

* __Description:__ Fetches and returns the profile information of the user with the given ID by calling the __show__ method of the __ProfileService__.

__update__

```
	public function update(Request $request, $id): JsonResponse
```

* __Parameters:__
    * __Request $request:__  The request object containing the data for updating the profile.

* __Returns:__ _`JsonResponse`_

* __Description:__ Updates the profile of the user with the given ID using the data from the request. Calls the __update__ method of the __ProfileService__.

__destroy__

```
	public function destroy($id): JsonResponse
```

* __Parameters:__
    * __$id (mixed):__ The ID of the user whose profile is to be deleted from the 'users' table.

* __Returns:__ _`JsonResponse`_

* __Description:__  Deletes the profile of the user with the given ID by calling the __deleteProfile__ method of the __ProfileService__.

## Usage Example

### Showing a User Profile

```
	Route::get('/profile/{id}', [ProfileController::class, 'show']);
```

This route definition maps a GET request to the __show__ method of the __ProfileController__. When a request is made to __/profile/{id}__, the __show__ method will be invoked to return the profile information of the user with the specified ID.

### Updating a User Profile

```
	Route::put('/profile/{id}', [ProfileController::class, 'update']);
```

This route definition maps a PUT request to the __update__ method of the __ProfileController__. When a request is made to __/profile/{id}__, the __update__ method will be invoked to update the profile of the user with the specified ID using the data from the request body

### Deleting a User Profile

```
	Route::delete('/profile/{id}', [ProfileController::class, 'destroy']);
```

his route definition maps a DELETE request to the __destroy__ method of the __ProfileController__. When a request is made to __/profile/{id}__, the __destroy__ method will be invoked to delete the profile of the user with the specified ID.
