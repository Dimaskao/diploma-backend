<?php

namespace TestsHelpers\Profile\Users\Specific\AdminHelpers\Handlers\Update;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use TestsEnums\Status;

trait UpdateHelper
{
    private function expectedAdminUpdateResult($status, $response): void
    {
        match ($status) {
            Status::SUCCESS => $this->expectedAdminSuccessUpdateResult($response),
            Status::FAILED => $this->expectedAdminFailedUpdateResult($response)
        };
    }

    private function expectedAdminSuccessUpdateResult($response): void
    {
        Log::debug('response: ' . var_export($response, 1));
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = $response->getData(true);

        $this->assertEquals('Success', $responseData['message']);

        $this->hasUpdateKeys($responseData);
        $this->hasExpectedValues($responseData);
    }

    private function expectedAdminFailedUpdateResult($response): void
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('Attempt to read property "userProfile" on array', $response->getData(true)['message']);
    }

    private function hasUpdateKeys($responseData): void
    {
        $this->hasSelfExpectedKeys($responseData);
        $this->hasAnotherAdminPermissionsExpectedKeys($responseData);
        $this->hasBanUnbanKeys($responseData);
        $this->hasBanUnbanValues($responseData);
    }

    private function hasExpectedValues($responseData): void
    {
        $this->hasSelfExpectedValues($responseData);
        $this->hasAnotherAdminPermissionExpectedValues($responseData);
    }

    private function hasSelfExpectedKeys($responseData): void
    {
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('updated_information', $responseData['data']);
        $this->assertArrayHasKey('self', $responseData['data']['updated_information']);
        $this->assertArrayHasKey('personal_information', $responseData['data']['updated_information']['self']);
        $this->assertArrayHasKey('name', $responseData['data']['updated_information']['self']['personal_information']);
        $this->assertArrayHasKey('avatar_url', $responseData['data']['updated_information']['self']['personal_information']);
    }

    private function hasSelfExpectedValues($responseData): void
    {
        $this->assertEquals('Moderator test admin', $responseData['data']['updated_information']['self']['personal_information']['name']);
        $this->assertEquals('https://avatar-url.test.com', $responseData['data']['updated_information']['self']['personal_information']['avatar_url']);
    }

    private function hasAnotherAdminPermissionsExpectedKeys($responseData): void
    {
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('updated_information', $responseData['data']);
        $this->assertArrayHasKey('another_admin_permissions', $responseData['data']['updated_information']);
        $this->assertArrayHasKey('message', $responseData['data']['updated_information']['another_admin_permissions']);
    }

    private function hasAnotherAdminPermissionExpectedValues($responseData): void
    {
        $this->assertEquals('success', $responseData['data']['updated_information']['another_admin_permissions']['message']);
    }

    private function hasBanUnbanKeys($responseData): void
    {
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('updated_information', $responseData['data']);
        $this->assertArrayHasKey('ban_unban', $responseData['data']['updated_information']);
        $this->assertArrayHasKey('ban', $responseData['data']['updated_information']['ban_unban']);
        $this->assertArrayHasKey('unban', $responseData['data']['updated_information']['ban_unban']);
        $this->assertArrayHasKey('message', $responseData['data']['updated_information']['ban_unban']['ban'][0]);
        $this->assertArrayHasKey('message', $responseData['data']['updated_information']['ban_unban']['unban'][0]);
    }

    private function hasBanUnbanValues($responseData): void
    {
        $this->assertEquals('success', $responseData['data']['updated_information']['ban_unban']['ban'][0]['message']);
        $this->assertEquals('success', $responseData['data']['updated_information']['ban_unban']['unban'][0]['message']);
    }

    private function getAdminUpdateRequestData(): array
    {
        $initData = $this->credentials;
        $this->refreshCredentials();
        $base = $this->getTestUser();
        $post = Post::create([
            'title' => 'Test post',
            'content' => 'This is the test post',
            'user_id' => $base->id
        ]);

        return [
            'update_type' => [
                'self' => [
                    'personal_information' => [
                        'name' => 'Moderator test admin',
                        'password' => 'test admin password',
                        'avatar_url' => 'https://avatar-url.test.com'
                    ]
                ],
                'another_admin_permissions' => [
                    'update_admin_id' => $base->id,
                    'permissions' => [
                        'edit' => true,
                        'read' => true,
                        'write' => false,
                        'full' => false
                    ]
                ],
                'ban_unban' => [
                    'edit_info' => [
                        'ban' => [
                            'ban_users' => [
                                [
                                    'user_id_to_ban' => $base->id,
                                    'reason' => 'Bot'
                                ]
                            ],
                            'ban_posts' => [
                                [
                                    'post_id_to_ban' => $post->id,
                                    'reason' => 'Harm content'
                                ]
                            ],
                        ],
                        'unban' => [
                            'unban_users' => [
                                [
                                    'user_id_to_unban' => $base->id
                                ]
                            ],
                            'unban_posts' => [
                                [
                                    'post_id_to_unban' => $post->id
                                ]
                            ]
                        ]
                    ]
                ],
                "skills" => [
                    [
                        "edit_info" => 'add',
                        "name" => 'team lead'
                    ],
                    [
                        "edit_info" => 'remove',
                        'name' => 'team lead'
                    ]
                ],
            ],
            'base_user_test_only' => $base,
            'init_credentials_data' => $initData
        ];
    }
}
