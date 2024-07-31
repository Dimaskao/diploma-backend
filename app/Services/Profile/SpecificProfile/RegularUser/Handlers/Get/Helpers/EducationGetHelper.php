<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Get\Helpers;

use App\Models\UserEducation;

trait EducationGetHelper
{
    protected function getRegularUserEducation($regularUserRecord): array
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
}
