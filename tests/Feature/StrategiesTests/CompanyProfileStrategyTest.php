<?php

namespace Tests\Feature\StrategiesTests;

use App\Models\Company;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\Feature\TestsHelpers\UserProfileHelper;
use Tests\TestCase;

class CompanyProfileStrategyTest extends TestCase
{
    use RefreshDatabase;
    use UserProfileHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthService();
        $this->setUpCompanyProfileStrategy();
        $this->seed(DatabaseSeeder::class);
    }

    public function testShow()
    {
        $user = $this->getCompanyTestUser();
        $response = $this->strategy->show($user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('profile', $response->getData(true));
    }

    public function testUpdate()
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
        $response = $this->strategy->update($request, $user->id);

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

    public function testUpdateUnsetUpdateType()
    {
        $user = $this->getCompanyTestUser();
        $data = [];
        $request = new Request($data);

        $response = $this->strategy->update($request, $user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Unset update type', $response->getData(true)['message']);
    }

    public function testDelete()
    {
        $user = $this->getCompanyTestUser();
        $response = $this->strategy->deleteProfile($user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Company profile was deleted', $response->getData(true)['message']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('companies', ['id' => $user->company_id]);
    }

    public function testDeleteNotFound()
    {
        $response = $this->strategy->deleteProfile('non_existing_id');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('User not found', $response->getData(true)['message']);
    }
}
