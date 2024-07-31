<?php

namespace App\Services\SocialNetwork;

use App\Enums\ResponseKey;
use App\Services\Response\ResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialNetworkService
{
    protected SubscriptionService $subscriptionService;
    protected ChatService $chatService;
    protected MessageService $messageService;
    protected SearchService $searchService;
    protected ResponseService $responseService;

    public function __construct(SubscriptionService $subscriptionService, ChatService $chatService, MessageService $messageService, SearchService $searchService, ResponseService $responseService)
    {
        $this->subscriptionService = $subscriptionService;
        $this->chatService = $chatService;
        $this->messageService = $messageService;
        $this->searchService = $searchService;
        $this->responseService = $responseService;
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
            return $this->responseService->notFound($e->getMessage());
        }
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        try {
            return $this->subscriptionService->unsubscribe($request);
        } catch (Exception $e) {
            return $this->responseService->notFound($e->getMessage());
        }
    }

    public function createChat(Request $request): JsonResponse
    {
        return $this->chatService->createChat($request->all());
    }

    public function addUserToChat(Request $request): JsonResponse
    {
        if ($request->has('chat_id') && $request->has('user_id')) {
            $chatId = $request->get('chat_id');
            $userId = $request->get('user_id');
            return $this->chatService->addUserToChat($chatId, $userId);
        }
        return $this->responseService->internalServerError('User was not added to chat');
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
