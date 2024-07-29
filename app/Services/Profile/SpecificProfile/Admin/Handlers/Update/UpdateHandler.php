<?php

namespace App\Services\Profile\SpecificProfile\Admin\Handlers\Update;

use App\Enums\UpdateType;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers\AnotherAdminPermissionsUpdateHelper;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers\BanUnbanUpdateHelper;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers\SelfUpdateHelper;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers\SkillsUpdateHelper;

trait UpdateHandler
{
    use AnotherAdminPermissionsUpdateHelper, SelfUpdateHelper, BanUnbanUpdateHelper, SkillsUpdateHelper;

    protected function getUpdateMethods(): array
    {
        return [
            UpdateType::SELF => 'updateSelfByUpdateType',
            UpdateType::ANOTHER_ADMIN_PERMISSIONS => 'updateAnotherAdminPermissions',
            UpdateType::BAN_UNBAN => 'handleBanOrUnban',
            UpdateType::SKILLS => 'handleSkills'
        ];
    }
}
