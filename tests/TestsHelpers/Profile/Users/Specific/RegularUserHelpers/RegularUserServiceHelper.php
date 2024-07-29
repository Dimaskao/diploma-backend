<?php

namespace TestsHelpers\Profile\Users\Specific\RegularUserHelpers;

use TestsHelpers\Profile\Users\Specific\RegularUserHelpers\HttpMethods\Delete\DeleteHelper;
use TestsHelpers\Profile\Users\Specific\RegularUserHelpers\HttpMethods\Get\GetHelper;
use TestsHelpers\Profile\Users\Specific\RegularUserHelpers\HttpMethods\Update\UpdateHelper;

trait RegularUserServiceHelper
{
    use GetHelper, UpdateHelper, DeleteHelper;
}
