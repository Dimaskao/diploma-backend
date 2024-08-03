<?php

namespace App\Http\Controllers;

use App\Services\JobOffer\JobOfferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobOffersController extends Controller
{
    protected JobOfferService $service;

    public function __construct(JobOfferService $service)
    {
        $this->service = $service;
    }

    public function show($id): JsonResponse
    {
        return $this->service->getJobOfferById($id);
    }

    public function update($id, Request $request): JsonResponse
    {
        return $this->service->updateJobOfferById($id, $request->all());
    }

    public function store(Request $request): JsonResponse
    {
        return $this->service->createJobOffer($request->all());
    }

    public function destroy($id): JsonResponse
    {
        return $this->service->deleteJobOfferById($id);
    }

    public function getJobOffersByCompanyId(int $id): JsonResponse
    {
        return $this->service->getJobOffersByCompanyId($id);
    }
}
