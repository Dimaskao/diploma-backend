<?php

namespace TestsHelpers\SocialNetwork\Subscription;

use App\Enums\UserRole;
use App\Services\Response\ResponseService;
use App\Services\SocialNetwork\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use TestsEnums\Entity;
use TestsHelpers\Auth\AuthHelper;
use TestsHelpers\Profile\Users\UsersHelper;
use TestsHelpers\SocialNetwork\Chat\ChatServiceHelper;

trait SubscriptionServiceHelper
{
    use AuthHelper, RefreshDatabase, UsersHelper, ChatServiceHelper;

    protected SubscriptionService $subscriptionService;
    protected ResponseService $responseService;
    protected array $subscriptionTestData;

    protected function setUpSubscriptionService($role): void
    {
        $this->setUpRegistry(Entity::SERVICE, $role);
        $this->responseService = new ResponseService();
        $this->subscriptionService = new SubscriptionService($this->responseService);
    }

    private function subscribe($testData = null): JsonResponse
    {
        $this->subscriptionTestData = $testData ?: $this->getSubscriptionTestData();
        $request = new Request($this->subscriptionTestData);
        return $this->subscriptionService->subscribe($request);
    }

    private function unsubscribe(): JsonResponse
    {
        $this->subscriptionTestData = $this->getSubscriptionTestData();
        $this->subscribe($this->subscriptionTestData);
        $request = new Request($this->subscriptionTestData);
        return $this->subscriptionService->unsubscribe($request);
    }

    private function getSubscriptionTestData(): array
    {
        $this->role = UserRole::REGULAR_USER;
        $subscriber = $this->getTestUser();

        $this->role = UserRole::COMPANY;
        $subscription = $this->getTestUser();

        return [
            'subscriber_id' => $subscriber->id,
            'subscription_id' => $subscription->id,
        ];
    }
}
