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
        return array_filter([
            'password' => isset($data['password']) ? bcrypt($data['password']) : null,
            'avatar_url' => $data['avatar_url'] ?? null,
        ], function($value) {
            return !is_null($value);
        });
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

    protected function getUserEducationDataToProceed(array $data): array
    {
        return array_filter([
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'contact_url' => $data['contact_url'] ?? null,
        ], function($value) {
            return !is_null($value);
        });
    }

    protected function getWorkExperienceDataToProceed(array $data): array
    {
        return array_filter([
            'description' => $data['description'] ?? null,
            'date_start' => $data['date_start'] ?? null,
            'date_end' => $data['date_end'] ?? null,
        ], function($value) {
            return !is_null($value);
        });
    }
}
