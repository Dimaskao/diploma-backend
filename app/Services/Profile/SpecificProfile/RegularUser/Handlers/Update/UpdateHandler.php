<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update;

use App\Enums\UpdateType;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers\ProfileHelper;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers\SkillsHelper;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers\UserEducationHelper;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers\WorkExperienceUpdateHelper;
use Exception;

trait UpdateHandler
{
    use ProfileHelper, WorkExperienceUpdateHelper, UserEducationHelper, SkillsHelper;

    /**
     * @throws Exception
     */
    private function updateRegularUserByUpdateType($updateType, $user, $baseUser): array
    {
        $updatedResults = [];
        $updateMethods = $this->getUpdateMethods();

        foreach ($updateMethods as $type => $method) {
            if (isset($updateType[$type])) {
                $updatedResults[$type] = $this->callUpdateMethod($method, $updateType[$type], $user, $baseUser);
            }
        }

        return $updatedResults;
    }

    private function getUpdateMethods(): array
    {
        return [
            UpdateType::PERSONAL_INFORMATION => 'updateRegularUserProfile',
            UpdateType::EDUCATION => 'updateUserEducation',
            UpdateType::WORK_EXPERIENCE => 'updateWorkExperience',
            UpdateType::SKILLS => 'updateUserSkills'
        ];
    }

    private function callUpdateMethod(string $method, $updateData, $user, $baseUser = null)
    {
        if ($method === 'updateRegularUserProfile') {
            return $this->$method($updateData, $user, $baseUser);
        }
        return $this->$method($updateData, $user);
    }
}
