<?php

namespace App\Strategies;

use App\Interfaces\ProfileStrategy;
use App\Models\User;
use App\Services\RegularUserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegularUserProfileStrategy implements ProfileStrategy
{
    protected RegularUserProfileService $service;

    public function __construct(RegularUserProfileService $service)
    {
        $this->service = $service;
    }

    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);
        return $this->service->getRegularUserProfile($user);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        return $this->service->updateUserInformation($user, $request);
    }

    public function deleteProfile($id): JsonResponse
    {
        return $this->service->deleteRegularUserProfile($id);
    }
}
