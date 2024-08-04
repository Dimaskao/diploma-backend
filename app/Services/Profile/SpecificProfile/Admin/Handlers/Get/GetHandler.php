<?php

namespace App\Services\Profile\SpecificProfile\Admin\Handlers\Get;

use App\Enums\ResponseKey;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Get\Helpers\ProfileGetHelper;

trait GetHandler
{
    use ProfileGetHelper;

    protected function getProfileMethods(): array
    {
        return [
            ResponseKey::ADMIN => 'getAdminProfileData'
        ];
    }
}
