
# SearchService Documentation

The `SearchService` class manages search-related operations.

## Methods

### search

```php
public function search(Request $request): JsonResponse
```

Performs a search based on the request parameters.

- **Parameters:**
    - `request`: The search request containing `search_type` and `query`.

- **Returns:**
    - `JsonResponse`: Success message with search results or error message.

---
