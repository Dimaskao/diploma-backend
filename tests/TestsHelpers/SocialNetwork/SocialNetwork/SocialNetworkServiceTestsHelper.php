<?php

namespace TestsHelpers\SocialNetwork\SocialNetwork;

use TestsHelpers\SocialNetwork\Chat\ChatServiceTestsHelper;
use TestsHelpers\SocialNetwork\Message\MessageServiceTestsHelper;
use TestsHelpers\SocialNetwork\Search\SearchServiceTestsHelper;
use TestsHelpers\SocialNetwork\Subscription\SubscriptionServiceTestsHelper;

trait SocialNetworkServiceTestsHelper
{
    use SocialNetworkServiceHelper, ChatServiceTestsHelper, MessageServiceTestsHelper, SearchServiceTestsHelper, SubscriptionServiceTestsHelper;
}
