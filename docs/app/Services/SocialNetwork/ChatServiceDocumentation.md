
# ChatService Documentation

The `ChatService` class manages chat-related operations.

## Methods

### createChat

```php
public function createChat(array $data): JsonResponse
```

Creates a new chat.

- **Parameters:**
    - `data`: An associative array containing `name`, `is_group`, and `user_id`.

- **Returns:**
    - `JsonResponse`: Created chat information or error message.

### addUserToChat

```php
public function addUserToChat($chatId, $userId): JsonResponse
```

Adds a user to an existing chat.

- **Parameters:**
    - `chatId`: The ID of the chat.
    - `userId`: The ID of the user being added to the chat.

- **Returns:**
    - `JsonResponse`: Success message or error message.

---
