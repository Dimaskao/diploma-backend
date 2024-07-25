<?php

namespace App\Strategies\Profile;

use App\Interfaces\ProfileStrategy;
use App\Interfaces\SpecificProfileService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseProfileStrategy implements ProfileStrategy
{
    protected SpecificProfileService $service;

    public function __construct(SpecificProfileService $service)
    {
        $this->service = $service;
    }

    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);
        return $this->service->getProfile($user);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        return $this->service->updateProfile($user, $request);
    }

    public function deleteProfile($id): JsonResponse
    {
        return $this->service->deleteProfile($id);
    }
}
