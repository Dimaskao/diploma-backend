<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\Helpers;

use App\Enums\Period;
use App\Models\WorkExperience;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait WorkExperienceUpdateHelper
{
    /**
     * @throws ValidationException
     */
    private function updateWorkExperience($workExperience, $user): array
    {
        $result = [];
        foreach ($workExperience as $experience) {
            $result[] = $this->processWorkExperienceRecord($experience, $user);
        }
        return $result;
    }

    /**
     * @throws ValidationException
     */
    private function processWorkExperienceRecord(mixed $experience, $user): array
    {
        $workExperienceRecord = $this->getWorkExperienceUpdatedData(
            $this->getValidatedWorkExperienceUpdateData(
                $this->processAndGetDatesInWorkExperienceUpdateData($experience)
            ), $user);

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
        if (isset($experience['date_start'])) {
            $experience['date_start'] = $this->convertToDateTimeString($experience['date_start']);
        }

        if (isset($experience['date_end'])) {
            if ($experience['date_end'] == Period::PRESENT) {
                unset($experience['date_end']);
            } else {
                $experience['date_end'] = $this->convertToDateTimeString($experience['date_end']);
            }
        }

        return $experience;
    }

    /**
     * @throws ValidationException
     */
    private function getValidatedWorkExperienceUpdateData($experience): array
    {
        return $this->validator->validate($experience, [
            'position' => 'sometimes|string|max:255',
            'company_name' => 'string|max:255',
            'date_start' => 'sometimes|date',
            'date_end' => 'sometimes|date',
            'description' => 'sometimes|string|max:255',
        ]);
    }

    /**
     * @throws ValidationException
     */
    private function getWorkExperienceUpdatedData($data, $user)
    {
        $workExperienceRecord = $this->getWorkExperienceRecordToUpdate($data);
        return $workExperienceRecord != null
            ? $this->updateWorkExperienceRecord($workExperienceRecord, $data)
            : $this->insertWorkExperienceRecord($user, $data);
    }

    private function getWorkExperienceRecordToUpdate($data): ?WorkExperience
    {
        if (isset($experience['id'])) {
            $data['id'] = $experience['id'];
            $workExperienceRecord = WorkExperience::find($data['id']);
        } else {
            $workExperienceRecord = null;
        }
        return $workExperienceRecord;
    }

    /**
     * @throws ValidationException
     */
    private function insertWorkExperienceRecord($user, array $data): mixed
    {
        $dataToInsert = $this->getWorkExperienceDataToProceed($data);

        if (!isset($dataToInsert['position'])) {
            throw ValidationException::withMessages(['position' => 'Position field is required for work experience.']);
        }

        $dataToInsert['user_id'] = $user->id;
        $dataToInsert['id'] = (string)Str::uuid();

        WorkExperience::insert($dataToInsert);
        return WorkExperience::find($dataToInsert['id']);
    }

    private function updateWorkExperienceRecord($workExperienceRecord, array $data)
    {
        $dataToUpdate = $this->getWorkExperienceDataToProceed($data);
        $workExperienceRecord->update($dataToUpdate);
        return $workExperienceRecord;
    }

    protected function getWorkExperienceDataToProceed(array $data): array
    {
        $resultData = [];

        if (isset($data['position'])) {
            $resultData['position'] = $data['position'];
        }

        if (isset($data['company_name'])) {
            $resultData['company_name'] = $data['company_name'];
        }

        if (isset($data['description'])) {
            $resultData['description'] = $data['description'];
        }

        if (isset($data['date_start'])) {
            $resultData['date_start'] = $data['date_start'];
        }

        if (isset($data['date_end'])) {
            $resultData['date_end'] = $data['date_end'];
        }

        return $resultData;
    }
}
