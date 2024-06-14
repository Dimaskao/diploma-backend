<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Display the profile of a user or company.
     */
    public function show($id): JsonResponse
    {
        list($user, $role) = $this->getUserAndRole($id);

        if ($role === 'user') {
            $profile = $this->getRegularUserProfile($user, $id);
        } elseif ($role === 'company') {
            $profile = $this->getCompanyProfile($user);
        } else {
            return response()->json(['error' => 'Invalid role'], 400);
        }

        return response()->json($profile);
    }

    /**
     * Update the profile of a user or company.
     */
    public function update(Request $request, $id): JsonResponse
    {
        list($user, $role) = $this->getUserAndRole($id);

        if ($role === 'user') {
            if ($request->has('update_type')) {
                $user = DB::table('regular_users')
                    ->where('id', $user->user_id)
                    ->first();
                return $this->updateUserInformation($user, $request);
            }
        } elseif ($role === 'company') {
            $user = DB::table('companies')
                ->where('id', $user->company_id)
                ->first();
            return $this->updateCompanyInformation($user, $request);
        }

        return response()->json(['error' => 'Invalid request data'], 400);
    }

    /**
     * Add a new contact for a user.
     */
    public function subscribe(Request $request): JsonResponse
    {
        if ($request->has(['subscriberId', 'subscriptionId'])) {
            $subscriberId = $request->input('subscriberId');
            $subscriptionId = $request->input('subscriptionId');

            $subscriberUserId = DB::table('users')
                ->where('id', $subscriberId)
                ->value('user_id');

            $data['subscriber_id'] = DB::table('regular_users')
                ->where('id', $subscriberUserId)
                ->value('id');
            $data['subscription_id'] = $subscriptionId;
            DB::table('user_contacts')->insert($data);

            return response()->json(['message' => 'Subscribed successfully'], 200);
        }

        return response()->json(['message' => 'Bad request'], 400);
    }

    /**
     * Remove a contact for a user.
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        if ($request->has(['subscriberId', 'subscriptionId'])) {
            $subscriberId = $request->input('subscriberId');
            $subscriptionId = $request->input('subscriptionId');

            $subscriberUserId = DB::table('users')
                ->where('id', $subscriberId)
                ->value('user_id');

            $subscriberId = DB::table('regular_users')
                ->where('id', $subscriberUserId)
                ->value('id');

            $recordId = DB::table('user_contacts')
                ->where('subscriber_id', $subscriberId)
                ->where('subscription_id', $subscriptionId)
                ->value('id');
            DB::table('user_contacts')->delete($recordId);

            return response()->json(['message' => 'Unsubscribed successfully'], 200);
        }

        return response()->json(['message' => 'Bad request'], 400);
    }

    /**
     * Search for users or companies.
     */
    public function search(Request $request): JsonResponse
    {
        if ($request->has('query') && ($request->has('users') || $request->has('companies') || $request->has('all'))) {
            $query = $request->input('query');
            $results = [];

            if ($request->has('users') || $request->has('all')) {
                $users1 = DB::table('regular_users')
                    ->where('first_name', 'LIKE', "%{$query}%")
                    ->where('last_name', 'LIKE', "%{$query}%")
                    ->get();
                $users2 = DB::table('regular_users')
                    ->where('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('last_name', 'LIKE', "%{$query}%")
                    ->get();
                $users = $users1->merge($users2)->unique('id')->values();

                foreach ($users as $user) {
                    $results[] = [
                        'id' => DB::table('users')->where('user_id', $user->id)->value('id'),
                        'name' => "{$user->first_name} {$user->last_name}"
                    ];
                }
            }

            if ($request->has('companies') || $request->has('all')) {
                $companies = DB::table('companies')
                    ->where('name', 'LIKE', "%{$query}%")
                    ->get();

                foreach ($companies as $company) {
                    $results[] = [
                        'id' => DB::table('users')->where('user_id', $company->id)->value('id'),
                        'name' => $company->name
                    ];
                }
            }

            return response()->json(['results' => $results]);
        }

        return response()->json(['message' => 'Bad request'], 400);
    }

    /**
     * Update user information.
     *
     * @param $user
     * @param Request $request
     * @return JsonResponse
     */
    private function updateUserInformation($user, Request $request): JsonResponse
    {
        if ($request->has('updateType')) {
            $updateType = $request->input('updateType');

            if (isset($updateType['personalInformation'])) {
                $this->updateUserProfile($updateType['personalInformation'], $user);
            }

            if (isset($updateType['photos'])) {
                $this->updateUserPhotos($updateType['photos'], $user);
            }

            if (isset($updateType['education'])) {
                $this->updateUserEducation($updateType['education'], $user);
            }

            if (isset($updateType['workExperience'])) {
                $this->updateWorkExperience($updateType['workExperience'], $user);
            }

            if (isset($updateType['skills'])) {
                $this->updateUserSkills($updateType['skills'], $user);
            }

            return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
        }

        return response()->json(['message' => 'Unset update type'], 400);
    }

    /**
     * Update company information.
     *
     * @param $user
     * @param Request $request
     * @return JsonResponse
     */
    private function updateCompanyInformation($user, Request $request): JsonResponse
    {
        // Implement company update logic here

        return response()->json(['message' => 'Profile updated successfully', 'company' => $user]);
    }

    /**
     * Get the user and their role.
     *
     * @param int $id
     * @return array
     */
    private function getUserAndRole(int $id): array
    {
        $user = DB::table('users')
            ->where('id', $id)
            ->first();
        $role = $user->role;
        return [$user, $role];
    }

    /**
     * Update user profile information.
     *
     * @param array $personalInformation
     * @param $user
     */
    private function updateUserProfile(array $personalInformation, $user)
    {
        $validator = Validator::make($personalInformation, [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'avatar_url' => 'sometimes|url',
            'skills_desc' => 'sometimes|string',
            'experience' => 'sometimes|string',
            'contact_email' => 'sometimes|email',
            'contact_phone' => 'sometimes|string|max:20',
            'contact_url' => 'sometimes|url',
            'desc' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data = $validator->validated();

        if (isset($data['id'])) {
            $record = DB::table('regular_users')
                ->where('id', $user->id)
                ->first();
            $record->update($data);
        } else {
            return response()->json(['error' => "The user with id {$user->id} does not exist"], 400);
        }
    }

    /**
     * Update work experience.
     *
     * @param array $workExperience
     * @param $user
     * @return JsonResponse
     */
    private function updateWorkExperience(array $workExperience, $user): JsonResponse
    {
        $data = $this->getValidatedData($workExperience, [
            'position' => 'sometimes|string|max:255',
            'company' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'description' => 'sometimes|string|max:255',
        ]);

        if (isset($data['id'])) {
            $workExperienceRecord = DB::table('work_experiences')
                ->where('id', $data['id'])
                ->first();
        } else {
            $workExperienceRecord = null;
        }

        if ($workExperienceRecord) {
            $workExperienceRecord->update($data);
        } else {
            $data['user_id'] = $user->user_id;
            DB::table('work_experiences')->insert($data);
        }

        return response()->json(['message' => 'Work experience updated successfully', 'workExperience' => $data]);
    }

    /**
     * Update education.
     *
     * @param array $education
     * @param $user
     * @return JsonResponse
     */
    private function updateUserEducation(array $education, $user): JsonResponse
    {
        $data = $this->getValidatedData($education, [
            'institution' => 'sometimes|string|max:255',
            'degree' => 'sometimes|string|max:255',
            'field_of_study' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'contact_url' => 'sometimes|url',
        ]);

        if (isset($data['id'])) {
            $educationRecord = DB::table('user_educations')
                ->where('id', $data['id'])
                ->first();
        } else {
            $educationRecord = null;
        }

        if ($educationRecord) {
            $educationRecord->update($data);
        } else {
            $data['user_id'] = $user->user_id;
            DB::table('user_educations')->insert($data);
        }

        return response()->json(['message' => 'Education updated successfully', 'education' => $data]);
    }

    private function updateUserPhotos($request, $user)
    {
        // Implement photo update logic here
    }

    private function updateUserSkills($skill, $user): JsonResponse
    {
        $data = $this->getValidatedData($skill, [
            'name' => 'string|max:255'
        ]);

        if (isset($data['id'])) {
            $skillsRecord = DB::table('skills')
                ->where('id', $data['id'])
                ->first();
        } else {
            $skillsRecord = null;
        }

        if ($skillsRecord) {
            $skillsRecord->update($data);
        } else {
            $data['user_id'] = $user->user_id;
            DB::table('skills')->insert($data);
        }

        return response()->json(['message' => 'Skills updated successfully', 'skills' => $data]);
    }

    /**
     * Validate data with given rules.
     *
     * @param array $data
     * @param array $rules
     * @return array
     * @throws ValidationException
     */
    private function getValidatedData(array $data, array $rules): array
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Get the profile of a regular user.
     *
     * @param $user
     * @param int $id
     * @return array
     */
    private function getRegularUserProfile($user, int $id): array
    {
        $regularUserRecord = DB::table('regular_users')
            ->where('id', $user->user_id)
            ->first();
        $user = [
            'id' => $user->id,
            'firstName' => $regularUserRecord->first_name,
            'lastName' => $regularUserRecord->last_name,
            'skillsDesc' => $regularUserRecord->skills_desc,
            'experience' => $regularUserRecord->experience,
        ];

        $educationRecords = DB::table('user_educations')
            ->where('user_id', $regularUserRecord->id)
            ->get();
        $education = [];
        foreach ($educationRecords as $educationRecord) {
            $education[] = [
                'institution' => $educationRecord->institution,
                'degree' => $educationRecord->degree,
                'fieldOfStudy' => $educationRecord->field_of_study,
                'startDate' => $educationRecord->start_date,
                'endDate' => $educationRecord->end_date,
                'contactUrl' => $educationRecord->contact_url
            ];
        }

        $workExperienceRecords = DB::table('work_experiences')
            ->where('user_id', $regularUserRecord->id)
            ->get();
        $workExperience = [];
        foreach ($workExperienceRecords as $workExperienceRecord) {
            $workExperience[] = [
                'position' => $workExperienceRecord->position,
                'description' => $workExperienceRecord->description,
                'dateStart' => $workExperienceRecord->date_start,
                'dateEnd' => $workExperienceRecord->date_end
            ];
        }

        $skillsRecords = DB::table('skills')
            ->where('user_id', $regularUserRecord->id)
            ->get();
        $skills = [];
        foreach ($skillsRecords as $skillsRecord) {
            $skills[] = [
                'name' => $skillsRecord->name
            ];
        }

        return [
            'id' => $id,
            'user' => $user,
            'education' => $education,
            'workExperience' => $workExperience,
            'skills' => $skills
        ];
    }

    /**
     * Get the profile of a company.
     *
     * @param mixed $user
     * @return array
     */
    private function getCompanyProfile(mixed $user): array
    {
        $company = DB::table('companies')
            ->where('id', $user->company_id)
            ->first();

        $companyJobOffers = DB::table('job_offers')
            ->where('company_id', $company->id)
            ->get();

        $jobOffers = [];
        foreach ($companyJobOffers as $jobOffer) {
            $jobOfferSkills = DB::table('skills')
                ->where('job_offer_id', $jobOffer->id)
                ->get();

            $skills = [];
            foreach ($jobOfferSkills as $jobOfferSkill) {
                $skills[] = [
                    'name' => $jobOfferSkill->name
                ];
            }

            $jobOffers[] = [
                'title' => $jobOffer->title,
                'position' => $jobOffer->position,
                'description' => $jobOffer->description,
                'requirements' => $jobOffer->requirements,
                'requirementExperience' => $jobOffer->requirement_experience,
                'datePosted' => $jobOffer->date_posted,
                'validUntil' => $jobOffer->valid_until,
                'skills' => $skills
            ];
        }

        $companyPosts = DB::table('posts')
            ->where('user_id', $user->id)
            ->get();

        $posts = [];
        foreach ($companyPosts as $companyPost) {
            $postImages = DB::table('post_images')
                ->where('post_id', $companyPost->id)
                ->get();

            $images = [];
            foreach ($postImages as $postImage) {
                $images[] = [
                    'url' => $postImage->url
                ];
            }

            $postSkills = DB::table('skills')
                ->where('post_id', $companyPost->id)
                ->get();

            $skills = [];
            foreach ($postSkills as $postSkill) {
                $skills[] = [
                    'name' => $postSkill->name
                ];
            }

            $posts[] = [
                'title' => $companyPost->title,
                'content' => $companyPost->content,
                'images' => $images,
                'skills' => $skills
            ];
        }

        return [
            'id' => $user->id,
            'name' => $company->name,
            'description' => $company->description,
            'contactEmail' => $company->contact_email,
            'contactPhone' => $company->contact_phone,
            'contactUrl' => $company->contact_url,
            'posts' => $posts,
            'jobOffers' => $jobOffers
        ];
    }
}
