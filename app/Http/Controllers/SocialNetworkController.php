<?php

namespace App\Http\Controllers;

use App\Services\SocialNetwork\SocialNetworkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialNetworkController
{
    protected SocialNetworkService $service;

    public function __construct(SocialNetworkService $service)
    {
        $this->service = $service;
    }

    public function subscribe(Request $request): JsonResponse
    {
        return $this->service->subscribe($request);
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        return $this->service->unsubscribe($request);
    }

    public function search(Request $request): JsonResponse
    {
        return $this->service->search($request);
    }

    public function createChat(Request $request): JsonResponse
    {
        return $this->service->createChat($request);
    }

    public function addUserToChat(Request $request): JsonResponse
    {
        return $this->service->addUserToChat($request);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        return $this->service->sendMessage($request);
    }

    public function getMessages(int $chatId): JsonResponse
    {
        return $this->service->getMessages($chatId);
    }
}
