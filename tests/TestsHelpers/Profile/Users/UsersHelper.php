<?php

namespace TestsHelpers\Profile\Users;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use TestsEnums\Method;
use TestsHelpers\Profile\Users\Specific\CompanyHelpers\CompanyServiceHelper;
use TestsHelpers\Profile\Users\Specific\RegularUserHelpers\RegularUserServiceHelper;

trait UsersHelper
{
    use RegularUserServiceHelper, CompanyServiceHelper;

    public function getTestUser()
    {
        $this->authUser();
        return User::where('email', $this->credentials['email'])->first();
    }

    private function authUser(): void
    {
        $this->registerUser();
        $this->login($this->credentials);
    }

    private function expectedResult($method, $status, $response): void
    {
        match ($method) {
            Method::GET => $this->expectedGetResult($response),
            Method::UPDATE => $this->expectedUpdateResult($status, $response),
            Method::DELETE => $this->expectedDeleteResult($status, $response)
        };
    }

    private function expectedGetResult($response): void
    {
        match ($this->role) {
            UserRole::REGULAR_USER => $this->expectedRegularUserGetResult($response),
            UserRole::COMPANY => $this->expectedCompanyGetResult($response),
        };
    }

    private function expectedUpdateResult($status, $response): void
    {
        match ($this->role) {
            UserRole::REGULAR_USER => $this->expectedRegularUserUpdateResult($status, $response),
            UserRole::COMPANY => $this->expectedCompanyUpdateResult($status, $response),
        };
    }

    private function expectedDeleteResult($status, $response): void
    {
        match ($this->role) {
            UserRole::REGULAR_USER => $this->expectedRegularUserDeleteResult($status, $response),
            UserRole::COMPANY => $this->expectedCompanyDeleteResult($status, $response),
        };
    }

    private function setUpRequest(): void
    {
        $this->request = new Request($this->getUserUpdateData());
    }

    private function getUserUpdateData(): array
    {
        return match ($this->role) {
            UserRole::REGULAR_USER => $this->getRegularUserUpdateRequestData(),
            UserRole::COMPANY => $this->getCompanyUpdateRequestData(),
//            UserRole::ADMIN => $this->getAdminUpdateRequestData(),
        };
    }
}
