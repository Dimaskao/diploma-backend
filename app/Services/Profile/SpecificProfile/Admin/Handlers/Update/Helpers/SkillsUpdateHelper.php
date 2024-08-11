<?php

namespace App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers;

use App\Enums\Edit;
use App\Enums\ResponseKey;
use App\Models\Skill;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait SkillsUpdateHelper
{
    /**
     * @throws Exception
     */
    protected function handleSkills($skills): array
    {
        $result = [];

        foreach ($skills as $skill) {
            $result[] = match ($skill[Edit::EDIT_INFO]) {
                Edit::ADD => $this->addNewSkill($skill),
                Edit::REMOVE => $this->removeSkill($skill),
                default => throw new Exception('Update type does not exist')
            };
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    protected function addNewSkill($skill): array
    {
        if (!isset($skill['name'])) {
            throw new Exception('Skill name is required');
        }

        $insertId = (string)Str::uuid();

        Skill::insert([
                'id' => $insertId,
                'name' => $skill['name']
            ]
        );

        return [
            'id' => $insertId,
            'name' => Skill::find($insertId)->name,
            Edit::EDIT_INFO => Edit::ADD,
            ResponseKey::RESULT => 'success'
        ];
    }

    /**
     * @throws Exception
     */
    protected function removeSkill($skill): array
    {
        $skill = Skill::where('name', $skill['name'])->first();
        return $skill ? $this->removeSkillSuccess($skill) : $this->removeSkillError($skill);
    }

    private function removeSkillSuccess($skill): array
    {
        $skill->delete();
        return [
            Edit::EDIT_INFO => Edit::REMOVE,
            ResponseKey::RESULT => 'success'
        ];
    }

    private function removeSkillError($skill): array
    {
        return [
            'id' => $skill->id,
            Edit::EDIT_INFO => Edit::REMOVE,
            ResponseKey::RESULT => 'error'
        ];
    }
}
