<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers;

use App\Enums\Edit;
use App\Enums\ResponseKey;
use App\Models\UserSkill;
use Exception;
use Illuminate\Support\Str;

trait SkillsUpdateHelper
{
    /**
     * @throws Exception
     */
    protected function updateUserSkills(array $skills, $regularUser): array
    {
        return array_map(function ($skill) use ($regularUser) {
            $this->validateSkill($skill);

            return match ($skill[Edit::EDIT_INFO]) {
                Edit::ADD => $this->addSkill($skill['id'], $regularUser),
                Edit::REMOVE => $this->removeSkill($skill['id'], $regularUser),
                default => throw new Exception('Invalid edit info')
            };
        }, $skills);
    }

    /**
     * @throws Exception
     */
    private function validateSkill(array $skill): void
    {
        if (!isset($skill['id'])) {
            throw new Exception('Skill id is required');
        }

        if (!isset($skill[Edit::EDIT_INFO])) {
            throw new Exception('Edit info is required');
        }
    }


    private function addSkill($skillId, $user): array
    {
        $insertId = (string)Str::uuid();

        UserSkill::insert([
                'id' => $insertId,
                'user_id' => $user->id,
                'skill_id' => $skillId
            ]
        );

        return [
            'id' => $insertId,
            Edit::EDIT_INFO => Edit::ADD,
            ResponseKey::RESULT => 'success'
        ];
    }

    private function removeSkill($skillId, $user): array
    {
        $skill = UserSkill::where('skill_id', $skillId)->where('user_id', $user->id)->first();
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
