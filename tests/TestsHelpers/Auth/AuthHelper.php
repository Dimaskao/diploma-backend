<?php

namespace TestsHelpers\Auth;

use App\Enums\UserRole;
use App\Factories\UserFactory;
use App\Http\Controllers\AuthController;
use App\Interfaces\Factory;
use App\Services\Auth\AuthService;
use App\Services\Response\ResponseService;
use App\Services\ValidationService;
use TestsEnums\Entity;
use TestsHelpers\Auth\Login\AuthLoginHelper;
use TestsHelpers\Auth\Logout\AuthLogoutHelper;
use TestsHelpers\Auth\Register\AuthRegisterHelper;
use TestsHelpers\Auth\SpecificUser\AdminAuthHelper;
use TestsHelpers\Auth\SpecificUser\CompanyAuthHelper;
use TestsHelpers\Auth\SpecificUser\RegularUserAuthHelper;

trait AuthHelper
{
    use RegularUserAuthHelper, CompanyAuthHelper, AdminAuthHelper, AuthLoginHelper, AuthLogoutHelper, AuthRegisterHelper;

    /** AuthService or AuthController entity */
    protected mixed $registry;

    protected string $role;
    protected array $credentials;
    protected ValidationService $validationService;
    protected ResponseService $responseService;
    protected Factory $userFactory;
    protected AuthService $authService;
    protected AuthController $authController;

    protected function setUpRegistry(string $entity, string $role): void
    {
        $this->setUpRole($role);
        $this->setUpCredentials();
        match ($entity) {
            Entity::SERVICE => $this->setUpAuthService(),
            Entity::CONTROLLER => $this->setUpAuthController()
        };
    }

    public function refreshCredentials(): void
    {
        $this->setUpCredentials();
    }

    private function getUserRegistrationCredentials($role): array
    {
        return match ($role) {
            UserRole::REGULAR_USER => $this->getRegularUserRegistrationCredentials(),
            UserRole::COMPANY => $this->getCompanyRegistrationCredentials(),
            UserRole::ADMIN => $this->getAdminRegistrationCredentials()
        };
    }

    private function setUpRole($role): void
    {
        $this->role = $role;
    }

    private function setUpCredentials(): void
    {
        $this->credentials = $this->getUserRegistrationCredentials($this->role);
    }

    private function setUpAuthService(): void
    {
        $this->setUpAuthServiceEntity();
        $this->registry = $this->authService;
    }

    private function setUpAuthController(): void
    {
        $this->setUpAuthServiceEntity();
        $this->authController = new AuthController($this->authService);
        $this->registry = $this->authController;
    }

    private function setUpAuthServiceEntity(): void
    {
        $this->validationService = new ValidationService();
        $this->userFactory = new UserFactory();
        $this->responseService = new ResponseService();
        $this->authService = new AuthService($this->validationService, $this->userFactory, $this->responseService);
    }
}
