<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Get;

use App\Enums\ResponseKey;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Get\Helpers\EducationGetHelper;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Get\Helpers\ProfileGetHelper;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Get\Helpers\SkillsGetHelper;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Get\Helpers\WorkExperienceGetHelper;

trait GetHandler
{
    use EducationGetHelper, ProfileGetHelper, SkillsGetHelper, WorkExperienceGetHelper;

    protected function getProfileMethods(): array
    {
        return [
            ResponseKey::USER => 'getRegularUserProfileData',
            ResponseKey::EDUCATION => 'getRegularUserEducation',
            ResponseKey::WORK_EXPERIENCE => 'getRegularUserWorkExperience',
            ResponseKey::SKILLS => 'getRegularUserSkills'
        ];
    }
}
