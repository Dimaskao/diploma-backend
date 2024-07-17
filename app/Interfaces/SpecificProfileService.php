<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface SpecificProfileService
{
    public function getProfile(User $user): JsonResponse;
    public function updateProfile(User $user, Request $request): JsonResponse;
    public function deleteProfile($id): JsonResponse;
}
