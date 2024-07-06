<?php

namespace App\Strategies;

use App\Enums\SubscriptionAction;
use App\Enums\UserRole;
use App\Interfaces\ProfileStrategy;
use App\Traits\ProfileTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegularUserProfileStrategy implements ProfileStrategy
{
    use ProfileTrait;

    public function show($id): JsonResponse
    {
        return $this->getUserProfile($id, UserRole::REGULAR_USER);
    }

    public function update(Request $request, $id): JsonResponse
    {
        return $this->updateUserProfile($request, $id, UserRole::REGULAR_USER);
    }

    public function deleteProfile(): JsonResponse
    {
        // TODO: Implement deleteAccount() method.
    }
}
