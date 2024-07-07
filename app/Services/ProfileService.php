<?php

namespace App\Services;

use App\Factories\ProfileStrategyFactory;
use App\Interfaces\Factory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileService
{
    protected Factory $factory;

    public function __construct(ProfileStrategyFactory $factory)
    {
        $this->factory = $factory;
    }

    public function show($id): JsonResponse
    {
        try {
            return $this->factory->create(['id' => $id])->show($id);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            return $this->factory->create(['id' => $id])->update($request, $id);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function deleteProfile($id): JsonResponse
    {
        try {
            return $this->factory->create(['id' => $id])->deleteProfile($id);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
