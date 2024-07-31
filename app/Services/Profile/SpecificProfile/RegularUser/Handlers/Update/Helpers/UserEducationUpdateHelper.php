<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers;

use App\Models\UserEducation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait UserEducationUpdateHelper
{
    /**
     * @throws ValidationException
     */
    protected function updateUserEducation($education, $user): array
    {
        $result = [];
        Log::debug('updateUserEducation init: ' . var_export([
                'education' => $education,
                'user' => $user
            ], 1));

        foreach ($education as $e) {
            if (isset($e['start_date'])) {
                $e['start_date'] = date('Y-m-d H:i:s', $e['start_date']);
            }

            if (isset($e['end_date'])) {
                $e['end_date'] = date('Y-m-d H:i:s', $e['end_date']);
            }

            $data = $this->validator->validate($e, [
                'institution' => 'required|string|max:255',
                'degree' => 'string|max:255',
                'field_of_study' => 'string|max:255',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date',
                'contact_url' => 'sometimes|url',
            ]);

            if (isset($e['id'])) {
                $data['id'] = $e['id'];
                $educationRecord = UserEducation::find($data['id']);
            } else {
                $educationRecord = null;
            }

            if ($educationRecord) {
                $educationRecord = $this->updateEducationRecord($data, $educationRecord);
            } else {
                $educationRecord = $this->insertEducationRecord($data, $user);
            }

            $result[] = [
                'id' => $educationRecord->id,
                'institution' => $educationRecord->institution,
                'degree' => $educationRecord->degree,
                'field_of_study' => $educationRecord->field_of_study,
                'start_date' => $educationRecord->start_date,
                'end_date' => $educationRecord->end_date,
                'contact_url' => $educationRecord->contact_url,
            ];
        }

        return $result;
    }

    private function updateEducationRecord(array $data, $educationRecord): array
    {
        Log::debug('updateEducationRecord: ' . var_export($data, 1));

        if (isset($data['institution'])) {
            $dataToUpdate['institution'] = $data['institution'];
        }

        if (isset($data['degree'])) {
            $dataToUpdate['degree'] = $data['degree'];
        }

        if (isset($data['field_of_study'])) {
            $dataToUpdate['field_of_study'] = $data['field_of_study'];
        }

        $dataToUpdate = $this->getUserEducationDataToProceed($data);

        $educationRecord->update($dataToUpdate);
        return $educationRecord;
    }

    private function insertEducationRecord(array $data, $user)
    {
        $education_data = [
            'institution' => $data['institution'],
            'degree' => $data['degree'],
            'field_of_study' => $data['field_of_study']
        ];

        $education_data = array_merge($education_data, $this->getUserEducationDataToProceed($data));
        $education_data['user_id'] = $user->id;
        $education_data['id'] = (string)Str::uuid();

        UserEducation::insert($education_data);
        return UserEducation::find($education_data['id']);
    }

    protected function getUserEducationDataToProceed(array $data): array
    {
        $dataToInsert = [];

        if (isset($data['start_date'])) {
            $dataToInsert['start_date'] = $data['start_date'];
        }

        if (isset($data['end_date'])) {
            $dataToInsert['end_date'] = $data['end_date'];
        }

        if (isset($data['contact_url'])) {
            $dataToInsert['contact_url'] = $data['contact_url'];
        }

        return $dataToInsert;
    }
}
