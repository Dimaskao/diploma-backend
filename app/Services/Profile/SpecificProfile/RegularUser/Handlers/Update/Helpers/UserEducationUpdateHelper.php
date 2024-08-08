<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers;

use App\Models\UserEducation;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait UserEducationUpdateHelper
{
    /**
     * @throws ValidationException
     */
    protected function updateUserEducation($educationData, $user): array
    {
        return array_map(function ($education) use ($user) {
            $education = $this->prepareEducationDates($education);
            $educationRecord = $this->findOrCreateEducationRecord($education, $user);
            return $this->transformEducationRecord($educationRecord);
        }, $educationData);
    }

    private function prepareEducationDates(array $education): array
    {
        if (isset($education['start_date'])) {
            $education['start_date'] = date('Y-m-d H:i:s', $education['start_date']);
        }

        if (isset($education['end_date'])) {
            $education['end_date'] = date('Y-m-d H:i:s', $education['end_date']);
        }

        return $education;
    }

    /**
     * @throws ValidationException
     */
    private function findOrCreateEducationRecord(array $data, $user)
    {
        $educationRecord = isset($data['id']) ? UserEducation::find($data['id']) : null;

        if ($educationRecord) {
            $data = $this->validator->validate($data, $this->updateEducationValidationRules());
            return $this->updateEducationRecord($data, $educationRecord);
        }

        $data = $this->validator->validate($data, $this->createEducationValidationRules());
        return $this->createEducationRecord($data, $user);
    }

    private function updateEducationRecord(array $data, $educationRecord): array
    {
        $data = $this->getUpdateDataByFields($data, $this->educationFields());
        $educationRecord->update($data);
        return $educationRecord;
    }

    private function createEducationRecord(array $data, $user)
    {
        $data = $this->getUpdateDataByFields($data, $this->educationFields());
        $educationData = array_merge(
            $data,
            [
                'user_id' => $user->id,
                'id' => (string)Str::uuid()
            ]
        );

        UserEducation::insert($educationData);
        return UserEducation::find($educationData['id']);
    }

    private function transformEducationRecord($educationRecord): array
    {
        return [
            'id' => $educationRecord->id,
            'institution' => $educationRecord->institution,
            'degree' => $educationRecord->degree,
            'field_of_study' => $educationRecord->field_of_study,
            'start_date' => $educationRecord->start_date,
            'end_date' => $educationRecord->end_date,
            'contact_url' => $educationRecord->contact_url,
        ];
    }

    private function createEducationValidationRules(): array
    {
        return [
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'contact_url' => 'sometimes|url',
        ];
    }

    private function updateEducationValidationRules(): array
    {
        return [
            'institution' => 'sometimes|string|max:255',
            'degree' => 'sometimes|string|max:255',
            'field_of_study' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'contact_url' => 'sometimes|url',
        ];
    }

    private function educationFields(): array
    {
        return ['institution', 'degree', 'field_of_study', 'start_date', 'end_date', 'contact_url'];
    }
}
