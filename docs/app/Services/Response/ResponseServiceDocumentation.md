# ResponseService

The `ResponseService` class is a service that provides standardized JSON responses for an API built with Laravel. It ensures consistency and simplicity in the way responses are formatted and returned to the client.

## Response Format

All responses returned by this service will have the following JSON format:

```json
{
  "data": <mixed>,
  "message": <string>
}
```

- **data**: The payload of the response. It can be any type of data (e.g., object, array, string, null).
- **message**: A message describing the response, typically used for success or error messages.

## Methods

### response

```php
public function response($data, $message = '', $statusCode = 200): JsonResponse
```

This method standardizes the response data and returns a JSON response.

- **Parameters:**
    - `$data`: The data to be included in the response.
    - `$message` (optional): A message to be included in the response. Default is an empty string.
    - `$statusCode` (optional): The HTTP status code of the response. Default is 200.

- **Returns:**
    - `JsonResponse`: The standardized JSON response.

### success

```php
public function success($data = null, $message = 'Success'): JsonResponse
```

Returns a success response with a default message of "Success".

- **Parameters:**
    - `$data` (optional): The data to be included in the response. Default is `null`.
    - `$message` (optional): A message to be included in the response. Default is "Success".

- **Returns:**
    - `JsonResponse`: The success JSON response with status code 200.

### created

```php
public function created($data = null, $message = 'Created'): JsonResponse
```

Returns a created response with a default message of "Created".

- **Parameters:**
    - `$data` (optional): The data to be included in the response. Default is `null`.
    - `$message` (optional): A message to be included in the response. Default is "Created".

- **Returns:**
    - `JsonResponse`: The created JSON response with status code 201.

### badRequest

```php
public function badRequest($message = 'Bad Request'): JsonResponse
```

Returns a bad request response with a default message of "Bad Request".

- **Parameters:**
    - `$message` (optional): A message to be included in the response. Default is "Bad Request".

- **Returns:**
    - `JsonResponse`: The bad request JSON response with status code 400.

### unauthorized

```php
public function unauthorized($message = 'Unauthorized'): JsonResponse
```

Returns an unauthorized response with a default message of "Unauthorized".

- **Parameters:**
    - `$message` (optional): A message to be included in the response. Default is "Unauthorized".

- **Returns:**
    - `JsonResponse`: The unauthorized JSON response with status code 401.

### forbidden

```php
public function forbidden($message = 'Forbidden'): JsonResponse
```

Returns a forbidden response with a default message of "Forbidden".

- **Parameters:**
    - `$message` (optional): A message to be included in the response. Default is "Forbidden".

- **Returns:**
    - `JsonResponse`: The forbidden JSON response with status code 403.

### notFound

```php
public function notFound($message = 'Not Found'): JsonResponse
```

Returns a not found response with a default message of "Not Found".

- **Parameters:**
    - `$message` (optional): A message to be included in the response. Default is "Not Found".

- **Returns:**
    - `JsonResponse`: The not found JSON response with status code 404.

### internalServerError

```php
public function internalServerError($message = 'Internal Server Error'): JsonResponse
```

Returns an internal server error response with a default message of "Internal Server Error".

- **Parameters:**
    - `$message` (optional): A message to be included in the response. Default is "Internal Server Error".

- **Returns:**
    - `JsonResponse`: The internal server error JSON response with status code 500.
