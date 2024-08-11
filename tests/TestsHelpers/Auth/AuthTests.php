<?php

namespace TestsHelpers\Auth;

use TestsHelpers\Auth\Login\AuthLoginHelper;
use TestsHelpers\Auth\Login\AuthLoginTestsHelper;
use TestsHelpers\Auth\Logout\AuthLogoutHelper;
use TestsHelpers\Auth\Logout\AuthLogoutTestsHelper;
use TestsHelpers\Auth\Register\AuthRegisterHelper;
use TestsHelpers\Auth\Register\AuthRegisterTestsHelper;
use TestsHelpers\Auth\SpecificUser\AdminAuthHelper;
use TestsHelpers\Auth\SpecificUser\CompanyAuthHelper;
use TestsHelpers\Auth\SpecificUser\RegularUserAuthHelper;

trait AuthTests
{
    use RegularUserAuthHelper,
        CompanyAuthHelper,
        AdminAuthHelper,
        AuthLoginHelper,
        AuthLogoutHelper,
        AuthRegisterHelper,
        AuthLoginTestsHelper,
        AuthRegisterTestsHelper,
        AuthLogoutTestsHelper;
}
