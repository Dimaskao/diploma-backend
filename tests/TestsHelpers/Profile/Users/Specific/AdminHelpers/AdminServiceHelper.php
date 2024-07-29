<?php

namespace TestsHelpers\Profile\Users\Specific\AdminHelpers;

use TestsHelpers\Profile\Users\Specific\AdminHelpers\Handlers\Delete\DeleteHelper;
use TestsHelpers\Profile\Users\Specific\AdminHelpers\Handlers\Get\GetHelper;
use TestsHelpers\Profile\Users\Specific\AdminHelpers\Handlers\Update\UpdateHelper;

trait AdminServiceHelper
{
    use GetHelper, UpdateHelper, DeleteHelper;
}
