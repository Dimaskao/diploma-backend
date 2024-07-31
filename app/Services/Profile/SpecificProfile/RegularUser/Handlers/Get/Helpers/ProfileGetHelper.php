<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Get\Helpers;

trait ProfileGetHelper
{
    protected function getRegularUserProfileData($user, $regularUser): array
    {
        return [
            'id' => $user->id,
            'first_name' => $regularUser->first_name,
            'last_name' => $regularUser->last_name,
            'skills_desc' => $regularUser->skills_desc,
            'experience' => $regularUser->experience,
        ];
    }
}
