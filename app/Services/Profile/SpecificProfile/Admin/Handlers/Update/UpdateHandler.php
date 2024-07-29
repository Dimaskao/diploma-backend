<?php

namespace App\Services\Profile\SpecificProfile\Admin\Handlers\Update;

use App\Enums\UpdateType;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers\AnotherAdminPermissionsUpdateHelper;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers\BanUpdateHelper;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers\SelfUpdateHelper;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers\SkillsUpdateHelper;

trait UpdateHandler
{
    use AnotherAdminPermissionsUpdateHelper, SelfUpdateHelper, BanUpdateHelper, SkillsUpdateHelper;

    protected function getUpdateMethods(): array
    {
        return [
            UpdateType::SELF => 'updateSelfByUpdateType',
            UpdateType::ANOTHER_ADMIN_PERMISSIONS => 'updateAnotherAdminPermissions',
            UpdateType::BAN_USER => 'banUser',
            UpdateType::BAN_POST => 'banPost',
            UpdateType::UNBAN_POST => 'unbanPost',
            UpdateType::UNBAN_USER => 'unbanUser',
            UpdateType::ADD_NEW_SKILLS => 'addNewSkills',
            UpdateType::REMOVE_SKILLS => 'addNewSkills'
        ];
    }
}
