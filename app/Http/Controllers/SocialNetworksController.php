<?php

namespace App\Http\Controllers;

use App\Services\SocialNetworksService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialNetworksController
{
    protected SocialNetworksService $service;

    public function __construct(SocialNetworksService $service)
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
}
