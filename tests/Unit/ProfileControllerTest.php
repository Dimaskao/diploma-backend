<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\RegularUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class ProfileControllerTest extends TestCase
{
//    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Increase memory limit
        ini_set('memory_limit', '-1');

        // Ensure roles are created for testing
        if (DB::table('roles')->where('name', 'user')->count() == 0) {
            Role::create(['name' => 'user']);
        }
        if (DB::table('roles')->where('name', 'company')->count() == 0) {
            Role::create(['name' => 'company']);
        }
    }

//    public function testShowRegularUserProfile(): void
//    {
//        $regularUser = RegularUser::factory()->create();
//        $userRole = Role::where('name', 'user')->first();
//        $user = User::factory()->create(['user_id' => $regularUser->id, 'role_id' => $userRole->id]);
////        Log::info('regular user object: ' . var_export($regularUser, 1));
////        Log::info('$userRole: ' . var_export($userRole, 1));
////        Log::info('$user: ' . var_export($user, 1));
//
//        $response = $this->actingAs($user, 'api')->getJson("/api/profile/{$user->id}");
////        Log::info('$response: ' . var_export($response, 1));
//
//        $response->assertStatus(200);
//        $response->assertJsonStructure([
//            'profile' => [
//                'user' => [
//                    'id',
//                    'firstName',
//                    'lastName',
//                    'skillsDesc',
//                    'experience',
//                ],
//                'education',
//                'workExperience',
//                'skills',
//            ]
//        ]);
//    }

//    public function testShowCompanyProfile(): void
//    {
//        $company = Company::factory()->create();
//        $userRole = Role::where('name', 'company')->first();
//        $user = User::factory()->create(['company_id' => $company->id, 'role_id' => $userRole->id]);
//        $response = $this->actingAs($user, 'api')->getJson("/api/profile/{$user->id}");
//
//        $response->assertStatus(200);
//        $response->assertJsonStructure([
//            'profile' => [
//                'id',
//                'name',
//                'description',
//                'contactEmail',
//                'contactPhone',
//                'contactUrl',
//                'posts',
//                'jobOffers',
//            ]
//        ]);
//    }

//    public function testUpdateUserInformation(): void
//    {
//        $regularUser = RegularUser::factory()->create();
//        $userRole = Role::where('name', 'user')->first();
//        $user = User::factory()->create(['user_id' => $regularUser->id, 'role_id' => $userRole->id]);
//
//        $startDate = now()->subYears(4)->timestamp;
//        $endDate = now()->subYears(2)->timestamp;
//
//        $requestData = [
//            'updateType' => [
////                'personalInformation' => [
////                    'first_name' => 'Updated First Name',
//////                    'last_name' => 'Updated Last Name',
//////                    'skills_desc' => 'Updated Skills Description',
////                    'experience' => 'Updated Experience',
//////                    'email' => 'test1234@test.com',
//////                    'password' => 'check1234'
////                ],
////                'education' => [
////                    [
////                        'id' => 'ffd9875a-4988-4210-94dc-710d74e9d5d6',
////                        'institution' => 'Updated University1234',
////                        'degree' => 'Updated Degree1234',
////                        'field_of_study' => 'Updated Field of Study1234',
//////                        'start_date' => $startDate,
//////                        'end_date' => $endDate,
////                        'contact_url' => 'https://updated-university.example.com',
////                    ],
////                ],
////                'workExperience' => [
////                    [
////                        'id' => 'ff7aae08-e1f7-460b-bb68-0c9c572650a5',
////                        'position' => 'Test Position123',
////                        'description' => 'Test Description123',
////                        'date_start' => $startDate,
////                        'date_end' => $endDate,
////                    ]
////                ],
//                'skills' => [
//                    [
//                        'id' => '2ab07475-2e7c-11ef-a23e-0242ac130002',
//                        'editInfo' => 'add'
//                    ],
//                    [
//                        'id' => '2e0112d7-2e7c-11ef-a23e-0242ac130002',
//                        'editInfo' => 'add'
//                    ],
//                    [
//                        'id' => '2ab07475-2e7c-11ef-a23e-0242ac130002',
//                        'editInfo' => 'remove'
//                    ],
//                ]
//            ]
//        ];
//
//        Log::info('$requestData: ' . var_export($requestData, 1));
////
////        Log::info('start_date: ' . var_export(date('Y-m-d H:i:s', $startDate), 1));
////        Log::info('$endDate: ' . var_export(date('Y-m-d H:i:s', $endDate), 1));
//
//        $response = $this->actingAs($user, 'api')->putJson("/api/profile/{$user->id}", $requestData);
//        Log::error('response: ' . var_export($response, 1));
//
//        $response->assertStatus(200);
//        $response->assertJson([
//            'message' => 'User information was updated successfully',
//            'updatedInformation' => [
////                'personalInformation' => [
////                    'first_name' => 'Updated First Name',
////                    'experience' => 'Updated Experience',
////                ],
////                'education' => [
////                    [
////                        'id' => 'ffd9875a-4988-4210-94dc-710d74e9d5d6',
////                        'institution' => 'Updated University1234',
////                        'degree' => 'Updated Degree1234',
////                        'field_of_study' => 'Updated Field of Study1234',
//////                        'start_date' => date('Y-m-d H:i:s', $startDate),
//////                        'end_date' => date('Y-m-d H:i:s', $endDate),
////                        'contact_url' => 'https://updated-university.example.com',
////                    ],
////                ],
////                'workExperience' => [
////                    [
////                        'id' => 'ff7aae08-e1f7-460b-bb68-0c9c572650a5',
////                        'position' => 'Test Position123',
////                        'description' => 'Test Description123',
////                        'date_start' => date('Y-m-d H:i:s', $startDate),
////                        'date_end' => date('Y-m-d H:i:s', $endDate),
////                    ]
////                ],
//                'skills' => [
//                    [
//                        'editInfo' => 'add',
//                        'result' => 'success'
//                    ],
//                    [
//                        'editInfo' => 'add',
//                        'result' => 'success'
//                    ],
//                    [
//                        'editInfo' => 'remove',
//                        'result' => 'success'
//                    ]
//                ]
//            ]
//        ]);
//    }

//    public function testUpdateCompanyInformation(): void
//    {
//        $company = Company::factory()->create();
//        $user = User::factory()->create(['company_id' => $company->id]);
//        $requestData = ['updateType' => ['someUpdateType' => 'someData']]; // Replace with actual update data for company
//
//        $response = $this->putJson(route('profiles.update', ['id' => $user->id]), $requestData);
//
//        $response->assertStatus(200);
//        $response->assertJson(['message' => 'Profile updated successfully']);
//    }

//    public function testSubscribeToUserContacts(): void
//    {
//        $subscriber = RegularUser::factory()->create();
//        $subscription = Company::factory()->create();
//
//        $userRole = Role::where('name', 'user')->first();
//        $companyRole = Role::where('name', 'company')->first();
//
//        $subscriberUser = User::factory()->create(['user_id' => $subscriber->id, 'role_id' => $userRole->id]);
//        $subscriptionUser = User::factory()->create(['company_id' => $subscription->id, 'role_id' => $companyRole->id]);
//
//        $requestData = [
//            'subscriberId' => $subscriber->id,
//            'subscriptionId' => $subscriptionUser->id
//        ];
//
//        Log::debug('$requestData: ' . var_export($requestData, 1));
//
//        $response = $this->actingAs($subscriberUser, 'api')->postJson("/api/profile/{$subscriberUser->id}/subscribe", $requestData);
//
//        Log::debug('$response: ' . var_export($response, 1));
//
//        $response->assertStatus(200);
//        $response->assertJson(['message' => 'Subscribed successfully']);
//    }

//    public function testUnsubscribeFromUserContacts(): void
//    {
//        $subscriber = RegularUser::factory()->create();
//        $subscription = Company::factory()->create();
//
//        $userRole = Role::where('name', 'user')->first();
//        $companyRole = Role::where('name', 'company')->first();
//
//        $subscriberUser = User::factory()->create(['user_id' => $subscriber->id, 'role_id' => $userRole->id]);
//        $subscriptionUser = User::factory()->create(['company_id' => $subscription->id, 'role_id' => $companyRole->id]);
//
//        $requestData = [
//            'subscriberId' => $subscriber->id,
//            'subscriptionId' => $subscriptionUser->id
//        ];
//
//        $response = $this->actingAs($subscriberUser, 'api')->postJson("/api/profile/{$subscriberUser->id}/subscribe", $requestData);
//
//        $response->assertStatus(200);
//        $response->assertJson(['message' => 'Subscribed successfully']);
//
////        $requestData = [
////          'recordId' => '855aefaf-0773-41ea-a088-1ee11ab7bba8'
////        ];
//
//        Log::debug('$requestData: ' . var_export($requestData, 1));;
//        $response = $this->actingAs($subscriberUser, 'api')->postJson("/api/profile/{$subscriberUser->id}/unsubscribe", $requestData);
//        Log::debug('$response: ' . var_export($response, 1));
//
//        $response->assertStatus(200);
//        $response->assertJson(['message' => 'Unsubscribed successfully']);
//    }

    public function testSearchRegularUsersAndCompanies(): void
    {
        $subscriber = RegularUser::factory()->create();
        $userRole = Role::where('name', 'user')->first();
        $subscriberUser = User::factory()->create(['user_id' => $subscriber->id, 'role_id' => $userRole->id]);

        $response = $this->actingAs($subscriberUser, 'api')->getJson("/api/search?first_name=Holly&for=all&last_name=Bartoletti&name=Toy-Walsh");

        Log::error('Response: ' . var_export($response, 1));

        $response->assertStatus(200);
    }
}
