<?php

namespace App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers;

use App\Enums\UpdateType;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Validation\ValidationException;

trait SelfUpdateHelper
{
    /**
     * @throws ValidationException
     */
    protected function updateSelfByUpdateType($editRequest, Admin $admin, User $user): array
    {
        $updatedResults = [];
        if (isset($editRequest[UpdateType::PERSONAL_INFORMATION])) {
            $this->updatePersonalInformation($editRequest[UpdateType::PERSONAL_INFORMATION], $admin, $user);
            $updatedResults[UpdateType::PERSONAL_INFORMATION] = [
                'name' => $admin->name,
                'avatar_url' => $user->avatar_url,
            ];
        }

        return $updatedResults;
    }

    /**
     * @throws ValidationException
     */
    private function updatePersonalInformation($personalInformation, Admin $admin, User $user): void
    {
        $data = $this->validator->validate($personalInformation, $this->updatePersonalInformationValidationRules());

        $admin->update($this->getSelfAdminUpdateData($data));
        $user->update($this->getUserUpdateData($data));
    }

    private function updatePersonalInformationValidationRules(): array
    {
        return [
            'name' => 'sometimes|string',
            'password' => 'sometimes|string|min:8',
            'avatar_url' => 'sometimes|url',
        ];
    }

    private function getSelfAdminUpdateData($personalInformation): array
    {
        return array_filter(['name' => $personalInformation['name'] ?? null]);
    }

//    /**
//     * @throws ValidationException
//     * @throws Exception
//     */
//    private function updateAdminProfile(array $personalInformation, $user, $baseUser): array
//    {
//        $data = $this->validator->validate($personalInformation, [
//            'first_name' => 'sometimes|string|max:255',
//            'last_name' => 'sometimes|string|max:255',
//            'skills_desc' => 'sometimes|string',
//            'experience' => 'sometimes|string',
//            'email' => 'sometimes|string',
//            'password' => 'sometimes|string|min:8',
//            'avatar_url' => 'sometimes|url'
//        ]);
//
//        if (!$user || !$baseUser) {
//            throw new Exception('Error while updating user profile');
//        }
//
//        $userUpdateData = $this->getRegularUserUpdateData($data);
//        $baseUserUpdateData = $this->getUserUpdateData($data);
//
//        if (!empty($userUpdateData)) {
//            $user->update($userUpdateData);
//        }
//
//        if (!empty($baseUserUpdateData)) {
//            $baseUser->update($baseUserUpdateData);
//        }
//
//        return [
//            'id' => $baseUser->id,
//            'first_name' => $user->first_name,
//            'last_name' => $user->last_name,
//            'skills_desc' => $user->skills_desc,
//            'experience' => $user->experience,
//            'avatar_url' => $baseUser->avatar_url,
//            'email' => $baseUser->email,
//        ];
//    }
}
