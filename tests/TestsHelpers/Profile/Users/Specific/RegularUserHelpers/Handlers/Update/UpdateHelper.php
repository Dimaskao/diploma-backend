<?php

namespace TestsHelpers\Profile\Users\Specific\RegularUserHelpers\Handlers\Update;

use App\Enums\Edit;
use App\Enums\ResponseKey;
use App\Enums\UpdateType;
use App\Models\RegularUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use TestsEnums\Status;
use TestsHelpers\Image\Helpers\ImageUploadSetUpHelper;

trait UpdateHelper
{
    use ImageUploadSetUpHelper;

    public function extractedSkills($skills): void
    {
        $this->assertArrayHasKey('id', $skills[0]);
        $this->assertArrayHasKey('edit_info', $skills[0]);
        $this->assertEquals(Edit::ADD, $skills[0]['edit_info']);
    }

    public function extractedWorkExperience($workExperience): void
    {
        $this->assertArrayHasKey('position', $workExperience);
        $this->assertArrayHasKey('company_name', $workExperience);
        $this->assertArrayHasKey('date_start', $workExperience);
        $this->assertArrayHasKey('date_end', $workExperience);
        $this->assertArrayHasKey('description', $workExperience);
        $this->assertEquals('Senior Developer', $workExperience['position']);
        $this->assertEquals('Tech Company', $workExperience['company_name']);
        $this->assertEquals('2022-01-01 00:00:00', $workExperience['date_start']);
        $this->assertEquals(null, $workExperience['date_end']);
        $this->assertEquals('Leading development teams', $workExperience['description']);
    }

    public function extractedKeys($response): void
    {
        $response = $this->convertToArray($response);

        // TODO fix bug here with correct keys
//        Log::debug("******************** extractedKeys response converted: " . var_export($response, 1));

        $this->extractedUpdate($response);
        $this->extractedPersonalInformation($response['data'][ResponseKey::UPDATED_INFORMATION][UpdateType::PERSONAL_INFORMATION]);
        $this->extractedEducation($response['data'][ResponseKey::UPDATED_INFORMATION][UpdateType::EDUCATION]);
        $this->extractedWorkExperience($response['data'][ResponseKey::UPDATED_INFORMATION][UpdateType::WORK_EXPERIENCE][0]);
        $this->extractedSkills($response['data'][ResponseKey::UPDATED_INFORMATION][UpdateType::SKILLS]);
    }

    private function convertToArray($data)
    {
        if (is_object($data)) {
            $data = (array)$data;
        }

        if (is_array($data)) {
            foreach ($data as &$value) {
                $value = $this->convertToArray($value);
            }
        }

        return $data;
    }

    public function extractedEducation($education): void
    {
        $this->assertCount(1, $education);
        $this->assertArrayHasKey('id', $education[0]);
        $this->assertArrayHasKey('institution', $education[0]);
        $this->assertArrayHasKey('degree', $education[0]);
        $this->assertArrayHasKey('field_of_study', $education[0]);
        $this->assertArrayHasKey('contact_url', $education[0]);
        $this->assertEquals('University X', $education[0]['institution']);
        $this->assertEquals('Bachelor"s in Computer Science', $education[0]['degree']);
        $this->assertEquals('Computer Science', $education[0]['field_of_study']);
        $this->assertEquals('https://universityx.edu/', $education[0]['contact_url']);
    }

    public function extractedPersonalInformation($personalInformation): void
    {
        Log::debug('personal info result: ' . var_export($personalInformation, 1));

        $this->assertArrayHasKey('first_name', $personalInformation);
        $this->assertArrayHasKey('last_name', $personalInformation);
        $this->assertArrayHasKey('skills_desc', $personalInformation);
        $this->assertArrayHasKey('experience', $personalInformation);
        $this->assertEquals('Johnny', $personalInformation['first_name']);
        $this->assertEquals('Johnson', $personalInformation['last_name']);
        $this->assertEquals('Senior Developer', $personalInformation['skills_desc']);
        $this->assertEquals('7 years', $personalInformation['experience']);
    }

    public function extractedUpdate($response): void
    {
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey(ResponseKey::UPDATED_INFORMATION, $response['data']);
        $this->assertArrayHasKey(UpdateType::PERSONAL_INFORMATION, $response['data'][ResponseKey::UPDATED_INFORMATION]);
        $this->assertArrayHasKey(UpdateType::EDUCATION, $response['data'][ResponseKey::UPDATED_INFORMATION]);
        $this->assertArrayHasKey(ResponseKey::WORK_EXPERIENCE, $response['data'][ResponseKey::UPDATED_INFORMATION]);
        $this->assertArrayHasKey(ResponseKey::SKILLS, $response['data'][ResponseKey::UPDATED_INFORMATION]);
    }

    private function expectedRegularUserUpdateResult($status, $response): void
    {
        match ($status) {
            Status::SUCCESS => $this->expectedRegularUserSuccessUpdateResult($response),
            Status::FAILED => $this->expectedRegularUserFailedUpdateResult($response)
        };
    }

    private function expectedRegularUserSuccessUpdateResult($response): void
    {
        Log::debug('********** expectedRegularUserSuccessUpdateResult response: ' . var_export($response, 1));

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Success', $response->getData(true)['message']);

//        $this->extractedKeys($response);
    }

    private function expectedRegularUserFailedUpdateResult($response): void
    {
//        Log::debug('******** response: ' . var_export($response, 1)) ;
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('Attempt to read property "userProfile" on array', $response->getData(true)['message']);
    }

    public function getRegularUserUpdateRequestData(): array
    {
        return [
            'update_type' => [
                'personal_information' => [
                    'first_name' => 'Johnny',
                    'last_name' => 'Johnson',
                    "skills_desc" => "Senior Developer",
                    "experience" => "7 years",
                    'avatar' => $this->uploadedFile(),
                    'password' => 'test1234'
                ],
                'education' => [
                    [
                        'id' => 1,
                        'institution' => 'University X',
                        'degree' => 'Bachelor"s in Computer Science',
                        'field_of_study' => 'Computer Science',
                        'contact_url' => 'https://universityx.edu/'
                    ]
                ],
                'work_experience' => [
                    [
                        "position" => "Senior Developer",
                        "company_name" => "Tech Company",
                        "date_start" => "2022-01-01",
                        "date_end" => 'present',
                        "description" => "Leading development teams"
                    ]
                ],
                "skills" => [
                    [
                        "id" => "1",
                        "edit_info" => 'add',
                    ],
                    [
                        "id" => "1",
                        "edit_info" => 'remove'
                    ]
                ]
            ]
        ];
    }
}
