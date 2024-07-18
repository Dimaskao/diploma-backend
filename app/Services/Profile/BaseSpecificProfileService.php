<?php

namespace App\Services\Profile;

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
        $baseUserUpdateData = [];

        if (isset($data['password'])) {
            $baseUserUpdateData['password'] = bcrypt($data['password']);
        }

        if (isset($data['avatar_url'])) {
            $baseUserUpdateData['avatar_url'] = $data['avatar_url'];
        }
        return $baseUserUpdateData;
    }

    protected function convertToDateTimeString($date): ?string
    {
        try {
            $timestamp = strtotime($date);
            return date('Y-m-d H:i:s', $timestamp);
        } catch (Exception $e) {
            return null;
        }
    }

    protected function getUserEducationDataToProceed(array $data, array $dataToUpdate): array
    {
        if (isset($data['start_date'])) {
            $dataToUpdate['start_date'] = $data['start_date'];
        }

        if (isset($data['end_date'])) {
            $dataToUpdate['end_date'] = $data['end_date'];
        }

        if (isset($data['contact_url'])) {
            $dataToUpdate['contact_url'] = $data['contact_url'];
        }
        return $dataToUpdate;
    }

    protected function getWorkExperienceDataToProceed(array $data, array $newData): array
    {
        if (isset($data['description'])) {
            $newData['description'] = $data['description'];
        }

        if (isset($data['date_start'])) {
            $newData['date_start'] = $data['date_start'];
        }

        if (isset($data['date_end'])) {
            $newData['date_end'] = $data['date_end'];
        }
        return $newData;
    }
}
