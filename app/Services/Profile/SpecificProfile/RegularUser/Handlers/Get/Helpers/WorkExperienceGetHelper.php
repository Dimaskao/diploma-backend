<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Get\Helpers;

use App\Models\WorkExperience;

trait WorkExperienceGetHelper
{
    protected function getRegularUserWorkExperience($regularUserRecord): array
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
}
