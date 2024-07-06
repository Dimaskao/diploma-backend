<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function show($id): JsonResponse
    {
        return $this->profileService->show($id);
    }

    public function update(Request $request, $id): JsonResponse
    {
        return $this->profileService->update($request, $id);
    }

    public function subscribe(Request $request): JsonResponse
    {
        return $this->profileService->subscribe($request);
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        return $this->profileService->unsubscribe($request);
    }
}
