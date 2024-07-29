<?php

namespace App\Services\Profile\SpecificProfile\Company\Handlers\Get\Helpers;

trait ProfileGetHelper
{
    protected function getCompanyProfileData($user, $company): array
    {
        return [
            'id' => $user->id,
            'name' => $company->name,
            'description' => $company->description,
            'contact_email' => $company->contact_email,
            'contact_phone' => $company->contact_phone,
            'contact_url' => $company->contact_url,
        ];
    }
}
