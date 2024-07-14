<?php

namespace App\Http\Controllers;

use App\Services\SocialNetworkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialNetworkController
{
    protected SocialNetworkService $socialNetworkService;

    public function __construct(SocialNetworkService $socialNetworkService)
    {
        $this->socialNetworkService = $socialNetworkService;
    }

    public function subscribe(Request $request): JsonResponse
    {
        return $this->socialNetworkService->subscribe($request);
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        return $this->socialNetworkService->unsubscribe($request);
    }

    public function search(Request $request): JsonResponse
    {
        return $this->socialNetworkService->search($request);
    }

    public function createChat(Request $request): JsonResponse
    {
        return $this->socialNetworkService->createChat($request);
    }

    public function addUserToChat(Request $request, $chatId): JsonResponse
    {
        return $this->socialNetworkService->addUserToChat($chatId, $request->user_id);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        return $this->socialNetworkService->sendMessage($request);
    }

    public function getMessages(int $chatId): JsonResponse
    {
        return $this->socialNetworkService->getMessages($chatId);
    }
}
