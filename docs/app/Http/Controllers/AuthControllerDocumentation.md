# AuthController Documentation

## Overview
The `AuthController` class is responsible for handling authentication-related HTTP requests. It acts as an intermediary between the client and the `AuthService`, processing input from the client, invoking the appropriate service methods, and returning the responses.

## Dependencies
- **AuthService**: A service class for handling authentication logic.

## Methods

### `__construct(AuthService $service)`
Constructor for initializing the `AuthController` with an `AuthService`.

#### Parameters
- **AuthService $service**: An instance of the `AuthService` class.

### `register(Request $request): JsonResponse`
Registers a new user or company based on the provided request data.

#### Parameters
- **Request $request**: The request object containing the registration data.

#### Returns
- **JsonResponse**: The registration result or an error message.

### `login(Request $request): JsonResponse`
Logs in a user or company based on the provided request data.

#### Parameters
- **Request $request**: The request object containing the login credentials.

#### Returns
- **JsonResponse**: The login result or an error message.

### `logout(Request $request): JsonResponse`
Logs out the authenticated user.

#### Parameters
- **Request $request**: The request object containing the user information.

#### Returns
- **JsonResponse**: A success message or an error message.

## Usage Example

```php
use App\Http\Controllers\AuthController;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

// Create an instance of AuthService
$authService = new AuthService(new ValidationService(), new UserFactory(), new ResponseService());

// Create an instance of AuthController with the dependencies
$authController = new AuthController($authService);

// Register a new user
$request = new Request([
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john.doe@example.com',
    'password' => 'secret123',
    'password_confirmation' => 'secret123',
    'role' => 'user'
]);
$response = $authController->register($request);

// Log in a user
$request = new Request([
    'email' => 'john.doe@example.com',
    'password' => 'secret123',
    'role' => 'user'
]);
$response = $authController->login($request);

// Log out a user
$request = new Request();
$response = $authController->logout($request);
```
---
