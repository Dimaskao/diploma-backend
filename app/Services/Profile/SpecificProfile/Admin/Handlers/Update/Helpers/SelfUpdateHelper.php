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
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    private function getSelfAdminUpdateData($personalInformation): array
    {
        return array_filter(['name' => $personalInformation['name'] ?? null]);
    }
}
