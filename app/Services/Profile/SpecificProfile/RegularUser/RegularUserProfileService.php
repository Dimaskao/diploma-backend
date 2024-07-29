<?php

namespace App\Services\Profile\SpecificProfile\RegularUser;

use App\Enums\ResponseKey;
use App\Enums\UpdateType;
use App\Models\User;
use App\Services\Profile\SpecificProfile\BaseSpecificProfileService;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Delete\DeleteHandler;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Get\GetHandler;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\UpdateHandler;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RegularUserProfileService extends BaseSpecificProfileService
{
    use GetHandler, UpdateHandler, DeleteHandler;

    public function getProfile($user): JsonResponse
    {
        $regularUser = $user->userProfile->regularUser;
        return $this->responseService->success([
            ResponseKey::PROFILE => [
                ResponseKey::USER => $this->getRegularUserProfileData($user, $regularUser),
                ResponseKey::EDUCATION => $this->getRegularUserEducation($regularUser),
                ResponseKey::WORK_EXPERIENCE => $this->getRegularUserWorkExperience($regularUser),
                ResponseKey::SKILLS => $this->getRegularUserSkills($regularUser)
            ]
        ]);
    }

    public function updateProfile($user, Request $request): JsonResponse
    {
        try {
            return $this->responseService->success([ResponseKey::UPDATED_INFORMATION => $this->updateRegularUserByUpdateType($request->input(UpdateType::UPDATE_TYPE), $user->userProfile->regularUser, $user)]);
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
}
