<?php

namespace App\Traits;

use App\Enums\EditInfoType;
use App\Models\RegularUser;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserSkill;
use App\Models\WorkExperience;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait RegularUserProfileTrait
{
    /**
     * @param array $data
     * @param array $dataToUpdate
     * @return array
     */
    public function getUserEducationDataToProceed(array $data, array $dataToUpdate): array
    {
        if (isset($data['start_date'])) {
            $dataToUpdate['start_date'] = $data['start_date'];
        }

        if (isset($data['end_date'])) {
            $dataToUpdate['end_date'] = $data['end_date'];
        }

        if (isset($data['contact_url'])) {
            $dataToUpdate['contact_url'] = $data['contact_url'];
        }
        return $dataToUpdate;
    }

    /**
     * @param array $data
     * @param array $newData
     * @return array
     */
    public function getWorkExperienceDataToProceed(array $data, array $newData): array
    {
        Log::debug('data to insert: ' . var_export($data, 1));

        if (isset($data['description'])) {
            $newData['description'] = $data['description'];
        }

        if (isset($data['date_start'])) {
            $newData['date_start'] = $data['date_start'];
        }

        if (isset($data['date_end'])) {
            $newData['date_end'] = $data['date_end'];
        }
        return $newData;
    }

    /**
     * @param $user
     * @param array $data
     */
    public function insertWorkExperienceRecord($user, array $data)
    {
        $dataToInsert = [
            'position' => $data['position']
        ];

        $dataToInsert = $this->getWorkExperienceDataToProceed($data, $dataToInsert);

        $dataToInsert['user_id'] = $user->id;
        $dataToInsert['id'] = (string)Str::uuid();

        WorkExperience::insert($dataToInsert);
        return WorkExperience::find($dataToInsert['id']);
    }

    /**
     * @param $workExperienceRecord
     * @param array $data
     */
    public function updateWorkExperienceRecord($workExperienceRecord, array $data)
    {
        $dataToUpdate = [];

        if (isset($data['position'])) {
            $dataToUpdate['position'] = $data['position'];
        }

        $dataToUpdate = $this->getWorkExperienceDataToProceed($data, $dataToUpdate);
        Log::info('Updating WE record , $dataToUpdate: ' . var_export($dataToUpdate, 1));

        $workExperienceRecord->update($dataToUpdate);
        return $workExperienceRecord;
    }

    /**
     * @param array $data
     * @param $educationRecord
     * @return array
     */
    public function updateEducationRecord(array $data, $educationRecord)
    {
        $dataToUpdate = [];

        if (isset($data['institution'])) {
            $dataToUpdate['institution'] = $data['institution'];
        }

        if (isset($data['degree'])) {
            $dataToUpdate['degree'] = $data['degree'];
        }

        if (isset($data['field_of_study'])) {
            $dataToUpdate['field_of_study'] = $data['field_of_study'];
        }

        $dataToUpdate = $this->getUserEducationDataToProceed($data, $dataToUpdate);

        $educationRecord->update($dataToUpdate);
        return $educationRecord;
    }

    /**
     * @param array $data
     * @param $user
     * @return array
     */
    public function insertEducationRecord(array $data, $user)
    {
        $dataToInsert = [
            'institution' => $data['institution'],
            'degree' => $data['degree'],
            'field_of_study' => $data['field_of_study']
        ];

        $dataToInsert = $this->getUserEducationDataToProceed($data, $dataToInsert);

        $dataToInsert['user_id'] = $user->id;
        $dataToInsert['id'] = (string)Str::uuid();

        UserEducation::insert($dataToInsert);
        return UserEducation::find($dataToInsert['id']);
    }

    /**
     * @param $regularUserRecord
     * @return array
     */
    private function getRegularUserSkills($regularUserRecord): array
    {
        return UserSkill::where('user_id', $regularUserRecord->id)->get()->map(function ($userSkill) {
            return [
                'id' => $userSkill->id,
                'name' => $userSkill->name
            ];
        })->toArray();
    }

    /**
     * @param $regularUserRecord
     * @return array
     */
    private function getRegularUserWorkExperience($regularUserRecord): array
    {
        return WorkExperience::where('user_id', $regularUserRecord->id)->get()->map(function ($workExperienceRecord) {
            return [
                'position' => $workExperienceRecord->position,
                'description' => $workExperienceRecord->description,
                'dateStart' => $workExperienceRecord->date_start,
                'dateEnd' => $workExperienceRecord->date_end
            ];
        })->toArray();
    }

    /**
     * @param $regularUserRecord
     * @return array
     */
    private function getRegularUserEducation($regularUserRecord): array
    {
        return UserEducation::where('user_id', $regularUserRecord->id)->get()->map(function ($educationRecord) {
            return [
                'institution' => $educationRecord->institution,
                'degree' => $educationRecord->degree,
                'fieldOfStudy' => $educationRecord->field_of_study,
                'startDate' => $educationRecord->start_date,
                'endDate' => $educationRecord->end_date,
                'contactUrl' => $educationRecord->contact_url
            ];
        })->toArray();
    }

    private function getRegularUserProfile($user): JsonResponse
    {
        $regularUserRecord = $user->regularUser;

        return response()->json([
            'profile' => [
                'user' => [
                    'id' => $user->id,
                    'firstName' => $regularUserRecord->first_name,
                    'lastName' => $regularUserRecord->last_name,
                    'skillsDesc' => $regularUserRecord->skills_desc,
                    'experience' => $regularUserRecord->experience,
                ],
                'education' => $this->getRegularUserEducation($regularUserRecord),
                'workExperience' => $this->getRegularUserWorkExperience($regularUserRecord),
                'skills' => $this->getRegularUserSkills($regularUserRecord)
            ]
        ], 200);
    }

    private function updateUserInformation($user, Request $request): JsonResponse
    {
        if ($request->has('updateType')) {
            try {
                return response()->json([
                    'message' => 'User information was updated successfully',
                    'updatedInformation' => $this->updateByUpdateType($request->input('updateType'), RegularUser::find($user->user_id), $user, [])
                ], 200);
            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        }

        return response()->json(['message' => 'Unset update type'], 400);
    }

    /**
     * @throws Exception
     */
    private function updateByUpdateType($updateType, $user, $baseUser, array $updatedResults): array
    {
        if (isset($updateType['personalInformation'])) {
            $this->updateUserProfile($updateType['personalInformation'], $user, $baseUser);
            $updatedResults['personalInformation'] = [
                'id' => $baseUser->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'skills_desc' => $user->skills_desc,
                'experience' => $user->experience,
                'avatar_url' => $baseUser->avatar_url,
                'email' => $baseUser->email,
            ];
        }

        if (isset($updateType['education'])) {
            $updatedResults['education'] = $this->updateUserEducation($updateType['education'], $user);
        }

        if (isset($updateType['workExperience'])) {
            $updatedResults['workExperience'] = $this->updateWorkExperience($updateType['workExperience'], $user);
        }

        if (isset($updateType['skills'])) {
            $updatedResults['skills'] = $this->updateUserSkills($updateType['skills'], $user);
        }

        return $updatedResults;
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    private function updateUserProfile(array $personalInformation, $user, $baseUser)
    {
        $data = $this->getValidatedData($personalInformation, [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'skills_desc' => 'sometimes|string',
            'experience' => 'sometimes|string',
            'email' => 'sometimes|string',
            'password' => 'sometimes|string|min:8',
            'avatar_url' => 'sometimes|url'
        ]);

        if ($user && $baseUser) {
            $userUpdateData = $this->getRegularUserUpdateData($data);
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

    private function updateUserEducation($education, $user)
    {
        $result = [];

        foreach ($education as $e) {
            if (isset($e['start_date'])) {
                $e['start_date'] = date('Y-m-d H:i:s', $e['start_date']);
            }

            if (isset($e['end_date'])) {
                $e['end_date'] = date('Y-m-d H:i:s', $e['end_date']);
            }

            $data = $this->getValidatedData($e, [
                'institution' => 'sometimes|string|max:255',
                'degree' => 'sometimes|string|max:255',
                'field_of_study' => 'sometimes|string|max:255',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date',
                'contact_url' => 'sometimes|url',
            ]);

            if (isset($e['id'])) {
                $data['id'] = $e['id'];
                $educationRecord = UserEducation::find($data['id']);
            } else {
                $educationRecord = null;
            }

            if ($educationRecord) {
                $educationRecord = $this->updateEducationRecord($data, $educationRecord);
            } else {
                $educationRecord = $this->insertEducationRecord($data, $user);
            }
            $result[] = [
                'id' => $educationRecord->id,
                'institution' => $educationRecord->institution,
                'degree' => $educationRecord->degree,
                'field_of_study' => $educationRecord->field_of_study,
                'start_date' => $educationRecord->start_date,
                'end_date' => $educationRecord->end_date,
                'contact_url' => $educationRecord->contact_url,
            ];
        }

        return $result;
    }

    /**
     * @throws ValidationException
     */
    private function updateWorkExperience($workExperience, $user)
    {
        $result = [];
        foreach ($workExperience as $experience) {
            if (isset($experience['date_start'])) {
                $experience['date_start'] = date('Y-m-d H:i:s', $experience['date_start']);
            }

            if (isset($experience['date_end'])) {
                $experience['date_end'] = date('Y-m-d H:i:s', $experience['date_end']);
            }

            $data = $this->getValidatedData($experience, [
                'position' => 'sometimes|string|max:255',
                'company' => 'sometimes|string|max:255',
                'date_start' => 'sometimes|date',
                'date_end' => 'sometimes|date',
                'description' => 'sometimes|string|max:255',
            ]);

            if (isset($experience['id'])) {
                $data['id'] = $experience['id'];
                $workExperienceRecord = WorkExperience::find($data['id']);
            } else {
                $workExperienceRecord = null;
            }

            if ($workExperienceRecord) {
                $workExperienceRecord = $this->updateWorkExperienceRecord($workExperienceRecord, $data);
            } else {
                $workExperienceRecord = $this->insertWorkExperienceRecord($user, $data);
            }

            $result[] = [
                'id' => $workExperienceRecord->id,
                'position' => $workExperienceRecord->position,
                'description' => $workExperienceRecord->description,
                'date_start' => $workExperienceRecord->date_start,
                'date_end' => $workExperienceRecord->date_end
            ];
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    private function updateUserSkills($skills, $user)
    {
        $result = [];
        foreach ($skills as $skill) {
            if (isset($skill['id']) && isset($skill['editInfo'])) {
                $skillId = $skill['id'];
                $editInfo = $skill['editInfo'];

                if ($editInfo == EditInfoType::Add->value) {
                    $result[] = $this->addSkill($skillId, $user);
                } elseif ($editInfo == EditInfoType::Remove->value) {
                    $result[] = $this->removeSkill($skillId, $user);
                } else {
                    throw new Exception('Update type does not exist');
                }
            } else {
                throw new Exception('Skill id was not set');
            }
        }
        return $result;
    }

    /**
     * @throws ValidationException
     */
    private function getValidatedData(array $data, array $rules): array
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            Log::error('Validation failed: ' . var_export($validator->errors()->all(), true));
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    private function addSkill($skillId, $user)
    {
        $userSkillRecordId = (string)Str::uuid();
        UserSkill::insert([
            'id' => $userSkillRecordId,
            'user_id' => $user->id,
            'skill_id' => $skillId
            ]
        );
        return [
            'id' => $userSkillRecordId,
            'editInfo' => EditInfoType::Add->value,
            'result' => 'success'
        ];
    }

    private function removeSkill($skillId, $user)
    {
        $record = UserSkill::where('skill_id', $skillId)->where('user_id', $user->id)->first();
        if ($record) {
            $record->delete();
            return [
                'editInfo' => EditInfoType::Remove->value,
                'result' => 'success'
            ];
        }
        return [
            'id' => $record->id,
            'editInfo' => EditInfoType::Remove->value,
            'result' => 'error'
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    private function getUserUpdateData(array $data): array
    {
        $baseUserUpdateData = [];

        if (isset($data['email'])) {
            $baseUserUpdateData['email'] = $data['email'];
        }

        if (isset($data['password'])) {
            $baseUserUpdateData['password'] = bcrypt($data['password']);
        }

        if (isset($data['email'])) {
            $baseUserUpdateData['avatar_url'] = $data['avatar_url'];
        }
        return $baseUserUpdateData;
    }

    /**
     * @param array $data
     * @return array
     */
    private function getRegularUserUpdateData(array $data): array
    {
        $userUpdateData = [];

        if (isset($data['first_name'])) {
            $userUpdateData['first_name'] = $data['first_name'];
        }

        if (isset($data['last_name'])) {
            $userUpdateData['last_name'] = $data['last_name'];
        }

        if (isset($data['skills_desc'])) {
            $userUpdateData['skills_desc'] = $data['skills_desc'];
        }

        if (isset($data['experience'])) {
            $userUpdateData['experience'] = $data['experience'];
        }
        return $userUpdateData;
    }
}
