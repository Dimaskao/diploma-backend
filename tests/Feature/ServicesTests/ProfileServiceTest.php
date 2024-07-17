<?php

namespace Tests\Feature\ServicesTests;

use App\Enums\EditInfoType;
use App\Factories\ProfileStrategyFactory;
use App\Interfaces\Factory;
use App\Models\Company;
use App\Models\RegularUser;
use App\Models\User;
use App\Services\Profile\CompanyProfileService;
use App\Services\Profile\UserProfileService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\Feature\TestsHelpers\UserProfileHelper;
use Tests\TestCase;

class ProfileServiceTest extends TestCase
{
    use RefreshDatabase;
    use UserProfileHelper;

    protected UserProfileService $service;
    protected Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthService();
        $this->profileService = new CompanyProfileService();
        $this->factory = new ProfileStrategyFactory();
        $this->service = new UserProfileService($this->factory);
        $this->seed(DatabaseSeeder::class);
    }

    public function testShowRegularUserSuccess()
    {
        $user = $this->getRegularTestUser();
        $response = $this->service->getProfile($user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('profile', $response->getData(true));
    }

    public function testShowCompanySuccess()
    {
        $user = $this->getCompanyTestUser();
        $response = $this->service->getProfile($user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('profile', $response->getData(true));
    }

    public function testShowFails()
    {
        $response = $this->service->getProfile('test_id');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testUpdateRegularUserSuccess()
    {
        $user = $this->getRegularTestUser();
        $data = [
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
                        "description" => "Leading development teams"
                    ]
                ],
                "skills" => [
                    [
                        "id" => "1",
                        "editInfo" => EditInfoType::ADD,
                    ],
                    [
                        "id" => "1",
                        "editInfo" => EditInfoType::REMOVE
                    ]
                ]
            ]
        ];

        $request = new Request($data);
        $response = $this->service->updateProfile($request, $user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('User information was updated successfully', $response->getData(true)['message']);

        $regularUser = RegularUser::first();
        $this->assertEquals('Johnny', $regularUser->first_name);
        $this->assertEquals('Johnson', $regularUser->last_name);
    }

    public function testUpdateRegularUserUnsetUpdateType()
    {
        $user = $this->getRegularTestUser();
        $data = [];
        $request = new Request($data);

        $response = $this->service->updateProfile($request, $user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Unset update type', $response->getData(true)['message']);
    }

    public function testUpdateCompanySuccess()
    {
        $user = $this->getCompanyTestUser();
        $data = [
            'updateType' => [
                'personalInformation' => [
                    'description' => 'Desc test',
                    'name' => 'Test company',
                    "contact_email" => "Test contact email",
                    "contact_phone" => "Test contact phone",
                    "contact_url" => "https://1.kibana.gameloft.org/",
                    "avatar_url" => "https://1.kibana.gameloft.org/",
                ]
            ]
        ];

        $request = new Request($data);
        $response = $this->service->updateProfile($request, $user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Company information was updated successfully', $response->getData(true)['message']);

        $company = Company::first();
        $user = User::first();
        $this->assertEquals('Desc test', $company->description);
        $this->assertEquals('Test company', $company->name);
        $this->assertEquals('Test contact email', $company->contact_email);
        $this->assertEquals('Test contact phone', $company->contact_phone);
        $this->assertEquals('https://1.kibana.gameloft.org/', $company->contact_url);
        $this->assertEquals('https://1.kibana.gameloft.org/', $user->avatar_url);
    }

    public function testUpdateCompanyUnsetUpdateType()
    {
        $user = $this->getCompanyTestUser();
        $data = [];
        $request = new Request($data);

        $response = $this->service->updateProfile($request, $user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Unset update type', $response->getData(true)['message']);
    }

    public function testDeleteUserSuccess()
    {
        $user = $this->getRegularTestUser();
        $response = $this->service->deleteProfile($user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Regular user profile was deleted', $response->getData(true)['message']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('regular_users', ['id' => $user->user_id]);
    }

    public function testDeleteCompanySuccess()
    {
        $user = $this->getCompanyTestUser();
        $response = $this->service->deleteProfile($user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Company profile was deleted', $response->getData(true)['message']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('companies', ['id' => $user->company_id]);
    }

    public function testDeleteNotFound()
    {
        $response = $this->service->deleteProfile('non_existing_id');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
    }
}
