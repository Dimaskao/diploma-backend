<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers;

use App\Enums\Period;
use App\Models\UserEducation;
use App\Models\WorkExperience;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait WorkExperienceUpdateHelper
{
    /**
     * @throws ValidationException
     */
    protected function updateWorkExperience(array $workExperience, $user): array
    {
        return array_map(fn($experience) => $this->processWorkExperienceRecord($experience, $user), $workExperience);
    }

    /**
     * @throws ValidationException
     */
    private function processWorkExperienceRecord(mixed $experience, $user): array
    {
        $data = $this->processAndGetDatesInWorkExperienceUpdateData($experience);
        $workExperienceRecord = $this->findOrCreateWorkExperienceRecord($data, $user);
        return $this->transformWorkExperienceRecord($workExperienceRecord);
    }

    /**
     * @throws ValidationException
     */
    private function findOrCreateWorkExperienceRecord(array $data, $user): WorkExperience
    {
        $workExperienceRecord = isset($data['id']) ? WorkExperience::find($data['id']) : null;

        if ($workExperienceRecord) {
            $data = $this->validator->validate($data, $this->updateWorkExperienceValidationRules());
            return $this->updateWorkExperienceRecord($data, $workExperienceRecord);
        }

        $data = $this->validator->validate($data, $this->createWorkExperienceValidationRules());
        return $this->createWorkExperienceRecord($data, $user);
    }

    private function updateWorkExperienceRecord($data, $workExperience)
    {
        $data = $this->getUpdateDataByFields($data, $this->workExperienceFields());
        $workExperience->update($data);
        return $workExperience;
    }

    private function createWorkExperienceRecord($user, array $data): WorkExperience
    {
        $workExperienceData = array_merge(
            $this->getUpdateDataByFields($data, $this->workExperienceFields()),
            [
                'user_id' => $user->id,
                'id' => (string)Str::uuid()
            ]
        );

        WorkExperience::insert($workExperienceData);
        return WorkExperience::find($workExperienceData['id']);
    }

    private function workExperienceFields(): array
    {
        return ['position', 'company_name', 'description', 'date_start', 'date_end'];
    }

    private function updateWorkExperienceValidationRules(): array
    {
        return [
            'position' => 'sometimes|string|max:255',
            'company_name' => 'sometimes|string|max:255',
            'date_start' => 'sometimes|date',
            'date_end' => 'sometimes|date',
            'description' => 'sometimes|string|max:255',
        ];
    }

    private function createWorkExperienceValidationRules(): array
    {
        return [
            'position' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'date_start' => 'sometimes|date',
            'date_end' => 'sometimes|date',
            'description' => 'sometimes|string|max:255',
        ];
    }

    private function transformWorkExperienceRecord(WorkExperience $workExperienceRecord): array
    {
        return [
            'id' => $workExperienceRecord->id,
            'position' => $workExperienceRecord->position,
            'company_name' => $workExperienceRecord->company_name,
            'description' => $workExperienceRecord->description,
            'date_start' => $workExperienceRecord->date_start,
            'date_end' => $workExperienceRecord->date_end
        ];
    }

    private function processAndGetDatesInWorkExperienceUpdateData($experience): array
    {
        $experience['date_start'] = isset($experience['date_start']) ? $this->convertToDateTimeString($experience['date_start']) : null;
        $experience['date_end'] = isset($experience['date_end']) && $experience['date_end'] !== Period::PRESENT
            ? $this->convertToDateTimeString($experience['date_end']) : null;
        return $experience;
    }

}
