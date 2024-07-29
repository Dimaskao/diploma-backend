<?php

namespace App\Services\Profile\SpecificProfile\Company\Handlers\Update;

use App\Enums\UpdateType;
use App\Services\Profile\SpecificProfile\Company\Handlers\Update\Helpers\ProfileHelper;

trait UpdateHandler
{
    use ProfileHelper;

    protected function getUpdateMethods(): array
    {
        return [
            UpdateType::PERSONAL_INFORMATION => 'updateCompanyProfile'
        ];
    }

    protected function callUpdateMethod(string $method, $updateData, $user, $baseUser = null)
    {
        if ($method === 'updateAndGetCompanyProfile') {
            return $this->$method($updateData, $user, $baseUser);
        }
        return $this->$method($updateData, $user);
    }
}
