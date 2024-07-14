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
        return $this->service->getCompanyProfile($user);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        return $this->service->updateCompanyInformation($user, $request);
    }

    public function deleteProfile($id): JsonResponse
    {
        return $this->service->deleteCompanyProfile($id);
    }
}
