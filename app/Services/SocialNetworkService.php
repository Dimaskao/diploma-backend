<?php

namespace App\Services;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialNetworkService
{
    protected SubscriptionService $subscriptionService;
    protected ChatService $chatService;
    protected MessageService $messageService;
    protected SearchService $searchService;

    public function __construct(SubscriptionService $subscriptionService, ChatService $chatService, MessageService $messageService, SearchService $searchService)
    {
        $this->subscriptionService = $subscriptionService;
        $this->chatService = $chatService;
        $this->messageService = $messageService;
        $this->searchService = $searchService;
    }

    public function search(Request $request): JsonResponse
    {
        return $this->searchService->search($request);
    }

    public function subscribe(Request $request): JsonResponse
    {
        try {
            return $this->subscriptionService->subscribe($request);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        try {
            return $this->subscriptionService->unsubscribe($request);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function createChat(Request $request): JsonResponse
    {
        return $this->chatService->createChat($request->all());
    }

    public function addUserToChat(Request $request, int $chatId): JsonResponse
    {
        return $this->chatService->addUserToChat($chatId, $request->user_id);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        return $this->messageService->sendMessage($request->all());
    }

    public function getMessages(int $chatId): JsonResponse
    {
        return $this->messageService->getMessages($chatId);
    }
}
