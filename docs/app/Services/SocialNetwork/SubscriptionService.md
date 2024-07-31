
# SubscriptionService Documentation

The `SubscriptionService` class manages subscription-related operations.

## Methods

### subscribe

```php
public function subscribe(Request $request): JsonResponse
```

Subscribes a user to another user.

- **Parameters:**
    - `request`: The subscription request containing `subscriberId` is instance of User model ID and `subscriptionId` is instance of User model ID too.

- **Returns:**
    - `JsonResponse`: Success message or error message.

### unsubscribe

```php
public function unsubscribe(Request $request): JsonResponse
```

Unsubscribes a user from another user.

- **Parameters:**
    - `request`: The unsubscription request containing `subscriberId` and `subscriptionId`.

- **Returns:**
    - `JsonResponse`: Success message or error message.

---
