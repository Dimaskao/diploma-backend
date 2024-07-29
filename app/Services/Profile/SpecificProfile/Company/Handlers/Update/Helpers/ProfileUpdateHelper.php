<?php

namespace App\Services\Profile\SpecificProfile\Company\Handlers\Update\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

trait ProfileUpdateHelper
{
    /**
     * @throws ValidationException
     * @throws Exception
     */
    protected function updateAndGetCompanyProfile(array $personalInformation, $user, $base): array
    {
        $data = $this->validator->validate($personalInformation, [
            'description' => 'sometimes|string|max:255',
            'name' => 'sometimes|string',
            'contact_email' => 'sometimes|string',
            'contact_phone' => 'sometimes|string',
            'password' => 'sometimes|string|min:8',
            'contact_url' => 'sometimes|url',
            'avatar_url' => 'sometimes|url'
        ]);

        if (!$user || !$base) {
            throw new Exception('Error while updating user profile');
        }

        $userUpdateData = $this->getCompanyUpdateData($data);
        $baseUserUpdateData = $this->getUserUpdateData($data);

        if (!empty($userUpdateData)) {
            $user->update($userUpdateData);
        }

        if (!empty($baseUserUpdateData)) {
            $base->update($baseUserUpdateData);
        }

        return [
            'id' => $base->id,
            'description' => $user->description,
            'name' => $user->name,
            'contact_email' => $user->contact_email,
            'contact_phone' => $user->contact_phone,
            'contact_url' => $user->contact_url,
            'avatar_url' => $base->avatar_url,
            'email' => $base->email,
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    private function getCompanyUpdateData(array $data): array
    {
        $userUpdateData = [];

        if (isset($data['description'])) {
            $userUpdateData['description'] = $data['description'];
        }

        if (isset($data['name'])) {
            $userUpdateData['name'] = $data['name'];
        }

        if (isset($data['contact_email'])) {
            $userUpdateData['contact_email'] = $data['contact_email'];
        }

        if (isset($data['contact_phone'])) {
            $userUpdateData['contact_phone'] = $data['contact_phone'];
        }

        if (isset($data['contact_url'])) {
            $userUpdateData['contact_url'] = $data['contact_url'];
        }

        return $userUpdateData;
    }
}
