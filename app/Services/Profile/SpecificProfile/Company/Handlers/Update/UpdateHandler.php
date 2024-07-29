<?php

namespace App\Services\Profile\SpecificProfile\Company\Handlers\Update;

use App\Enums\UpdateType;
use App\Services\Profile\SpecificProfile\Company\Handlers\Update\Helpers\ProfileUpdateHelper;

trait UpdateHandler
{
    use ProfileUpdateHelper;

    protected function getUpdateMethods(): array
    {
        return [
            UpdateType::PERSONAL_INFORMATION => 'updateAndGetCompanyProfile'
        ];
    }
}
