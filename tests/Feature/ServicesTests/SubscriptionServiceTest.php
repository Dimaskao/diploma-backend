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

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthService();
        $this->subscriptionService = new SubscriptionService();
        $this->seed(DatabaseSeeder::class);
    }

    public function testSubscribeUserSuccess()
    {
        list($subscriber, $subscription, $response) = $this->subscribe($this->subscriptionService);

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
        list($subscriber, $subscription, $response) = $this->subscribe($this->subscriptionService);

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
}
