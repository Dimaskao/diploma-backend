<?php

namespace App\Http\Controllers;

use App\Services\Profile\UserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected UserProfileService $service;

    public function __construct(UserProfileService $service)
    {
        $this->service = $service;
    }

    public function show($id): JsonResponse
    {
        return $this->service->getProfile($id);
    }

    public function update(Request $request, $id): JsonResponse
    {
        return $this->service->updateProfile($request, $id);
    }

    public function destroy($id) : JsonResponse
    {
        return $this->service->deleteProfile($id);
    }
}
