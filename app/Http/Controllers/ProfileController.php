<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
    public function show($id)
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
    public function update(Request $request, $id)
    {
        list($user, $role) = $this->getUserAndRole($id);

        if ($role === 'user') {
            if (isset($request['update_type'])) {
                $user = DB::table('regular_users')
                    ->where('id', $user->getAttribute('user_id'))
                    ->first();
                return $this->updateUserInformation($user, $request);
            }
        } elseif ($role === 'company') {
            $user = DB::table('companies')
                ->where('id', $user->getAttribute('company_id'))
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
        if (isset($request['subscriberId']) && isset($request['subscriptionId'])) {
            $data['subscriber_id'] = DB::table('regular_users')
                ->where('id', (DB::table('users')->where('id', $request['subscriberId'])->first())->getAttribute('user_id'))
                ->first()->getAttribute('id');
            $data['subscription_id'] = $request['subscriptionId'];
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
        if (isset($request['subscriberId']) && isset($request['subscriptionId'])) {
            $recordId = DB::table('user_contacts')
                ->where('subscriber_id', DB::table('regular_users')
                    ->where('id', (DB::table('users')->where('id', $request['subscriberId'])->first())->getAttribute('user_id'))
                    ->first()->getAttribute('id'))
                ->where('subscription_id', $request['subscriptionId'])
                ->first()->getAttribute('id');
            DB::table('user_contacts')->delete($recordId);

            return response()->json(['message' => 'Unsubscribed successfully'], 200);
        }

        return response()->json(['message' => 'Bad request'], 400);
    }

    /**
     * Search for users or companies.
     */
    public function search(Request $request)
    {
        if (isset($request['query']) && (isset($request['users']) || isset($request['companies']) || isset($request['all']))) {
            $query = $request['query'];
            $results = [];

            if (isset($request['users']) || isset($request['all'])) {
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
                        'id' => (DB::table('users')->where('user_id', $user->getAttribute('id'))->first()->getAttribute('id')),
                        'name' => "{$user->getAttribute('first_name')} {$user->getAttribute('last_name')}"
                    ];
                }
            }

            if (isset($request['companies']) || isset($request['all'])) {
                $companies = DB::table('companies')
                    ->where('name', 'LIKE', "%$query%")
                    ->get();

                foreach ($companies as $company) {
                    $results[] = [
                        'id' => (DB::table('users')->where('user_id', $user->getAttribute('id'))->first()->getAttribute('id')),
                        'name' => $company->getAttribute('name')
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
     * @param $request
     * @return JsonResponse
     */
    private function updateUserInformation($user, $request): JsonResponse
    {
        if (isset($request['updateType'])) {
            $updateType = $request['updateType'];

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

        return response()->json(['message' => 'Unset update type', 400]);
    }

    /**
     * Update company information.
     *
     * @param $user
     * @param $request
     * @return JsonResponse
     */
    private function updateCompanyInformation($user, $request)
    {
//        $company = $user->company;
//        $company->update($data);

//        return response()->json(['message' => 'Profile updated successfully', 'company' => $company]);
    }

    /**
     * @return array
     */
    private function getUserAndRole($id): array
    {
        $user = DB::table('users')
            ->where('id', $id)
            ->first();
        $role = $user->getAttribute('role');
        return array($user, $role);
    }

    private function updateUserProfile($personalInformation, $user)
    {
        $validator = Validator::make($personalInformation->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'avatar_url' => 'sometimes|url',
            'skills_desc' => 'sometimes|string',
            'experience' => 'sometimes|string',
            'contact_email' => 'sometimes|email',
            'contact_phone' => 'sometimes|string|max:20',
            'contact_url' => 'sometimes|url',
            'desc' => 'sometimes|string',
        ], [
            'required_if' => 'The :attribute field is required when the role is :value.',
            'unique' => 'The :attribute has already been taken.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $data = $validator->validated();
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        }

        if (isset($data['id'])) {
            $record = DB::table('regular_users')
                ->where('id', $user->getAttribute('id'))
                ->first();
            $record->update($data);
        } else {
            return response()->json(['error' => "The user with id $user->getAttribute('id') does not exist"], 400);
        }
    }

    /**
     * Update work experience.
     *
     * @param array $work_experience
     * @return JsonResponse
     */
    private function updateWorkExperience($workExperience, $user)
    {
        try {
            $data = $this->getValidatedData($workExperience, [
                'position' => 'sometimes|string|max:255',
                'company' => 'sometimes|string|max:255',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date',
                'description' => 'sometimes|string|max:255',
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 400);
        }

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
            $data['user_id'] = $user->getAttribute('user_id');
            DB::table('work_experiences')->insert($data);
        }

        return response()->json(['message' => 'Education updated successfully', 'education' => $data]);
    }

    /**
     * Update education.
     *
     * @param array $education
     * @return JsonResponse
     */
    private function updateUserEducation($education, $user)
    {
        try {
            $data = $this->getValidatedData($education, [
                'institution' => 'sometimes|string|max:255',
                'degree' => 'sometimes|string|max:255',
                'field_of_study' => 'sometimes|string|max:255',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date',
                'contact_url' => 'sometimes|url',
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 400);
        }

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
            $data['user_id'] = $user->getAttribute('user_id');
            DB::table('user_educations')->insert($data);
        }

        return response()->json(['message' => 'Education updated successfully', 'education' => $data]);
    }

    private function updateUserPhotos($request, $user)
    {
    }

    private function updateUserSkills($skill, $user)
    {
        try {
            $data = $this->getValidatedData($skill, [
                'name' => 'string|max:255'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 400);
        }

        if (isset($data['id'])) {
            $educationRecord = DB::table('skills')
                ->where('id', $data['id'])
                ->first();
        } else {
            $educationRecord = null;
        }

        if ($educationRecord) {
            $educationRecord->update($data);
        } else {
            $data['user_id'] = $user->getAttribute('user_id');
            DB::table('skills`')->insert($data);
        }

        return response()->json(['message' => 'Education updated successfully', 'education' => $data]);
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    private function getValidatedData($data, $rules)
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new Exception('Validation failed');
        }

        return $validator->validated();
    }

    private function getRegularUserProfile($user, $id): array
    {
        $regularUserRecord = DB::table('regular_users')
            ->where('id', $user->getAttribute('user_id'))
            ->first();
        $user = [
            'id' => $user->getAttribute('id'),
            'firstName' => $regularUserRecord->getAttribute('first_name'),
            'lastName' => $regularUserRecord->getAttribute('last_name'),
            'skillsDesc' => $regularUserRecord->getAttribute('skills_desc'),
            'experience' => $regularUserRecord->getAttribute('experience'),
        ];

        $educationRecords = DB::table('user_education')
            ->where('user_id', $regularUserRecord->getAttribute('id'))
            ->get();
        $education = [];
        foreach ($educationRecords as $educationRecord) {
            $education[] = [
                'institution' => $educationRecord->getAttribute('institution'),
                'degree' => $educationRecord->getAttribute('degree'),
                'fieldOfStudy' => $educationRecord->getAttribute('field_of_study'),
                'startDate' => $educationRecord->getAttribute('start_date'),
                'endDate' => $educationRecord->getAttribute('end_date'),
                'contactUrl' => $educationRecord->getAttribute('contact_url')
            ];
        }

        $workExperienceRecords = DB::table('work_experiences')
            ->where('user_id', $regularUserRecord->getAttribute('id'))
            ->get();
        $workExperience = [];
        foreach ($workExperienceRecords as $workExperienceRecord) {
            $workExperience[] = [
                'position' => $workExperienceRecord->getAttribute('position'),
                'description' => $workExperienceRecord->getAttribute('description'),
                'dateStart' => $workExperienceRecord->getAttribute('date_start'),
                'dateEnd' => $workExperienceRecord->getAttribute('date_end')
            ];
        }

        $skillsRecords = DB::table('skills')
            ->where('user_id', $regularUserRecord->getAttribute('id'))
            ->get();
        $skills = [];
        foreach ($skillsRecords as $skillsRecord) {
            $skills[] = [
                'name' => $skillsRecord->getAttribute('name')
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
     * @param mixed $user
     * @return Builder|Model
     */
    private function getCompanyProfile(mixed $user): array
    {
        $company = DB::table('companies')
            ->where('id', $user->getAttribute('company_id'))
            ->first();

        $companyJobOffers = DB::table('job_offers')
            ->where('company_id', $company->getAttribute('id'))
            ->get();

        $jobOffers = [];
        foreach ($companyJobOffers as $jobOffer) {
            $jobOfferSkills = DB::table('skills')
                ->where('job_offer_id', $jobOffer->getAttribute('id'))
                ->get();

            $skills = [];
            foreach ($jobOfferSkills as $jobOfferSkill) {
                $skills[] = [
                    'name' => $jobOfferSkill->getAttribute('name')
                ];
            }

            $jobOffers[] = [
                'title' => $jobOffer->getAttribute('title'),
                'position' => $jobOffer->getAttribute('position'),
                'description' => $jobOffer->getAttribute('description'),
                'requirements' => $jobOffer->getAttribute('requirements'),
                'requirementExperience' => $jobOffer->getAttribute('requirement_experience'),
                'datePosted' => $jobOffer->getAttribute('date_posted'),
                'validUntil' => $jobOffer->getAttribute('valid_until'),
                'skills' => $skills
            ];
        }

        $companyPosts = DB::table('posts')
            ->where('user_id', $user->getAttribute('id'))
            ->get();

        $posts = [];
        foreach ($companyPosts as $companyPost) {
            $postImages = DB::table('post_images')
                ->where('post_id', $companyPost->getAttribute('id'))
                ->get();

            $images = [];
            foreach ($postImages as $postImage) {
                $images[] = [
                    'url' => $postImage->getAttribute('url')
                ];
            }

            $postSkills = DB::table('skills')
                ->where('post_id', $companyPost->getAttribute('id'))
                ->get();

            $skills = [];
            foreach ($postSkills as $postSkill) {
                $skills[] = [
                    'name' => $postSkill->getAttribute('name')
                ];
            }

            $posts[] = [
                'title' => $companyPost->getAttribute('title'),
                'content' => $companyPost->getAttribute('content'),
                'images' => $images,
                'skills' => $skills
            ];
        }

        return [
            'id' => $user->getAttribute('id'),
            'name' => $company->getAttribute('name'),
            'description' => $company->getAttribute('description'),
            'contactEmail' => $company->getAttribute('contact_email'),
            'contactPhone' => $company->getAttribute('contact_phone'),
            'contactUrl' => $company->getAttribute('contact_url'),
            'posts' => $posts,
            'jobOffers' => $jobOffers
        ];
    }
}
