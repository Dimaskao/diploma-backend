<?php

namespace App\Services\Profile\SpecificProfile;

use App\Interfaces\SpecificProfileService;
use App\Services\Response\ResponseService;
use App\Services\ValidationService;
use Exception;

abstract class BaseSpecificProfileService implements SpecificProfileService
{
    protected ValidationService $validator;
    protected ResponseService $responseService;

    public function __construct()
    {
        $this->validator = new ValidationService();
        $this->responseService = new ResponseService();
    }

    protected function getUserUpdateData(array $data): array
    {
        return array_filter([
            'password' => isset($data['password']) ? bcrypt($data['password']) : null,
            'avatar_url' => $data['avatar_url'] ?? null,
        ], function ($value) {
            return !is_null($value);
        });
    }

    protected function convertToDateTimeString($date): ?string
    {
        try {
            return date('Y-m-d H:i:s', strtotime($date));
        } catch (Exception $e) {
            return null;
        }
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

    protected function getWorkExperienceDataToProceed(array $data): array
    {
        $resultData = [];

        if (isset($data['position'])) {
            $resultData['position'] = $data['position'];
        }

        if(isset($data['company_name'])) {
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

//    private function updateRegularUserByUpdateType($updateType, $user, $baseUser): array
//    {
//        $updatedResults = [];
//        $updateMethods = $this->getUpdateMethods();
//
//        foreach ($updateMethods as $type => $method) {
//            if (isset($updateType[$type])) {
//                $updatedResults[$type] = $this->callUpdateMethod($method, $updateType[$type], $user, $baseUser);
//            }
//        }
//
//        return $updatedResults;
//    }
//
//    private function getUpdateMethods(): array
//    {
//        return [
//            UpdateType::PERSONAL_INFORMATION => 'updateRegularUserProfile',
//            UpdateType::EDUCATION => 'updateUserEducation',
//            UpdateType::WORK_EXPERIENCE => 'updateWorkExperience',
//            UpdateType::SKILLS => 'updateUserSkills'
//        ];
//    }
//
//    private function callUpdateMethod(string $method, $updateData, $user, $baseUser = null)
//    {
//        if ($method === 'updateRegularUserProfile') {
//            return $this->$method($updateData, $user, $baseUser);
//        }
//        return $this->$method($updateData, $user);
//    }
}
