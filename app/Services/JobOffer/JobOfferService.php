<?php

namespace App\Services\JobOffer;

use App\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use App\Models\Company;
use Exception;

class JobOfferService
{
    protected ResponseService $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    public function createJobOffer(array $data): JsonResponse
    {
        try {
            if (isset($data['title']) && isset($data['company_id'])
                && isset($data['position']) && isset($data['description'])
                && isset($data['requirements']) && isset($data['requirement_experience']) 
                && isset($data['valid_until'])) {

                    
            }
            return $this->responseService->badRequest();
        } catch (Exception $e) {
            return $this->responseService->internalServerError("Error during creating a JobOffer, {$e->getMessage()}");
        }
    }
}