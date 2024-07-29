<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers;

use Exception;
use Illuminate\Validation\ValidationException;

trait ProfileHelper
{

    /**
     * @throws ValidationException
     * @throws Exception
     */
    private function updateRegularUserProfile(array $personalInformation, $user, $baseUser): array
    {
        $data = $this->validator->validate($personalInformation, [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'skills_desc' => 'sometimes|string',
            'experience' => 'sometimes|string',
            'email' => 'sometimes|string',
            'password' => 'sometimes|string|min:8',
            'avatar_url' => 'sometimes|url'
        ]);

        if (!$user || !$baseUser) {
            throw new Exception('Error while updating user profile');
        }

        $userUpdateData = $this->getRegularUserUpdateData($data);
        $baseUserUpdateData = $this->getUserUpdateData($data);

        if (!empty($userUpdateData)) {
            $user->update($userUpdateData);
        }

        if (!empty($baseUserUpdateData)) {
            $baseUser->update($baseUserUpdateData);
        }

        return [
            'id' => $baseUser->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'skills_desc' => $user->skills_desc,
            'experience' => $user->experience,
            'avatar_url' => $baseUser->avatar_url,
            'email' => $baseUser->email,
        ];
    }

    private function getRegularUserUpdateData(array $data): array
    {
        $userUpdateData = [];

        if (isset($data['first_name'])) {
            $userUpdateData['first_name'] = $data['first_name'];
        }

        if (isset($data['last_name'])) {
            $userUpdateData['last_name'] = $data['last_name'];
        }

        if (isset($data['skills_desc'])) {
            $userUpdateData['skills_desc'] = $data['skills_desc'];
        }

        if (isset($data['experience'])) {
            $userUpdateData['experience'] = $data['experience'];
        }

        return $userUpdateData;
    }
}
