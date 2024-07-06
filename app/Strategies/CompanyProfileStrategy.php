<?php

namespace App\Strategies;

use App\Enums\UserRole;
use App\Interfaces\ProfileStrategy;
use App\Traits\ProfileTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyProfileStrategy implements ProfileStrategy
{
    use ProfileTrait;

    public function show($id): JsonResponse
    {
        return $this->getUserProfile($id, UserRole::COMPANY);
    }

    public function update(Request $request, $id): JsonResponse
    {
        return $this->updateUserProfile($request, $id, UserRole::COMPANY);
    }

    public function deleteProfile(): JsonResponse
    {
        // TODO: Implement deleteAccount() method.
    }
}
