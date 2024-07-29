<?php

namespace TestsHelpers\Profile\Users\Specific\RegularUserHelpers;

use TestsHelpers\Profile\Users\Specific\RegularUserHelpers\Handlers\Delete\DeleteHelper;
use TestsHelpers\Profile\Users\Specific\RegularUserHelpers\Handlers\Get\GetHelper;
use TestsHelpers\Profile\Users\Specific\RegularUserHelpers\Handlers\Update\UpdateHelper;

trait RegularUserServiceHelper
{
    use GetHelper, UpdateHelper, DeleteHelper;
}
