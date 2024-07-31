# SocialNetworkController Documentation

The `SocialNetworkController` class serves as the entry point for handling social network-related API requests. It delegates the actual processing to the `SocialNetworkService` class, ensuring that the controller remains thin and focused on request handling.

## Endpoints

### subscribe

```php
public function subscribe(Request $request): JsonResponse
```

Handles subscription requests.

- **URL:** `/api/subscribe`
- **Method:** `POST`
- **Request Body:**
    - `subscriberId`: The ID of the user subscribing.
    - `subscriptionId`: The ID of the user being subscribed to.

- **Response:**
    - `200`: Success message.
    - `400`: Bad request if necessary parameters are missing.
    - `404`: Not found if the subscription cannot be found.
    - `500`: Internal server error for other exceptions.

### unsubscribe

```php
public function unsubscribe(Request $request): JsonResponse
```

Handles unsubscription requests.

- **URL:** `/api/unsubscribe`
- **Method:** `POST`
- **Request Body:**
    - `subscriberId`: The ID of the user unsubscribing.
    - `subscriptionId`: The ID of the user being unsubscribed from.

- **Response:**
    - `200`: Success message.
    - `400`: Bad request if necessary parameters are missing.
    - `404`: Not found if the subscription cannot be found.
    - `500`: Internal server error for other exceptions.

### search

```php
public function search(Request $request): JsonResponse
```

Handles search requests.

- **URL:** `/api/search`
- **Method:** `GET`
- **Request Parameters:**
    - `search_type`: The type of search (users, companies, all).
    - `query`: The search query string.

- **Response:**
    - `200`: Success message with search results.
    - `400`: Bad request if necessary parameters are missing.
    - `500`: Internal server error for other exceptions.

### createChat

```php
public function createChat(Request $request): JsonResponse
```

Creates a new chat.

- **URL:** `/api/chat`
- **Method:** `POST`
- **Request Body:**
    - `name`: The name of the chat.
    - `is_group`: Whether the chat is a group chat.
    - `user_id`: The ID of the user creating the chat.

- **Response:**
    - `201`: Created chat information.
    - `400`: Bad request if necessary parameters are missing.
    - `500`: Internal server error for other exceptions.

### addUserToChat

```php
public function addUserToChat(Request $request): JsonResponse
```

Adds a user to an existing chat.

- **URL:** `/api/chat/add-user`
- **Method:** `POST`
- **Request Body:**
    - `chat_id`: The ID of the chat.
    - `user_id`: The ID of the user being added to the chat.

- **Response:**
    - `200`: Success message.
    - `400`: Bad request if necessary parameters are missing.
    - `500`: Internal server error for other exceptions.

### sendMessage

```php
public function sendMessage(Request $request): JsonResponse
```

Sends a message in a chat.

- **URL:** `/api/message`
- **Method:** `POST`
- **Request Body:**
    - `chat_id`: The ID of the chat.
    - `user_id`: The ID of the user sending the message.
    - `content`: The content of the message.

- **Response:**
    - `201`: Created message information.
    - `500`: Internal server error for other exceptions.

### getMessages

```php
public function getMessages(int $chatId): JsonResponse
```

Retrieves messages from a chat.

- **URL:** `/api/chat/{chatId}/messages`
- **Method:** `GET`
- **Parameters:**
    - `chatId`: The ID of the chat.

- **Response:**
    - `200`: Success message with chat messages.
    - `500`: Internal server error for other exceptions.
---

