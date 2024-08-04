<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Get\Helpers;

use App\Models\UserSkill;

trait SkillsGetHelper
{
    protected function getRegularUserSkills($regularUserRecord): array
    {
        return UserSkill::where('user_id', $regularUserRecord->id)->get()->map(function ($userSkill) {
            return [
                'id' => $userSkill->id,
                'name' => $userSkill->name
            ];
        })->toArray();
    }
}
