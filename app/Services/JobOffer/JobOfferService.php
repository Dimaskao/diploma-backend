<?php

namespace App\Services\JobOffer;

use App\Models\Company;
use App\Models\JobOffer;
use App\Services\Response\ResponseService;
use Exception;
use Illuminate\Http\JsonResponse;

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
                }
            }

            return $this->responseService->badRequest();
        } catch (Exception $e) {
            return $this->responseService->internalServerError("Error during creating a JobOffer, {$e->getMessage()}");
        }
    }

    public function getJobOfferById(int $id): JsonResponse
    {
        $jobOffer = JobOffer::find($id);

        return $this->responseService->success($jobOffer);
    }

    public function getJobOffersByCompanyId(int $id): JsonResponse
    {
        $jobOffers = JobOffer::where('company_id', $id)->get();

        return $this->responseService->success($jobOffers);
    }

    public function updateJobOfferById(int $id, array $data): JsonResponse
    {
        try {
            $jobOffer = JobOffer::find($id);

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

    public function deleteJobOfferById(int $id): JsonResponse
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
}
