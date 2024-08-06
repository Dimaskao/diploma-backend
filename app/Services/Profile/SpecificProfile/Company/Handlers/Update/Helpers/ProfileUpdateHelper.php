<?php

namespace App\Services\Profile\SpecificProfile\Company\Handlers\Update\Helpers;

use Exception;
use Illuminate\Validation\ValidationException;

trait ProfileUpdateHelper
{
    /**
     * @throws ValidationException
     * @throws Exception
     */
    protected function updateAndGetCompanyProfile(array $personalInformation, $user, $base): array
    {
        $this->updateProfileData($personalInformation, $user, $base);

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

    protected function validationRules(): array
    {
        return [
            'description' => 'sometimes|string|max:255',
            'name' => 'sometimes|string',
            'contact_email' => 'sometimes|string',
            'contact_phone' => 'sometimes|string',
            'password' => 'sometimes|string|min:8',
            'contact_url' => 'sometimes|url',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

    }

    protected function profileUpdateFields(): array
    {
        return ['description', 'name', 'contact_email', 'contact_phone', 'contact_url'];
    }
}
