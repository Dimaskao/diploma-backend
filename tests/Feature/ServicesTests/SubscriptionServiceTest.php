<?php

namespace Tests\Feature\ServicesTests;

use App\Models\RegularUser;
use App\Services\SubscriptionService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\Feature\TestsHelpers\UserProfileHelper;
use Tests\TestCase;

class SubscriptionServiceTest extends TestCase
{
    use RefreshDatabase, UserProfileHelper;

    protected SubscriptionService $subscriptionService;

    /**
     * @return array
     */
    public function subscribe(): array
    {
        $subscriber = $this->getRegularTestUser();
        $subscription = $this->getCompanyTestUser();

        $data = [
            'subscriberId' => $subscriber->id,
            'subscriptionId' => $subscription->id,
        ];

        $request = new Request($data);
        $response = $this->subscriptionService->subscribe($request);
        return array($subscriber, $subscription, $response);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthService();
        $this->subscriptionService = new SubscriptionService();
        $this->seed(DatabaseSeeder::class);
    }

    public function testSubscribeUserSuccess()
    {
        list($subscriber, $subscription, $response) = $this->subscribe();

        $this->validateJsonResponse($response, 200, ['message' => 'Subscribed successfully']);
        $this->assertDatabaseHas('user_contacts', [
            'subscriber_id' => RegularUser::where('id', $subscriber->user_id)->first()->id,
            'subscription_id' => $subscription->id
        ]);
    }

    public function testSubscribeUserBadRequest()
    {
        $data = []; // Empty data to simulate bad request
        $request = new Request($data);
        $response = $this->subscriptionService->subscribe($request);

        $this->validateJsonResponse($response, 400, ['message' => 'Bad request']);
    }

    public function testUnsubscribeUserSuccess()
    {
        list($subscriber, $subscription, $response) = $this->subscribe();

        $data = [
            'subscriberId' => $subscriber->id,
            'subscriptionId' => $subscription->id,
        ];

        $request = new Request($data);
        $response = $this->subscriptionService->unsubscribe($request);

        $this->validateJsonResponse($response, 200, ['message' => 'Unsubscribed successfully']);
    }

    public function testUnsubscribeUserNotFound()
    {
        $subscriber = $this->getRegularTestUser();
        $subscription = $this->getCompanyTestUser();

        $data = [
            'subscriberId' => $subscriber->id,
            'subscriptionId' => $subscription->id,
        ];

        $request = new Request($data);
        $response = $this->subscriptionService->unsubscribe($request);

        $this->validateJsonResponse($response, 404, ['message' => 'Subscription not found']);
    }

    protected function validateJsonResponse(JsonResponse $response, int $statusCode, array $expectedData = [])
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($statusCode, $response->getStatusCode());

        if (!empty($expectedData)) {
            foreach ($expectedData as $key => $value) {
                $this->assertArrayHasKey($key, $response->getData(true));
                $this->assertEquals($value, $response->getData(true)[$key]);
            }
        }
    }
}
