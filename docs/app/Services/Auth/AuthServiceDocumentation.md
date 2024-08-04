
# AuthService Documentation

## Overview
The `AuthService` class is responsible for handling authentication logic, including user registration, login, and logout. It validates input data, creates new users, generates authentication tokens, and manages user sessions.

## Dependencies
- **ValidationService**: A service class for validating input data.
- **UserFactory**: A factory class for creating new users.
- **ResponseService**: A service class for handling API responses.

## Methods

### `__construct(ValidationService $validationService, UserFactory $factory, ResponseService $responseService)`
Constructor for initializing the `AuthService` with necessary dependencies.

#### Parameters
- **ValidationService $validationService**: An instance of the `ValidationService` class.
- **UserFactory $factory**: An instance of the `UserFactory` class.
- **ResponseService $responseService**: An instance of the `ResponseService` class.

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

## Helper Methods

### `registrationRules(): array`
Defines the validation rules for registration.

#### Returns
- **array**: The validation rules for registration.

### `loginRules(): array`
Defines the validation rules for login.

#### Returns
- **array**: The validation rules for login.

### `userLogin(array $credentials, string $role)`
Handles the login process for a user, generating an authentication token if successful.

#### Parameters
- **array $credentials**: The login credentials (email and password).
- **string $role**: The role of the user (user, company, admin).

#### Returns
- **string|bool**: The authentication token if successful, or `false` if login fails.

### `userLogout($user): void`
Handles the logout process for a user, revoking all authentication tokens.

#### Parameters
- **$user**: The authenticated user.

## Usage Example

### Registration Credentials Example

### *UserRole::ADMIN*

```php
$request = new Request([
    'name' => 'Test Admin test',
    'email' => 'moderator.test@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'role' => 'admin',
    'permissions' => [
        'read' => true,
        'edit' => true,
        'write' => true,
        'full' => true
]);
```

### *UserRole::REGULAR_USER*

```php
$request = new Request([
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john.doe@example.com',
    'password' => 'secret123',
    'password_confirmation' => 'secret123',
    'role' => 'user'
]);
```

### *UserRole::COMPANY*

```php
$request = new Request([
    'name' => 'Company X',
    'email' => 'test.company.x@example.com',
    'password' => 'secret123',
    'password_confirmation' => 'secret123',
    'role' => 'company'
]);
```

```php
use App\Factories\UserFactory;use App\Services\Auth\AuthService;use App\Services\Response\ResponseService;use App\Services\Validation\ValidationService;use Illuminate\Http\Request;

// Create instances of the dependencies
$validationService = new ValidationService();
$userFactory = new UserFactory();
$responseService = new ResponseService();

// Create an instance of AuthService with the dependencies
$authService = new AuthService($validationService, $userFactory, $responseService);

// Register a new user

$response = $authService->register($request);

// Log in a user
$request = new Request([
    'email' => 'john.doe@example.com',
    'password' => 'secret123',
    'role' => 'user'
]);
$response = $authService->login($request);

// Log out a user
$request = new Request();
$response = $authService->logout($request);
```

---
