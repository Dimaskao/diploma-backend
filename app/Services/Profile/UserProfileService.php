<?php

namespace App\Services\Profile;

use App\Enums\ResponseKeys;
use App\Factories\ProfileStrategyFactory;
use App\Interfaces\Factory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserProfileService
{
    protected Factory $factory;

    public function __construct(ProfileStrategyFactory $factory)
    {
        $this->factory = $factory;
    }

    public function getProfile($id): JsonResponse
    {
        try {
            return $this->factory->create(['id' => $id])->show($id);
        } catch (Exception $e) {
            return response()->json([ResponseKeys::MESSAGE => $e->getMessage()], 404);
        }
    }

    public function updateProfile(Request $request, $id): JsonResponse
    {
        try {
            return $this->factory->create(['id' => $id])->update($request, $id);
        } catch (Exception $e) {
            return response()->json([ResponseKeys::MESSAGE => $e->getMessage()], 404);
        }
    }

    public function deleteProfile($id): JsonResponse
    {
        try {
            return $this->factory->create(['id' => $id])->deleteProfile($id);
        } catch (Exception $e) {
            return response()->json([ResponseKeys::MESSAGE => $e->getMessage()], 500);
        }
    }
}
