<?php

namespace TestsHelpers\Profile\Users\Specific\CompanyHelpers;

use TestsHelpers\Profile\Users\Specific\CompanyHelpers\Handlers\Delete\DeleteHelper;
use TestsHelpers\Profile\Users\Specific\CompanyHelpers\Handlers\Get\GetHelper;
use TestsHelpers\Profile\Users\Specific\CompanyHelpers\Handlers\Update\UpdateHelper;

trait CompanyServiceHelper
{
    use GetHelper, UpdateHelper, DeleteHelper;
}
