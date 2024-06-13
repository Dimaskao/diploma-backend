<?php

namespace App\Http\Controllers;

use App\Models\RegularUser;
use App\Models\User;
use App\Models\Company;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


/*
3. Network of Connections (Contacts):
-	Ability to add and delete contacts. ???
-	Search and view profiles of other users.

5. Network of Enterprises and Companies:
-  Creation of company pages.
-  Search and view information about companies, including vacancies, news, contact information.
-  Ability to view user vacancies.
 * */

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
    public function addContact(Request $request, $id): JsonResponse
    {
//        list($user, $role) = $this->getUserAndRole($id);
//
//        if ($role === 'user') {
//            $contact = \App\Models\User::with('role')->findOrFail($id);
//            $user->contacts()->attach($contact);
//        } else {
//            return response()->json(['error' => 'Companies cannot add contacts'], 400);
//        }
//
        return response()->json(['message' => 'Contact added successfully']);
    }

    /**
     * Remove a contact for a user.
     */
    public function removeContact(Request $request, $id): JsonResponse
    {
        list($user, $role) = $this->getUserAndRole($id);

        if ($role === 'user') {
            $contact = \App\Models\User::with('role')->findOrFail($id);
            $user->contacts()->detach($contact);
        } else {
            return response()->json(['error' => 'Companies cannot remove contacts'], 400);
        }

        return response()->json(['message' => 'Contact removed successfully']);
    }

    /**
     * Search for users or companies.
     */
    public function search(Request $request)
    {
//        $query = $request->input('query');
//        list($user, $role) = $this->getUserAndRole();
//
//        if ($role === 'user') {
//            $results = RegularUser::with('role')->where('first_name', 'LIKE', "%$query%")
//                ->orWhere('last_name', 'LIKE', "%$query%")
//                ->orWhere('email', 'LIKE', "%$query%")
//                ->get();
//        } elseif ($role === 'company') {
//            $results = Company::with('role')->where('name', 'LIKE', "%$query%")
//                ->orWhere('contact_email', 'LIKE', "%$query%")
//                ->get();
//        } else {
//            return response()->json(['error' => 'Invalid role'], 400);
//        }
//
//        return response()->json($results);
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
        if (isset($request['update_type'])) {
            $updateType = $request['update_type'];

            if (isset($updateType['personal_information'])) {
                $this->updateUserProfile($updateType['personal_information'], $user);
            }

            if (isset($updateType['photos'])) {
                $this->updateUserPhotos($updateType['photos'], $user);
            }

            if (isset($updateType['education'])) {
                $this->updateUserEducation($updateType['education'], $user);
            }

            if (isset($updateType['work_experience'])) {
                $this->updateWorkExperience($updateType['work_experience'], $user);
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
     * @param User|null $user
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
     * @param User|null $user
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

    private function getRegularUserProfile(?RegularUser $user, $id): array
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
    private function getCompanyProfile(mixed $user): Builder|Model
    {
        return Company::with(['posts', 'job_offers'])
            ->where('id', $user->getAttribute('company_id'))
            ->firstOrFail();
    }
}