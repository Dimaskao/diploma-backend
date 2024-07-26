<?php

namespace Tests\TestsHelpers\Profile;

use App\Enums\Edit;
use App\Enums\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestsEnums\Method;
use Tests\TestsEnums\Status;
use Tests\TestsHelpers\Auth\AuthHelper;

trait ProfileServiceTestsHelper
{
    use RefreshDatabase, AuthHelper, UsersHelper;

    /** SpecificProfileService entity */
    protected $profileRegistry = null;
    protected Request $request;
    protected User $user;

    protected function setUpProfileRegistry($registry, $role)
    {
        $this->setUpAuthService();
        $this->profileRegistry = $registry;
        $this->role = $role;
        $this->setUpRequest();
        $this->user = $this->getTestUser();
    }

    public function testGetProfileSuccess()
    {
        $response = $this->profileRegistry->getProfile($this->user);
        $this->expectedResult(Method::GET, Status::SUCCESS, $response);
    }
//
//    public function testUpdateProfileSuccess()
//    {
//        $response = $this->profileRegistry->updateProfile($this->getTestUser(), $this->request);
//        $this->expectedResult(Method::UPDATE, Status::SUCCESS, $response);
//    }
//
//    public function testUpdateProfileFailed()
//    {
//        $response = $this->profileRegistry->updateProfile($this->getTestUser(), new Request([]));
//        $this->expectedResult(Method::UPDATE, Status::FAILED, $response);
//    }
//
//    public function testDeleteProfileSuccess()
//    {
//        $response = $this->profileRegistry->deleteProfile($this->getTestUser()->id);
//        $this->expectedResult(Method::DELETE, Status::SUCCESS, $response);
//    }
//
//    public function testDeleteProfileFailed()
//    {
//        $response = $this->profileRegistry->deleteProfile($this->getTestUser()->id);
//        $this->expectedResult(Method::DELETE, Status::FAILED, $response);
//    }

    private function getCompanyUpdateRequestData()
    {
        return [
            'updateType' => [
                'personalInformation' => [
                    'first_name' => 'Johnny',
                    'last_name' => 'Johnson',
                    "skills_desc" => "Senior Developer",
                    "experience" => "7 years",
                ],
                'education' => [
                    [
                        'id' => 1,
                        'institution' => 'University X"',
                        'degree' => 'Bachelor"s in Computer Science',
                        'field_of_study' => 'Computer Science"',
                        'contact_url' => 'http://universityx.edu/'
                    ]
                ],
                'workExperience' => [
                    [
                        "position" => "Senior Developer",
                        "company" => "Tech Company",
                        "date_start" => "2022-01-01",
                        "date_end" => Period::PRESENT,
                        "description" => "Leading development teams"
                    ]
                ],
                "skills" => [
                    [
                        "id" => "1",
                        "editInfo" => Edit::ADD,
                    ],
                    [
                        "id" => "1",
                        "editInfo" => Edit::REMOVE
                    ]
                ]
            ]
        ];
    }

    private function getAdminUpdateRequestData()
    {
        return [
            'updateType' => [
                'personalInformation' => [
                    'first_name' => 'Johnny',
                    'last_name' => 'Johnson',
                    "skills_desc" => "Senior Developer",
                    "experience" => "7 years",
                ],
                'education' => [
                    [
                        'id' => 1,
                        'institution' => 'University X"',
                        'degree' => 'Bachelor"s in Computer Science',
                        'field_of_study' => 'Computer Science"',
                        'contact_url' => 'http://universityx.edu/'
                    ]
                ],
                'workExperience' => [
                    [
                        "position" => "Senior Developer",
                        "company" => "Tech Company",
                        "date_start" => "2022-01-01",
                        "date_end" => Period::PRESENT,
                        "description" => "Leading development teams"
                    ]
                ],
                "skills" => [
                    [
                        "id" => "1",
                        "editInfo" => Edit::ADD,
                    ],
                    [
                        "id" => "1",
                        "editInfo" => Edit::REMOVE
                    ]
                ]
            ]
        ];
    }
}
