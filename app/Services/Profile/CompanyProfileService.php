<?php

namespace App\Services\Profile;

use App\Enums\ResponseKeys;
use App\Enums\UpdateType;
use App\Models\JobOffer;
use App\Models\JobOfferSkill;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CompanyProfileService extends BaseSpecificProfileService
{
    public function getProfile(mixed $user): JsonResponse
    {
        $company = $user->profileable;
        return $this->responseService->response(ResponseKeys::PROFILE, [
            ResponseKeys::PROFILE => [
                'id' => $user->id,
                'name' => $company->name,
                'description' => $company->description,
                'contact_email' => $company->contact_email,
                'contact_phone' => $company->contact_phone,
                'contact_url' => $company->contact_url,
                'posts' => $this->getUserPosts($user),
                'job_offers' => $this->getCompanyJobOffers($company)
            ]
        ], 200);
    }

    public function updateProfile($user, Request $request): JsonResponse
    {
        if ($request->has(UpdateType::UPDATE_TYPE)) {
            try {
                $company = $user->profileable;
                return $this->responseService->response(ResponseKeys::RESULT, [
                    ResponseKeys::MESSAGE => 'Company information was updated successfully',
                    ResponseKeys::UPDATED_INFORMATION => $this->updateByUpdateType($request->input(UpdateType::UPDATE_TYPE), $company, $user)
                ], 200);
            } catch (Exception $e) {
                return $this->responseService->response(ResponseKeys::ERROR, $e->getMessage(), 500);
            }
        }
        return $this->responseService->response(ResponseKeys::ERROR, 'Unset update type', 400);
    }

    public function deleteProfile($id): JsonResponse
    {
        $baseUser = User::find($id);
        if ($baseUser) {
            $company = $baseUser->profileable;

            $jobOffers = JobOffer::where('company_id', $company->id)->get();
            foreach ($jobOffers as $jobOffer) {
                JobOfferSkill::where('job_offer_id', $jobOffer->id)->delete();
                $jobOffer->delete();
            }

            $posts = Post::where('user_id', $baseUser->id)->get();
            foreach ($posts as $post) {
                PostImage::where('post_id', $post->id)->delete();
                $post->delete();
            }

            $company->delete();
            $baseUser->delete();

            return $this->responseService->response(ResponseKeys::MESSAGE, "Company profile was deleted", 200);
        } else {
            return $this->responseService->response(ResponseKeys::ERROR, "User not found", 404);
        }
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    private function updateCompanyProfile(array $personalInformation, $user, $baseUser)
    {
        $data = $this->validator->validate($personalInformation, [
            'description' => 'sometimes|string|max:255',
            'name' => 'sometimes|string',
            'contact_email' => 'sometimes|string',
            'contact_phone' => 'sometimes|string',
            'password' => 'sometimes|string|min:8',
            'contact_url' => 'sometimes|url',
            'avatar_url' => 'sometimes|url'
        ]);

        if ($user && $baseUser) {
            $userUpdateData = $this->getCompanyUpdateData($data);
            $baseUserUpdateData = $this->getUserUpdateData($data);

            if (!empty($userUpdateData)) {
                $user->update($userUpdateData);
            }

            if (!empty($baseUserUpdateData)) {
                $baseUser->update($baseUserUpdateData);
            }
        } else {
            throw new Exception('Error while updating user profile');
        }
    }

    /**
     * @throws Exception
     */
    private function updateByUpdateType($updateType, $user, $baseUser): array
    {
        $updatedResults = [];
        if (isset($updateType[UpdateType::PERSONAL_INFORMATION])) {
            $this->updateCompanyProfile($updateType[UpdateType::PERSONAL_INFORMATION], $user, $baseUser);
            $updatedResults[UpdateType::PERSONAL_INFORMATION] = [
                'id' => $baseUser->id,
                'description' => $user->description,
                'name' => $user->name,
                'contact_email' => $user->contact_email,
                'contact_phone' => $user->contact_phone,
                'contact_url' => $user->contact_url,
                'avatar_url' => $baseUser->avatar_url,
                'email' => $baseUser->email,
            ];
        }

        return $updatedResults;
    }

    /**
     * @param array $data
     * @return array
     */
    private function getCompanyUpdateData(array $data): array
    {
        $userUpdateData = [];

        if (isset($data['description'])) {
            $userUpdateData['description'] = $data['description'];
        }

        if (isset($data['name'])) {
            $userUpdateData['name'] = $data['name'];
        }

        if (isset($data['contact_email'])) {
            $userUpdateData['contact_email'] = $data['contact_email'];
        }

        if (isset($data['contact_phone'])) {
            $userUpdateData['contact_phone'] = $data['contact_phone'];
        }

        if (isset($data['contact_url'])) {
            $userUpdateData['contact_url'] = $data['contact_url'];
        }

        return $userUpdateData;
    }

    public function getCompanyJobOffers($company): array
    {
        return JobOffer::where('company_id', $company->id)->get()->map(function ($jobOffer) {
            return [
                'id' => $jobOffer->id,
                'title' => $jobOffer->title,
                'position' => $jobOffer->position,
                'description' => $jobOffer->description,
                'requirements' => $jobOffer->requirements,
                'requirement_experience' => $jobOffer->requirement_experience,
                'date_posted' => $jobOffer->date_posted,
                'valid_until' => $jobOffer->valid_until,
                'skills' => $this->getJobOfferSkills($jobOffer)
            ];
        })->toArray();
    }

    public function getJobOfferSkills($jobOffer): array
    {
        return JobOfferSkill::where('job_offer_id', $jobOffer->id)->get()->map(function ($jobOfferSkill) {
            return [
                'id' => $jobOfferSkill->id,
                'name' => $jobOfferSkill->name
            ];
        })->toArray();
    }

    public function getUserPosts($user): array
    {
        return Post::where('user_id', $user->id)->get()->map(function ($post) {
            return [
                'title' => $post->title,
                'content' => $post->content,
                'images' => $this->getPostImages($post),
            ];
        })->toArray();
    }

    public function getPostImages($post): array
    {
        return PostImage::where('post_id', $post->id)->get()->map(function ($postImage) {
            return ['url' => $postImage->url];
        })->toArray();
    }
}
