<?php

namespace App\Services;

use App\Models\Company;
use App\Models\JobOffer;
use App\Models\JobOfferSkill;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CompanyProfileService
{
    public function getCompanyProfile(mixed $user): JsonResponse
    {
        $company = $user->company;

        return response()->json([
            'profile' => [
                'id' => $user->id,
                'name' => $company->name,
                'description' => $company->description,
                'contactEmail' => $company->contact_email,
                'contactPhone' => $company->contact_phone,
                'contactUrl' => $company->contact_url,
                'posts' => $this->getUserPosts($user),
                'jobOffers' => $this->getCompanyJobOffers($company)
            ]
        ], 200);
    }

    public function updateCompanyInformation($user, Request $request): JsonResponse
    {
        if ($request->has('updateType')) {
            try {
                return response()->json([
                    'message' => 'Company information was updated successfully',
                    'updatedInformation' => $this->updateByUpdateType($request->input('updateType'), Company::find($user->user_id), $user, [])
                ], 200);
            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        }

        return response()->json(['message' => 'Unset update type'], 400);
    }

    public function deleteCompanyProfile($id): JsonResponse
    {
        $baseUser = User::find($id);
        $company = $baseUser->company;

        JobOffer::where('company_id', $company->id)->get()->map(function ($jobOffer) {
            JobOfferSkill::where('job_offer_id', $jobOffer->id)->delete();
        })->delete();

        Post::where('user_id', $baseUser->id)->get()->map(function ($post) {
            PostImage::where('post_id', $post->id)->delete();
        })->delete;

        $company->delete();
        $baseUser->delete();

        return response()->json(['message' => "Regular user profile was deleted"], 200);
    }

    /**
     * @throws ValidationException
     */
    private function updateCompanyProfile(array $personalInformation, $user, $baseUser)
    {
        $data = (new ValidationService())->validate($personalInformation, [
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
    private function updateByUpdateType($updateType, $user, $baseUser, array $updatedResults): array
    {
        if (isset($updateType['personalInformation'])) {
            $this->updateCompanyProfile($updateType['personalInformation'], $user, $baseUser);
            $updatedResults['personalInformation'] = [
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

    private function getUserUpdateData(array $data): array
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
                'requirementExperience' => $jobOffer->requirement_experience,
                'datePosted' => $jobOffer->date_posted,
                'validUntil' => $jobOffer->valid_until,
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
