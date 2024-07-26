<?php

namespace Tests\TestsHelpers\Profile;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tests\TestsEnums\Method;
use Tests\TestsHelpers\Profile\Specific\RegularUserServiceHelper;

trait UsersHelper
{
    use RegularUserServiceHelper;

    private function expectedResult($method, $status, $response)
    {
        match ($method) {
            Method::GET => $this->expectedGetResult($response),
            Method::UPDATE => $this->expectedUpdateResult($status, $response),
            Method::DELETE => $this->expectedDeleteResult($status, $response)
        };
    }

    private function expectedGetResult($response)
    {
        match ($this->role) {
            UserRole::REGULAR_USER => $this->expectedRegularUserGetResult($response)
        };
    }

    private function expectedUpdateResult($status, $response)
    {
        match ($this->role) {
            UserRole::REGULAR_USER => $this->expectedRegularUserUpdateResult($status, $response)
        };
    }

    private function expectedDeleteResult($status, $response)
    {
        match ($this->role) {
            UserRole::REGULAR_USER => $this->expectedRegularUserDeleteResult($status, $response)
        };
    }

    private function authUser()
    {
        $this->registerUser($this->role);
        $this->login($this->getUserRegistrationCredentials($this->role));
    }

    private function getTestUser()
    {
        Log::debug('role usersHelper: ' . var_export($this->role, 1));
        $this->authUser();
        $credentials = $this->getUserRegistrationCredentials($this->role);
        return User::where('email', $credentials['email'])->first();
    }

    private function setUpRequest()
    {
        $data = $this->getUserUpdateData();
        $this->request = new Request($data);
    }

    private function getUserUpdateData()
    {
        return match ($this->role) {
            UserRole::REGULAR_USER => $this->getRegularUserUpdateRequestData(),
            UserRole::COMPANY => $this->getCompanyUpdateRequestData(),
            UserRole::ADMIN => $this->getAdminUpdateRequestData(),
        };
    }
}
