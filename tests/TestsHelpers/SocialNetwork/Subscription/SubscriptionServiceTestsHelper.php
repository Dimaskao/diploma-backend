<?php

namespace TestsHelpers\SocialNetwork\Subscription;

use App\Models\User;
use Illuminate\Http\Request;
use TestsHelpers\SocialNetwork\ExpectedTestsResults;

trait SubscriptionServiceTestsHelper
{
    use SubscriptionServiceHelper, ExpectedTestsResults;

    public function testSubscribeUserSuccess(): void
    {
        $response = $this->subscribe();
        $subscriberId = $this->subscriptionTestData['subscriber_id'];
        $subscriptionId = $this->subscriptionTestData['subscription_id'];
        $this->expectedSubscribeUserSuccess($response, $subscriberId, $subscriptionId);
    }

    public function testSubscribeUserBadRequest(): void
    {
        $data = []; // Empty data to simulate bad request
        $request = new Request($data);
        $response = $this->subscriptionService->subscribe($request);
        $this->expectedSubscribeUserBadRequestResult($response);
    }

    public function testUnsubscribeUserSuccess(): void
    {
        $response = $this->unsubscribe();
        $this->expectedUnsubscribeUserSuccessResult($response);
    }

    public function testUnsubscribeUserNotFound(): void
    {
        $this->refreshCredentials();
        $request = new Request($this->getSubscriptionTestData());
        $response = $this->subscriptionService->unsubscribe($request);
        $this->expectedUnsubscribeUserNotFoundResult($response);
    }
}
