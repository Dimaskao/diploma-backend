<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Get;

use App\Models\UserEducation;
use App\Models\UserSkill;
use App\Models\WorkExperience;

trait GetHandler
{
    private function  getRegularUserEducation($regularUserRecord): array
    {
        return UserEducation::where('user_id', $regularUserRecord->id)->get()->map(function ($educationRecord) {
            return [
                'institution' => $educationRecord->institution,
                'degree' => $educationRecord->degree,
                'field_of_study' => $educationRecord->field_of_study,
                'start_date' => $educationRecord->start_date,
                'end_date' => $educationRecord->end_date,
                'contact_url' => $educationRecord->contact_url
            ];
        })->toArray();
    }

    private function getRegularUserWorkExperience($regularUserRecord): array
    {
        return WorkExperience::where('user_id', $regularUserRecord->id)->get()->map(function ($workExperienceRecord) {
            return [
                'position' => $workExperienceRecord->position,
                'description' => $workExperienceRecord->description,
                'date_start' => $workExperienceRecord->date_start,
                'date_end' => $workExperienceRecord->date_end
            ];
        })->toArray();
    }

    private function getRegularUserSkills($regularUserRecord): array
    {
        return UserSkill::where('user_id', $regularUserRecord->id)->get()->map(function ($userSkill) {
            return [
                'id' => $userSkill->id,
                'name' => $userSkill->name
            ];
        })->toArray();
    }

    private function getRegularUserProfileData($user, $regularUser): array
    {
        return [
            'id' => $user->id,
            'first_name' => $regularUser->first_name,
            'last_name' => $regularUser->last_name,
            'skills_desc' => $regularUser->skills_desc,
            'experience' => $regularUser->experience,
        ];
    }
}
