<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers;

use App\Enums\Edit;
use App\Enums\ResponseKey;
use App\Models\Skill;
use App\Models\UserSkill;
use Exception;
use Illuminate\Support\Str;

trait SkillsHelper
{
    /**
     * @throws Exception
     */
    private function updateUserSkills($skills, $user): array
    {
        $result = [];
        $skillsTest = Skill::all()->toArray();
//        Log::debug('skills from db: ' . var_export($skillsTest, 1));

//        Log::debug('skills init: ' . var_export($skills, 1));
        foreach ($skills as $skill) {
//            Log::debug('skill: ' . var_export($skill, 1));
//            Log::debug('skill[id]: ' . var_export($skill['id'], 1));
//            Log::debug('skill[edit_info]: ' . var_export($skill['edit_info'], 1));

//            if (!isset($skill['id'])) {
//                Log::error('Skill id was not set');
//            }


            if (isset($skill['id']) && isset($skill[Edit::EDIT_INFO])) {
                $editInfo = $skill[Edit::EDIT_INFO];
                $skillId = $skill['id'];
//                if ($editInfo === Edit::ADD) {
//                    // Add skill logic here
//                    Log::debug("Adding skill with ID: $skillId");
//                } elseif ($editInfo === Edit::REMOVE) {
//                    // Remove skill logic here
//                    Log::debug("Removing skill with ID: $skillId");
//                }

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
