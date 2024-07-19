<?php

namespace App\Services\Profile;

use App\Enums\ResponseKeys;
use App\Factories\ProfileStrategyFactory;
use App\Interfaces\Factory;
use App\Services\Response\ResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserProfileService
{
    protected Factory $factory;
    protected ResponseService $responseService;

    public function __construct(ProfileStrategyFactory $factory, ResponseService $responseService)
    {
        $this->factory = $factory;
        $this->responseService = $responseService;
    }

    public function getProfile($id): JsonResponse
    {
        try {
            return $this->factory->create(['id' => $id])->show($id);
        } catch (Exception $e) {
            return $this->responseService->internalServerError($e->getMessage());
        }
    }

    public function updateProfile(Request $request, $id): JsonResponse
    {
        try {
            return $this->factory->create(['id' => $id])->update($request, $id);
        } catch (Exception $e) {
            return $this->responseService->internalServerError($e->getMessage());
        }
    }

    public function deleteProfile($id): JsonResponse
    {
        try {
            return $this->factory->create(['id' => $id])->deleteProfile($id);
        } catch (Exception $e) {
            return $this->responseService->internalServerError($e->getMessage());
        }
    }
}
