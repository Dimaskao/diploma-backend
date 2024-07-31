<?php

namespace TestsHelpers\SocialNetwork\SocialNetwork;

use App\Services\SocialNetwork\SocialNetworkService;
use TestsEnums\Entity;
use TestsHelpers\SocialNetwork\Chat\ChatServiceHelper;
use TestsHelpers\SocialNetwork\Message\MessageServiceHelper;
use TestsHelpers\SocialNetwork\Search\SearchServiceHelper;
use TestsHelpers\SocialNetwork\Subscription\SubscriptionServiceHelper;

trait SocialNetworkServiceHelper
{
    use SubscriptionServiceHelper, ChatServiceHelper, MessageServiceHelper, SearchServiceHelper;

    protected SocialNetworkService $socialNetworkService;

    protected function setUpSocialNetworkService($role): void
    {
        $this->setUpRegistry(Entity::SERVICE, $role);
        $this->setUpSearchService($role);
        $this->setUpChatService($role);
        $this->setUpSubscriptionService($role);
        $this->setUpMessageService($role);
        $this->socialNetworkService = new SocialNetworkService(
            $this->subscriptionService,
            $this->chatService,
            $this->messageService,
            $this->searchService,
            $this->responseService
        );
    }
}
