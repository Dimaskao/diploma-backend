<?php

namespace App\Services\Profile\SpecificProfile\Admin;

use App\Services\Profile\SpecificProfile\Admin\Handlers\Delete\DeleteHandler;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Get\GetHandler;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Update\UpdateHandler;
use App\Services\Profile\SpecificProfile\BaseSpecificProfileService;

class AdminProfileService extends BaseSpecificProfileService
{
    use GetHandler, UpdateHandler, DeleteHandler;

    protected function specificProfileUser($user): mixed
    {
        return $user->userProfile->admin;
    }

//    public function updateProfile($user, Request $request): JsonResponse
//    {
//        try {
//            return $this->responseService->success([ResponseKey::UPDATED_INFORMATION => $this->updateByEditInfoType($request, $user->profileable, $user)]);
//        } catch (ValidationException $e) {
//            return $this->responseService->badRequest($e->getMessage());
//        } catch (Exception $e) {
//            return $this->responseService->internalServerError($e->getMessage());
//        }
//    }
}
