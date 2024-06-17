<?php

namespace App\Traits;

use App\Models\RegularUser;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserSkill;
use App\Models\WorkExperience;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait RegularUserProfileTrait
{
    /**
     * @param $regularUserRecord
     * @return array
     */
    public function getRegularUserSkills($regularUserRecord): array
    {
        $skills = [];
        foreach ((UserSkill::where('user_id', $regularUserRecord->id)->get()) as $userSkill) {
            $skills[] = [
                'id' => Skill::where('id', $userSkill->skill_id),
                'name' => $userSkill->name
            ];
        }
        return $skills;
    }

    /**
     * @param $regularUserRecord
     * @return array
     */
    public function getRegularUserWorkExperience($regularUserRecord): array
    {
        $workExperience = [];
        foreach ((WorkExperience::where('user_id', $regularUserRecord->id)->get()) as $workExperienceRecord) {
            $workExperience[] = [
                'position' => $workExperienceRecord->position,
                'description' => $workExperienceRecord->description,
                'dateStart' => $workExperienceRecord->date_start,
                'dateEnd' => $workExperienceRecord->date_end
            ];
        }
        return $workExperience;
    }

    /**
     * @param $regularUserRecord
     * @return array
     */
    public function getRegularUserEducation($regularUserRecord): array
    {
        $education = [];
        foreach ((UserEducation::where('user_id', $regularUserRecord->id)->get()) as $educationRecord) {
            $education[] = [
                'institution' => $educationRecord->institution,
                'degree' => $educationRecord->degree,
                'fieldOfStudy' => $educationRecord->field_of_study,
                'startDate' => $educationRecord->start_date,
                'endDate' => $educationRecord->end_date,
                'contactUrl' => $educationRecord->contact_url
            ];
        }
        return $education;
    }

    private function getRegularUserProfile($user): array
    {
        $regularUserRecord = RegularUser::where('id', $user->user_id)->first();

        return [
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
        ];
    }

    private function updateUserInformation($user, Request $request): JsonResponse
    {
        if ($request->has('updateType')) {
            try {
                return response()->json([
                    'message' => 'User information was updated successfully',
                    'updatedInformation' => $this->updateByUpdateType($request->input('updateType'), $user, User::where('user_id', $user->id)->first(), [])
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
    public function updateByUpdateType($updateType, $user, $baseUser, array $updatedResults): array
    {
        if (isset($updateType['personalInformation'])) {
            $this->updateUserProfile($updateType['personalInformation'], $user);
            $updatedResults[] = [
                'personalInformation' => [
                    'id' => $baseUser->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'skills_desc' => $user->skills_desc,
                    'experience' => $user->experience,
                    'avatar_url' => $baseUser->avatar_url,
                    'email' => $baseUser->email,
                ]
            ];
        }

        if (isset($updateType['education'])) {
            $updatedResults[] = ['education' => $this->updateUserEducation($updateType['education'], $user)];
        }

        if (isset($updateType['workExperience'])) {
            $updatedResults[] = ['workExperience' => $this->updateWorkExperience($updateType['workExperience'], $user)];
        }

        if (isset($updateType['skills'])) {
            $this->updateUserSkills($updateType['skills'], $user);
        }

        return $updatedResults;
    }

    private function updateUserProfile(array $personalInformation, $user)
    {
        $data = $this->getValidatedData($personalInformation, [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'avatar_url' => 'sometimes|url',
            'skills_desc' => 'sometimes|string',
            'experience' => 'sometimes|string',
            'email' => 'sometimes|string',
        ]);
        $regularUserRecord = RegularUser::find($user->id);
        $baseUser = User::where('user_id', $user->id);

        if ($regularUserRecord && $baseUser) {
            $regularUserRecord->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'skills_desc' => $data['skills_desc'],
                'experience' => $data['experience']
            ]);
            $baseUser->update([
                'email' => $data['email'],
                'experience' => $data['experience']
            ]);
        } else {
            throw new Exception('Error while updating user profile');
        }
    }

    private function updateUserEducation($education, $user)
    {
        $result = [];
        foreach ($education as $e) {
            $data = $this->getValidatedData($e, [
                'institution' => 'sometimes|string|max:255',
                'degree' => 'sometimes|string|max:255',
                'field_of_study' => 'sometimes|string|max:255',
                'start_date' => 'sometimes|timestamp',
                'end_date' => 'sometimes|timestamp',
                'contact_url' => 'sometimes|url',
            ]);

            if (isset($e['id'])) {
                $data['id'] = $e['id'];
                $educationRecord = UserEducation::find($data['id']);
            } else {
                $educationRecord = null;
            }

            if ($educationRecord) {
                $educationRecord->update($data);
            } else {
                $data['user_id'] = $user->user_id;
                $id = UserEducation::insertGetId($data);
                $educationRecord = UserEducation::find($id);
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

    private function updateWorkExperience($workExperience, $user)
    {
        $result = [];
        foreach ($workExperience as $e) {
            $data = $this->getValidatedData($workExperience, [
                'position' => 'sometimes|string|max:255',
                'company' => 'sometimes|string|max:255',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date',
                'description' => 'sometimes|string|max:255',
            ]);

            if (isset($workExperience['id'])) {
                $data['id'] = $workExperience['id'];
                $workExperienceRecord = WorkExperience::find($data['id']);
            } else {
                $workExperienceRecord = null;
            }

            if ($workExperienceRecord) {
                $workExperienceRecord->update($data);
            } else {
                $data['user_id'] = $user->id;
                $id = WorkExperience::insertGetId($data);
                $workExperienceRecord = WorkExperience::find($id);
            }

            $result[] = [
                'id' => $workExperienceRecord->id,
                'position' => $workExperienceRecord->position,
                'description' => $workExperienceRecord->description,
                'start_date' => $workExperienceRecord->start_date,
                'end_date' => $workExperienceRecord->end_date
            ];
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    private function updateUserSkills($skills, $user)
    {
        foreach ($skills as $skill) {
            if (isset($skill['id'])) {
                $skillId = $skill['id'];

                if (isset($skill['add'])) {
                    $this->addSkill($skillId, $user);
                } elseif (isset($skill['remove'])) {
                    $this->removeSkill($skillId, $user);
                } else {
                    throw new Exception('Update type does not exist');
                }
            } else {
                throw new Exception('Skill id was not set');
            }
        }
    }

    private function getValidatedData(array $data, array $rules): array
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    private function addSkill($skillId, $user)
    {
        UserSkill::insert(['user_id' => $user->id, 'skill_id' => $skillId]);
    }

    private function removeSkill($skillId, $user)
    {
        $record = UserSkill::where('skill_id', $skillId)->where('user_id', $user->id)->first();
        if ($record) {
            $record->delete();
        }
    }
}
