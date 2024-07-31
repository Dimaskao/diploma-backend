
# SocialNetworkService Documentation

The `SocialNetworkService` class handles the business logic for the social network-related functionalities, delegating specific tasks to other services.

## Methods

### search

```php
public function search(Request $request): JsonResponse
```

Delegates the search functionality to the `SearchService`.

### subscribe

```php
public function subscribe(Request $request): JsonResponse
```

Delegates the subscription functionality to the `SubscriptionService`.

### unsubscribe

```php
public function unsubscribe(Request $request): JsonResponse
```

Delegates the unsubscription functionality to the `SubscriptionService`.

### createChat

```php
public function createChat(Request $request): JsonResponse
```

Delegates the chat creation functionality to the `ChatService`.

### addUserToChat

```php
public function addUserToChat(Request $request): JsonResponse
```

Delegates the functionality to add a user to a chat to the `ChatService`.

### sendMessage

```php
public function sendMessage(Request $request): JsonResponse
```

Delegates the message sending functionality to the `MessageService`.

### getMessages

```php
public function getMessages(int $chatId): JsonResponse
```

Delegates the functionality to retrieve messages from a chat to the `MessageService`.

---
