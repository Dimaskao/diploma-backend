<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update;

use App\Enums\UpdateType;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers\ProfileHelper;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers\SkillsHelper;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers\UserEducationHelper;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers\WorkExperienceUpdateHelper;

trait UpdateHandler
{
    use ProfileHelper, WorkExperienceUpdateHelper, UserEducationHelper, SkillsHelper;

    protected function getUpdateMethods(): array
    {
        return [
            UpdateType::PERSONAL_INFORMATION => 'updateRegularUserProfile',
            UpdateType::EDUCATION => 'updateUserEducation',
            UpdateType::WORK_EXPERIENCE => 'updateWorkExperience',
            UpdateType::SKILLS => 'updateUserSkills'
        ];
    }

    protected function callUpdateMethod(string $method, $updateData, $user, $baseUser = null): mixed
    {
        if ($method === 'updateRegularUserProfile') {
            return $this->$method($updateData, $user, $baseUser);
        }
        return $this->$method($updateData, $user);
    }
}
