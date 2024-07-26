<?php

namespace Tests\TestsHelpers\Profile\Specific;

use App\Enums\Edit;
use App\Enums\Period;
use App\Models\RegularUser;
use Illuminate\Http\JsonResponse;
use Tests\TestsEnums\Status;

trait RegularUserServiceHelper
{
    private function expectedRegularUserGetResult($response)
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('profile', $response->getData(true));
    }

    private function expectedRegularUserUpdateResult($status, $response)
    {
        match ($status) {
            Status::SUCCESS => $this->expectedRegularUserSuccessUpdateResult($response),
            Status::FAILED => $this->expectedRegularUserFailedUpdateResult($response)
        };
    }

    private function expectedRegularUserSuccessUpdateResult($response)
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('User information was updated successfully', $response->getData(true)['message']);

        $regularUser = RegularUser::first();
        $this->assertEquals('Johnny', $regularUser->first_name);
        $this->assertEquals('Johnson', $regularUser->last_name);
    }

    private function expectedRegularUserFailedUpdateResult($response)
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('User information was updated successfully', $response->getData(true)['message']);

        $regularUser = RegularUser::first();
        $this->assertEquals('Johnny', $regularUser->first_name);
        $this->assertEquals('Johnson', $regularUser->last_name);
    }

    private function expectedRegularUserDeleteResult($status, $response)
    {
        match ($status) {
            Status::SUCCESS => $this->expectedRegularUserSuccessDeleteResult($response),
            Status::FAILED => $this->expectedRegularUserFailedDeleteResult($response)
        };
    }

    private function expectedRegularUserSuccessDeleteResult($response)
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Regular user profile was deleted', $response->getData(true)['message']);

        $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
        $this->assertDatabaseMissing('regular_users', ['id' => $this->user->user_id]);
    }

    private function expectedRegularUserFailedDeleteResult($response)
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('User not found', $response->getData(true)['message']);
    }

    private function getRegularUserUpdateRequestData()
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
