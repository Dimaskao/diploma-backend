<?php

namespace App\Services\Profile\SpecificProfile;

use App\Enums\Method;
use App\Enums\ResponseKey;
use App\Enums\UpdateType;
use App\Interfaces\SpecificProfileService;
use App\Models\User;
use App\Services\Image\ImageUploadService;
use App\Services\Response\ResponseService;
use App\Services\Validation\ValidationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

abstract class BaseSpecificProfileService implements SpecificProfileService
{
    protected ValidationService $validator;
    protected ResponseService $responseService;
    protected ImageUploadService $imageUploadService;

    public function __construct(ValidationService $validationService, ResponseService $responseService, ImageUploadService $imageUploadService)
    {
        $this->validator = $validationService;
        $this->responseService = $responseService;
        $this->imageUploadService = $imageUploadService;
    }

    public function getProfile($user): JsonResponse
    {
        return $this->responseService->success([
            ResponseKey::PROFILE => $this->processByType(
                operation: Method::GET,
                methods: $this->getProfileMethods(),
                specific: $this->specificProfileUser($user),
                base: $user
            )
        ]);
    }

    public function updateProfile($user, Request $request): JsonResponse
    {
        try {
            return $this->responseService->success([
                ResponseKey::UPDATED_INFORMATION => $this->processByType(
                    operation: Method::UPDATE,
                    methods: $this->getUpdateMethods(),
                    specific: $this->specificProfileUser($user),
                    typeData: $request->input(UpdateType::UPDATE_TYPE),
                    base: $user
                )
            ]);
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

    protected function getUserUpdateData(array $data, User $user = null): array
    {
        return array_filter([
            'password' => isset($data['password']) ? bcrypt($data['password']) : null,
            'avatar_url' => isset($data['avatar']) && $user ? $this->processAvatarUpdate($user, $data['avatar']) : null,
        ], function ($value) {
            return !is_null($value);
        });
    }

    protected function processAvatarUpdate($user, $avatar): ?string
    {
        return $this->imageUploadService->remove($user) ? $this->imageUploadService->upload($user, $avatar) : null;
    }

    protected function convertToDateTimeString($date): ?string
    {
        try {
            return date('Y-m-d H:i:s', strtotime($date));
        } catch (Exception $e) {
            return null;
        }
    }

    protected function getUpdateDataByFields(array $data, $fields): array
    {
        return array_filter($data, function ($key) use ($fields) {
            return in_array($key, $fields);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    protected function updateProfileData($data, $specificUser, $baseUser): void
    {
        $data = $this->validator->validate($data, $this->validationRules());

        if (!$specificUser || !$baseUser) {
            throw new Exception('Error while updating user profile');
        }

        $userUpdateData = $this->getUpdateDataByFields($data, $this->profileUpdateFields());
        $baseUserUpdateData = $this->getUserUpdateData($data);

        if (!empty($userUpdateData)) {
            $specificUser->update($userUpdateData);
        }

        if (!empty($baseUserUpdateData)) {
            $baseUser->update($baseUserUpdateData);
        }
    }

    protected function profileUpdateFields(): array
    {
        // Override this method in the child class if needed
        return [];
    }

    protected function validationRules(): array
    {
        // Override this method in the child class if needed
        return [];
    }

    protected function processByType(string $operation, array $methods, $specific, $typeData = null, $base = null): array
    {
        $results = [];

        foreach ($methods as $type => $method) {
            if ($operation === Method::UPDATE && isset($typeData[$type])) {
                $results[$type] = $this->callMethod($method, $typeData[$type], $specific, $base);
            } elseif ($operation === Method::GET) {
                $results[$type] = $this->callMethod($method, $specific, $base);
            }
        }

        return $results;
    }

    protected function delete(User $base): void
    {
        // Override this method in the child class if needed
    }

    protected function specificProfileUser($user): mixed
    {
        // Override this method in the child class to return the specific profile user
        return [];
    }

    protected function getUpdateMethods(): array
    {
        return [
            // key => methodName
        ];
    }

    protected function getProfileMethods(): array
    {
        return [
            // key => methodName
        ];
    }

    protected function callMethod(string $method, ...$params): mixed
    {
        return call_user_func_array([$this, $method], $params);
    }
}
