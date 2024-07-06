<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Factories\ProfileStrategyFactory;
use App\Interfaces\Factory;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileService
{
    protected Factory $factory;
    protected SubscriptionService $subscriptionService;

    public function __construct(ProfileStrategyFactory $factory, SubscriptionService $subscriptionService)
    {
        $this->factory = $factory;
        $this->subscriptionService = $subscriptionService;
    }

    public function show($id): JsonResponse
    {
        try {
            return $this->factory->create(['id' => $id])->show($id);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            return $this->factory->create(['id' => $id])->update($request, $id);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
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

    public function deleteProfile($id): JsonResponse
    {
        try {
            return $this->factory->create(['id' => $id])->deleteProfile($id);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
