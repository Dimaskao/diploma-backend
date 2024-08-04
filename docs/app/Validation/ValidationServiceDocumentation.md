# ValidationService

The `ValidationService` class is a service that provides a standardized way to validate data using Laravel's built-in validation capabilities. It ensures consistency and simplicity in the way data validation is performed across the application.

## Methods

### validate

```php
public function validate(array $data, array $rules): array
```

This method validates the given data against the provided validation rules. If the validation fails, a `ValidationException` is thrown. If the validation passes, the validated data is returned.

- **Parameters:**
    - `$data`: An associative array of the data to be validated.
    - `$rules`: An associative array of validation rules to apply to the data.

- **Returns:**
    - `array`: The validated data.

- **Throws:**
    - `ValidationException`: If the validation fails, this exception is thrown containing the validation errors.

## Example Usage

Here is an example of how to use the `ValidationService` in a controller or another part of your application:

```php
use App\Services\ValidationService;
use Illuminate\Validation\ValidationException;

class SomeController
{
    protected $validationService;

    public function __construct(ValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ];

        try {
            $validatedData = $this->validationService->validate($data, $rules);
            // Proceed with storing the validated data
        } catch (ValidationException $e) {
            // Handle the validation exception
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
}
```

In this example:
- The `validate` method is used to validate the incoming request data against the specified rules.
- If validation fails, a `ValidationException` is thrown and caught, and the validation errors are returned in the response with a 422 status code.
- If validation passes, the validated data can be used for further processing, such as storing in the database.

This service encapsulates the validation logic, making it reusable and keeping controllers and other parts of the application clean and focused on their primary responsibilities.
