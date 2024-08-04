<?php

namespace App\Services\JobOffer;

use App\Models\Company;
use App\Models\JobOffer;
use App\Services\Response\ResponseService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;

class JobOfferService
{
    protected ResponseService $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    protected function isJobOfferExpired(?JobOffer $jobOffer): bool
    {
        if ($jobOffer) {
            return $jobOffer->valid_until < carbon::now();
        } else {
            return false;
        }
    }

    public function createJobOffer(array $data): JsonResponse
    {
        try {
            if (isset($data['title']) && isset($data['company_id'])
                && isset($data['position']) && isset($data['description'])
                && isset($data['requirements']) && isset($data['requirement_experience'])
                && isset($data['valid_until'])) {

                //var_dump($data);

                $company = Company::find($data['company_id']);

                if ($company) {
                    $jobOffer = JobOffer::create([
                        'title'                  => $data['title'],
                        'company_id'             => $data['company_id'],
                        'position'               => $data['position'],
                        'description'            => $data['description'],
                        'requirements'           => $data['requirements'],
                        'requirement_experience' => $data['requirement_experience'],
                        'valid_until'            => $data['valid_until'],
                    ]);

                    return $this->responseService->created($jobOffer, 'JobOffer created.');
                } else {
                    return $this->responseService->notFound('Company not found.');
                }
            }

            return $this->responseService->badRequest($data);
        } catch (Exception $e) {
            return $this->responseService->internalServerError("Error during creating a JobOffer, {$e->getMessage()}");
        }
    }

    public function getJobOfferById(string $id): JsonResponse
    {
        $jobOffer = JobOffer::find($id);

        if ($this->isJobOfferExpired($jobOffer)) {
            return $this->deleteJobOfferById($jobOffer->id);
        }

        return $this->responseService->success($jobOffer);
    }

    public function getJobOffers(): JsonResponse
    {
        $jobOffers = JobOffer::all();

        return $this->responseService->success($jobOffers);
    }

    public function getJobOffersByCompanyId(string $id): JsonResponse
    {
        $jobOffers = JobOffer::where('company_id', $id)->get();

        foreach ($jobOffers as $jobOffer) {
            if ($this->isJobOfferExpired($jobOffer)) {
                $this->deleteJobOfferById($jobOffer->id);
            }
        }

        return $this->responseService->success($jobOffers);
    }

    public function updateJobOfferById(string $id, array $data): JsonResponse
    {
        try {
            $jobOffer = JobOffer::find($id);

            if ($this->isJobOfferExpired($jobOffer)) {
                return $this->deleteJobOfferById($jobOffer->id);
            }

            if ($jobOffer) {
                $jobOffer->title = $data['title'];
                $jobOffer->position = $data['position'];
                $jobOffer->description = $data['description'];
                $jobOffer->requirements = $data['requirements'];
                $jobOffer->requirement_experience = $data['requirement_experience'];
                $jobOffer->valid_until = $data['valid_until'];

                $jobOffer->save();

                return $this->responseService->success($jobOffer, 'JobOffer has been updated');
            }

            return $this->responseService->notFound();
        } catch (Exception $e) {
            return $this->responseService->internalServerError("Error during updating a JobOffer, {$e->getMessage()}");
        }
    }

    public function deleteJobOfferById(string $id): JsonResponse
    {
        try {
            $jobOffer = JobOffer::find($id);
            if ($jobOffer) {
                $jobOffer->delete();

                return $this->responseService->success($jobOffer, 'JobOffer has been deleted');
            }

            return $this->responseService->notFound();
        } catch (Exception $e) {
            return $this->responseService->internalServerError("Error during deleting a JobOffer, {$e->getMessage()}");
        }
    }

    public function subscribeToJobOffer(string $jobOffer_id, string $user_id): JsonResponse
    {
        try {
            $jobOffer = JobOffer::find($jobOffer_id);

            if ($this->isJobOfferExpired($jobOffer)) {
                return $this->deleteJobOfferById($jobOffer->id);
            }

            $jobOffer->users()->syncWithoutDetaching([$user_id]);

            return $this->responseService->success($jobOffer, 'JobOffer has been subscribed');
        } catch (Exception $e) {
            return $this->responseService->internalServerError("Error during subscribe to the JobOffer, {$e->getMessage()}");
        }
    }

    public function unsubscribeFromJobOffer(string $jobOffer_id, string $user_id): JsonResponse
    {
        try {
            $jobOffer = JobOffer::find($jobOffer_id);

            if ($this->isJobOfferExpired($jobOffer)) {
                return $this->deleteJobOfferById($jobOffer->id);
            }

            $jobOffer->users()->detach([$user_id]);

            return $this->responseService->success($jobOffer, 'JobOffer has been unsubscribed');
        } catch (Exception $e) {
            return $this->responseService->internalServerError("Error during unsubscribe from the JobOffer, {$e->getMessage()}");
        }
    }
}
