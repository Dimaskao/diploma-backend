<?php

namespace App\Strategies;

use App\Interfaces\ProfileStrategy;
use App\Models\User;
use App\Services\CompanyProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyProfileStrategy implements ProfileStrategy
{
    protected CompanyProfileService $service;

    public function __construct(CompanyProfileService $service)
    {
        $this->service = $service;
    }

    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);
        $profile = $this->service->getCompanyProfile($user);
        return response()->json($profile, 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $updatedProfile = $this->service->updateCompanyInformation($user, $request);
        return response()->json($updatedProfile, 200);
    }

    public function deleteProfile($id): JsonResponse
    {
        return $this->service->deleteCompanyProfile($id);
    }
}
