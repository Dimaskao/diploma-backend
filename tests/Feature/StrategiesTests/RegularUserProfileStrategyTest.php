<?php

namespace Tests\Feature\StrategiesTests;

use App\Enums\Edit;
use App\Models\RegularUser;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\Feature\TestsHelpers\UserProfileHelper;
use Tests\TestCase;

class RegularUserProfileStrategyTest extends TestCase
{
    use RefreshDatabase;
    use UserProfileHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthService();
        $this->setUpRegularUserProfileStrategy();
        $this->seed(DatabaseSeeder::class);
    }

    public function testShow()
    {
        $user = $this->getRegularTestUser();
        $response = $this->strategy->show($user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('profile', $response->getData(true));
    }

    public function testUpdate()
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
                        "editInfo" => Edit::ADD,
                    ],
                    [
                        "id" => "1",
                        "editInfo" => Edit::REMOVE
                    ]
                ]
            ]
        ];

        $request = new Request($data);
        $response = $this->strategy->update($request, $user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('User information was updated successfully', $response->getData(true)['message']);

        $regularUser = RegularUser::first();
        $this->assertEquals('Johnny', $regularUser->first_name);
        $this->assertEquals('Johnson', $regularUser->last_name);
    }

    public function testUpdateUnsetUpdateType()
    {
        $user = $this->getRegularTestUser();
        $data = [];
        $request = new Request($data);

        $response = $this->strategy->update($request, $user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Unset update type', $response->getData(true)['message']);
    }

    public function testDelete()
    {
        $user = $this->getRegularTestUser();
        $response = $this->strategy->deleteProfile($user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Regular user profile was deleted', $response->getData(true)['message']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('regular_users', ['id' => $user->user_id]);
    }

    public function testDeleteNotFound()
    {
        $response = $this->strategy->deleteProfile('non_existing_id');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('User not found', $response->getData(true)['message']);
    }
}
