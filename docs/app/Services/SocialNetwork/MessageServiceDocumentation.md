
# MessageService Documentation

The `MessageService` class manages message-related operations.

## Methods

### sendMessage

```php
public function sendMessage(array $data): JsonResponse
```

Sends a message in a chat.

- **Parameters:**
    - `data`: An associative array containing `chat_id`, `user_id`, and `content`.

- **Returns:**
    - `JsonResponse`: Created message information or error message.

### getMessages

```php
public function getMessages($chatId): JsonResponse
```

Retrieves messages from a chat.

- **Parameters:**
    - `chatId`: The ID of the chat.

- **Returns:**
    - `JsonResponse`: Success message with chat messages.

---
