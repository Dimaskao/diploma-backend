# ProfileStrategyFactory Documentation

The `ProfileStrategyFactory` class is responsible for creating instances of `ProfileStrategy` based on the user's role. It implements the `Factory` interface and provides a standardized way to instantiate the correct profile strategy service.

## Methods

### create

```php
public function create($params = []): ProfileStrategy
```

Creates and returns an instance of `ProfileStrategy` based on the user's role.

- **Parameters:**
    - `params`: An associative array containing the user ID (`id`).

- **Returns:**
    - `ProfileStrategy`: An instance of the appropriate `ProfileStrategy` implementation.

- **Throws:**
    - `Exception`: If the user ID is not found in the parameters, if the user is not found, or if the user role is invalid.

## Example Usage

```php
use App\Factories\ProfileStrategyFactory;

$factory = new ProfileStrategyFactory();
$profileStrategy = $factory->create(['id' => $userId]);
```

---
