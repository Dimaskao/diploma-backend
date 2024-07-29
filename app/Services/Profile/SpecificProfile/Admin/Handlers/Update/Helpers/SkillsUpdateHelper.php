<?php

namespace App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers;

use App\Enums\ResponseKey;
use App\Models\Skill;
use Exception;

trait SkillsUpdateHelper
{
    /**
     * @throws Exception
     */
    protected function addNewSkills($editRequest): array
    {
        foreach ($editRequest['skills'] as $skill) {
            Skill::create(['name' => $skill['name']]);
        }

        return [ResponseKey::MESSAGE => 'success'];
    }

    /**
     * @throws Exception
     */
    protected function removeSkills($editRequest): array
    {
        foreach ($editRequest['skills'] as $skill) {
            Skill::where('name', $skill['name'])->firstOrFail()->delete();
        }

        return [ResponseKey::MESSAGE => 'success'];
    }
}
