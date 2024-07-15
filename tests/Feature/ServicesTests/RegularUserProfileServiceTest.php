<?php

namespace Tests\Feature\ServicesTests;

use App\Enums\EditInfoType;
use App\Enums\Period;
use App\Models\RegularUser;
use App\Services\RegularUserProfileService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;
use Tests\Feature\TestsHelpers\UserProfileHelper;

class RegularUserProfileServiceTest extends TestCase
{
    use RefreshDatabase;
    use UserProfileHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthService();
        $this->profileService = new RegularUserProfileService();
        $this->seed(DatabaseSeeder::class);
    }

    public function testGetRegularUserProfile()
    {
        $response = $this->profileService->getRegularUserProfile($this->getRegularTestUser());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('profile', $response->getData(true));

    }

    public function testUpdateUserInformation()
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
                        "date_end" => Period::PRESENT,
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
        $response = $this->profileService->updateUserInformation($user, $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('User information was updated successfully', $response->getData(true)['message']);

        $regularUser = RegularUser::first();
        $this->assertEquals('Johnny', $regularUser->first_name);
        $this->assertEquals('Johnson', $regularUser->last_name);
    }

    public function testUpdateUserInformationUnsetUpdateType()
    {
        $user = $this->getRegularTestUser();
        $data = [];
        $request = new Request($data);

        $response = $this->profileService->updateUserInformation($user, $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Unset update type', $response->getData(true)['message']);
    }

    public function testDeleteRegularUserProfile()
    {
        $user = $this->getRegularTestUser();
        $response = $this->profileService->deleteRegularUserProfile($user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Regular user profile was deleted', $response->getData(true)['message']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('regular_users', ['id' => $user->user_id]);
    }

    public function testDeleteRegularUserProfileNotFound()
    {
        $response = $this->profileService->deleteRegularUserProfile('non_existing_id');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('User not found', $response->getData(true)['message']);
    }
}
