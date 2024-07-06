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
        $profile = $this->service->getRegularUserProfile($user);
        return response()->json($profile, 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $updatedProfile = $this->service->updateUserInformation($user, $request);
        return response()->json($updatedProfile, 200);
    }

    public function deleteProfile($id): JsonResponse
    {
        return $this->service->deleteRegularUserProfile($id);
    }
}
