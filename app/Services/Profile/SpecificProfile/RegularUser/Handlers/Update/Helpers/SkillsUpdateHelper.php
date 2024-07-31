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
    protected function updateUserSkills($skills, $regularUser): array
    {
        $result = [];

        foreach ($skills as $skill) {
            if (!isset($skill['id'])) {
                throw new Exception('Skill id was not set');
            }

            if(!isset($skill[Edit::EDIT_INFO])) {
                throw new Exception('Edit info was not set');
            }

            $skillId = $skill['id'];

            $result[] = match ($skill[Edit::EDIT_INFO]) {
                Edit::ADD => $this->addSkill($skillId, $regularUser),
                Edit::REMOVE => $this->removeSkill($skillId, $regularUser),
                default => throw new Exception('Edit info does not exist')
            };
        }
        return $result;
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
        $record = UserSkill::where('skill_id', $skillId)->where('user_id', $user->id)->first();

        if (!$record) {
            return [
                'id' => $record->id,
                Edit::EDIT_INFO => Edit::REMOVE,
                ResponseKey::RESULT => 'error'
            ];
        }

        $record->delete();
        return [
            Edit::EDIT_INFO => Edit::REMOVE,
            ResponseKey::RESULT => 'success'
        ];
    }
}
