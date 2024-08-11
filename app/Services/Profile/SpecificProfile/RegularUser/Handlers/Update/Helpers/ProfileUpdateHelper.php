<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers;

use Exception;
use Illuminate\Validation\ValidationException;

trait ProfileUpdateHelper
{
    /**
     * @throws ValidationException
     * @throws Exception
     */
    protected function updateRegularUserProfile(array $personalInformation, $user, $base): array
    {
        $this->updateProfileData($personalInformation, $user, $base);
        return $this->transformProfileData($user, $base);
    }

    protected function validationRules(): array
    {
        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'skills_desc' => 'sometimes|string',
            'experience' => 'sometimes|string',
            'password' => 'sometimes|string|min:8',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    protected function profileUpdateFields(): array
    {
        return ['first_name', 'last_name', 'skills_desc', 'experience'];
    }

    private function transformProfileData($specific, $base): array
    {
        return [
            'id' => $base->id,
            'first_name' => $specific->first_name,
            'last_name' => $specific->last_name,
            'skills_desc' => $specific->skills_desc,
            'experience' => $specific->experience,
            'avatar_url' => $base->avatar_url,
            'email' => $base->email,
        ];
    }
}
