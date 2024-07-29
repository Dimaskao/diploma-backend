<?php

namespace App\Services\Profile\SpecificProfile;

use App\Enums\ResponseKey;
use App\Enums\UpdateType;
use App\Interfaces\SpecificProfileService;
use App\Models\User;
use App\Services\Response\ResponseService;
use App\Services\ValidationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

abstract class BaseSpecificProfileService implements SpecificProfileService
{
    protected ValidationService $validator;
    protected ResponseService $responseService;

    public function __construct()
    {
        $this->validator = new ValidationService();
        $this->responseService = new ResponseService();
    }

    public function getProfile($user): JsonResponse
    {
        return $this->responseService->success($this->getResponseProfileData($user, $this->specificProfileUser($user)));
    }

    public function updateProfile($user, Request $request): JsonResponse
    {
        try {
            return $this->responseService->success([ResponseKey::UPDATED_INFORMATION => $this->updateByUpdateType($request->input(UpdateType::UPDATE_TYPE),$this->specificProfileUser($user), $user)]);
        } catch (ValidationException $e) {
            return $this->responseService->badRequest($e->getMessage());
        } catch (Exception $e) {
            return $this->responseService->internalServerError($e->getMessage());
        }
    }

    public function deleteProfile($id): JsonResponse
    {
        $base = User::find($id);

        if (!$base) {
            return $this->responseService->notFound("User not found");
        }

        $this->delete($base);

        return $this->responseService->success();
    }

    protected function getUserUpdateData(array $data): array
    {
        return array_filter([
            'password' => isset($data['password']) ? bcrypt($data['password']) : null,
            'avatar_url' => $data['avatar_url'] ?? null,
        ], function ($value) {
            return !is_null($value);
        });
    }

    protected function convertToDateTimeString($date): ?string
    {
        try {
            return date('Y-m-d H:i:s', strtotime($date));
        } catch (Exception $e) {
            return null;
        }
    }

    protected function updateByUpdateType($updateType, $specific, $base): array
    {
        $updatedResults = [];
        $updateMethods = $this->getUpdateMethods();

        foreach ($updateMethods as $type => $method) {
            if (isset($updateType[$type])) {
                $updatedResults[$type] = $this->callUpdateMethod($method, $updateType[$type], $specific, $base);
            }
        }

        return $updatedResults;
    }

    protected function getResponseProfileData(User $base, $specific): mixed
    {
        return [];
    }

    protected function delete(User $base): void
    {
        //
    }

    protected function specificProfileUser($user): mixed
    {
        return [];
    }

    protected function getUpdateMethods(): array
    {
        return [
            // key => methodName
        ];
    }

    protected function callUpdateMethod(string $method, $updateData, $user, $baseUser = null): mixed
    {
        return null;
    }
}
