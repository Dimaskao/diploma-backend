<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update;

use App\Enums\UpdateType;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers\ProfileUpdateHelper;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers\SkillsUpdateHelper;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers\UserEducationUpdateHelper;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers\WorkExperienceUpdateHelper;

trait UpdateHandler
{
    use ProfileUpdateHelper, WorkExperienceUpdateHelper, UserEducationUpdateHelper, SkillsUpdateHelper;

    protected function getUpdateMethods(): array
    {
        return [
            UpdateType::PERSONAL_INFORMATION => 'updateRegularUserProfile',
            UpdateType::EDUCATION => 'updateUserEducation',
            UpdateType::WORK_EXPERIENCE => 'updateWorkExperience',
            UpdateType::SKILLS => 'updateUserSkills'
        ];
    }
}
