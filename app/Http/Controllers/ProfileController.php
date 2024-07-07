<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected ProfileService $service;

    public function __construct(ProfileService $profileService)
    {
        $this->service = $profileService;
    }

    public function show($id): JsonResponse
    {
        return $this->service->show($id);
    }

    public function update(Request $request, $id): JsonResponse
    {
        return $this->service->update($request, $id);
    }

    public function destroy($id) : JsonResponse
    {
        return $this->service->deleteProfile($id);
    }
}
