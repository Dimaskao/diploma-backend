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
    protected function updateUserSkills($skills, $user): array
    {
        $result = [];

        foreach ($skills as $skill) {

            if (isset($skill['id']) && isset($skill[Edit::EDIT_INFO])) {
                $editInfo = $skill[Edit::EDIT_INFO];
                $skillId = $skill['id'];

                $result[] = match ($editInfo) {
                    Edit::ADD => $this->addSkill($skillId, $user),
                    Edit::REMOVE => $this->removeSkill($skillId, $user),
                    default => throw new Exception('Update type does not exist')
                };
            } else {
                throw new Exception('Skill id was not set');
            }
        }
        return $result;
    }

    private function addSkill($skillId, $user): array
    {
        $userSkillRecordId = (string)Str::uuid();
        UserSkill::insert([
                'id' => $userSkillRecordId,
                'user_id' => $user->id,
                'skill_id' => $skillId
            ]
        );
        return [
            'id' => $userSkillRecordId,
            Edit::EDIT_INFO => Edit::ADD,
            ResponseKey::RESULT => 'success'
        ];
    }

    private function removeSkill($skillId, $user): array
    {
        $record = UserSkill::where('skill_id', $skillId)->where('user_id', $user->id)->first();
        if ($record) {
            $record->delete();
            return [
                Edit::EDIT_INFO => Edit::REMOVE,
                ResponseKey::RESULT => 'success'
            ];
        }
        return [
            'id' => $record->id,
            Edit::EDIT_INFO => Edit::REMOVE,
            ResponseKey::RESULT => 'error'
        ];
    }
}
