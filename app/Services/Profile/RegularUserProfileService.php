<?php

namespace App\Services\Profile;

use App\Enums\EditInfoType;
use App\Enums\Period;
use App\Enums\ResponseKeys;
use App\Enums\UpdateType;
use App\Interfaces\SpecificProfileService;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use App\Models\UserContact;
use App\Models\UserEducation;
use App\Models\UserSkill;
use App\Models\WorkExperience;
use App\Services\ValidationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegularUserProfileService implements SpecificProfileService
{
    protected ValidationService $validator;

    public function __construct()
    {
        $this->validator = new ValidationService();
    }

    public function getProfile($user): JsonResponse
    {
        $regularUserRecord = $user->regularUser;
        return response()->json([
            ResponseKeys::PROFILE => [
                ResponseKeys::USER => [
                    'id' => $user->id,
                    'first_name' => $regularUserRecord->first_name,
                    'last_name' => $regularUserRecord->last_name,
                    'skills_desc' => $regularUserRecord->skills_desc,
                    'experience' => $regularUserRecord->experience,
                ],
                ResponseKeys::EDUCATION => $this->getRegularUserEducation($regularUserRecord),
                ResponseKeys::WORK_EXPERIENCE => $this->getRegularUserWorkExperience($regularUserRecord),
                ResponseKeys::SKILLS => $this->getRegularUserSkills($regularUserRecord)
            ]
        ], 200);
    }

    public function updateProfile($user, Request $request): JsonResponse
    {
        if ($request->has(UpdateType::UPDATE_TYPE)) {
            try {
                $regularUser = $user->profileable;
                return response()->json([
                    ResponseKeys::MESSAGE => 'User information was updated successfully',
                    ResponseKeys::UPDATED_INFORMATION => $this->updateRegularUserByUpdateType($request->input(UpdateType::UPDATE_TYPE), $regularUser, $user, [])
                ], 200);
            } catch (Exception $e) {
                return response()->json([ResponseKeys::MESSAGE => $e->getMessage()], 500);
            }
        }

        return response()->json([ResponseKeys::MESSAGE => 'Unset update type'], 400);
    }

    public function deleteProfile($id): JsonResponse
    {
        $baseUser = User::find($id);
        if ($baseUser) {
            $regularUser = $baseUser->profileable;

            UserEducation::where('user_id', $regularUser->id)->delete();
            UserSkill::where('user_id', $regularUser->id)->delete();
            WorkExperience::where('user_id', $regularUser->id)->delete();
            UserContact::where('subscriber_id', $regularUser->id)->delete();

            $posts = Post::where('user_id', $baseUser->id)->get();
            foreach ($posts as $post) {
                PostImage::where('post_id', $post->id)->delete();
                $post->delete();
            }

            $regularUser->delete();
            $baseUser->delete();

            return response()->json([ResponseKeys::MESSAGE => "Regular user profile was deleted"], 200);
        } else {
            return response()->json([ResponseKeys::MESSAGE => "User not found"], 404);
        }
    }

    /**
     * @param array $data
     * @param array $dataToUpdate
     * @return array
     */
    private function getUserEducationDataToProceed(array $data, array $dataToUpdate): array
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
    private function getWorkExperienceDataToProceed(array $data, array $newData): array
    {
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
     * @return mixed
     */
    private function insertWorkExperienceRecord($user, array $data): mixed
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
    private function updateWorkExperienceRecord($workExperienceRecord, array $data)
    {
        $dataToUpdate = [];

        if (isset($data['position'])) {
            $dataToUpdate['position'] = $data['position'];
        }

        $dataToUpdate = $this->getWorkExperienceDataToProceed($data, $dataToUpdate);

        $workExperienceRecord->update($dataToUpdate);
        return $workExperienceRecord;
    }

    /**
     * @param array $data
     * @param $educationRecord
     * @return array
     */
    private function updateEducationRecord(array $data, $educationRecord)
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
    private function insertEducationRecord(array $data, $user)
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
                'date_start' => $workExperienceRecord->date_start,
                'date_end' => $workExperienceRecord->date_end
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
                'field_of_study' => $educationRecord->field_of_study,
                'start_date' => $educationRecord->start_date,
                'end_date' => $educationRecord->end_date,
                'contact_url' => $educationRecord->contact_url
            ];
        })->toArray();
    }

    /**
     * @throws Exception
     */
    private function updateRegularUserByUpdateType($updateType, $user, $baseUser, array $updatedResults): array
    {
        if (isset($updateType[UpdateType::PERSONAL_INFORMATION])) {
            $this->updateRegularUserProfile($updateType[UpdateType::PERSONAL_INFORMATION], $user, $baseUser);
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

        if (isset($updateType[UpdateType::EDUCATION])) {
            $updatedResults[UpdateType::EDUCATION] = $this->updateUserEducation($updateType[UpdateType::EDUCATION], $user);
        }

        if (isset($updateType[UpdateType::WORK_EXPERIENCE])) {
            $updatedResults[UpdateType::WORK_EXPERIENCE] = $this->updateWorkExperience($updateType[UpdateType::WORK_EXPERIENCE], $user);
        }

        if (isset($updateType[UpdateType::SKILLS])) {
            $updatedResults[UpdateType::SKILLS] = $this->updateUserSkills($updateType[UpdateType::SKILLS], $user);
        }

        return $updatedResults;
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    private function updateRegularUserProfile(array $personalInformation, $user, $baseUser)
    {
        $data = $this->validator->validate($personalInformation, [
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
            $baseUserUpdateData = $this->getBaseUserUpdateData($data);

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

            $data = $this->validator->validate($e, [
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
                $experience['date_start'] = $this->convertToDateTimeString($experience['date_start']);
            }

            if (isset($experience['date_end'])) {
                if ($experience['date_end'] == Period::PRESENT) {
                    unset($experience['date_end']);
                } else {
                    $experience['date_end'] = $this->convertToDateTimeString($experience['date_end']);
                }
            }

            $data = $this->validator->validate($experience, [
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
            if (isset($skill['id']) && isset($skill[EditInfoType::EDIT_INFO])) {
                $skillId = $skill['id'];
                $editInfo = $skill[EditInfoType::EDIT_INFO];

                $result[] = match ($editInfo) {
                    EditInfoType::ADD => $this->addSkill($skillId, $user),
                    EditInfoType::REMOVE => $this->removeSkill($skillId, $user),
                    default => throw new Exception('Update type does not exist')
                };

            } else {
                throw new Exception('Skill id was not set');
            }
        }
        return $result;
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
            EditInfoType::EDIT_INFO => EditInfoType::ADD,
            ResponseKeys::RESULT => 'success'
        ];
    }

    private function removeSkill($skillId, $user)
    {
        $record = UserSkill::where('skill_id', $skillId)->where('user_id', $user->id)->first();
        if ($record) {
            $record->delete();
            return [
                EditInfoType::EDIT_INFO => EditInfoType::REMOVE,
                ResponseKeys::RESULT => 'success'
            ];
        }
        return [
            'id' => $record->id,
            EditInfoType::EDIT_INFO => EditInfoType::REMOVE,
            ResponseKeys::RESULT => 'error'
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    private function getBaseUserUpdateData(array $data): array
    {
        $baseUserUpdateData = [];

        if (isset($data['email'])) {
            $baseUserUpdateData['email'] = $data['email'];
        }

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

    /**
     * Convert a date string to a datetime string
     *
     * @param string $date
     * @return string|null
     */
    private function convertToDateTimeString($date): ?string
    {
        try {
            $timestamp = strtotime($date);
            return date('Y-m-d H:i:s', $timestamp);
        } catch (Exception $e) {
            return null;
        }
    }
}
