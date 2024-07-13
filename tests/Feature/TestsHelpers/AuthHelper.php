<?php

namespace Tests\Feature\TestsHelpers;

use App\Factories\UserFactory;
use App\Http\Controllers\AuthController;
use App\Interfaces\Factory;
use App\Models\User;
use App\Services\AuthService;
use App\Services\ValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait AuthHelper
{
    protected $registry;
    protected ValidationService $validationService;
    protected Factory $userFactory;
    protected AuthService $authService;
    protected AuthController $authController;

    protected function setUpAuthService() : void
    {
        $this->setUpAuthServiceEntity();
        $this->registry = $this->authService;
    }

    protected function setUpAuthController() : void
    {
        $this->setUpAuthServiceEntity();
        $this->authController = new AuthController($this->authService);
        $this->registry = $this->authController;
    }

    /**
     * @return void
     */
    protected function setUpAuthServiceEntity(): void
    {
        $this->validationService = new ValidationService();
        $this->userFactory = new UserFactory();
        $this->authService = new AuthService($this->validationService, $this->userFactory);
    }

    /**
     * @return JsonResponse
     */
    public function registerUser(): JsonResponse
    {
        $request = Request::create('/register', 'POST', $this->getUserRegistrationCredentials());
        return $this->registry->register($request);
    }

    /**
     * @return JsonResponse
     */
    public function registerCompany(): JsonResponse
    {
        $request = Request::create('/register', 'POST', $this->getCompanyRegistrationCredentials());
        return $this->authService->register($request);
    }

    public function getUserRegistrationCredentials(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ];
    }

    public function getCompanyRegistrationCredentials(): array
    {
        return [
            'name' => 'Test Company',
            'email' => 'test.company@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'company',
        ];
    }

    public function testRegisterUserSuccess()
    {
        $this->assertEquals(201, $this->registerUser()->getStatusCode());
        $this->assertDatabaseHas('users', ['email' => $this->getUserRegistrationCredentials()['email']]);
    }

    public function testRegisterCompanySuccess()
    {
        $this->assertEquals(201, $this->registerCompany()->getStatusCode());
        $this->assertDatabaseHas('users', ['email' => $this->getCompanyRegistrationCredentials()['email']]);
    }

    public function testRegisterValidationFails()
    {
        $this->expectException(ValidationException::class);

        $request = Request::create('/register', 'POST', [
            'email' => 'invalid-email',
        ]);

        $response = $this->registry->register($request);

        $this->assertEquals(422, $response->getStatusCode()); // 422 Unprocessable Entity is the standard response for validation failures
        $this->assertArrayHasKey('error', $response->getData(true));
    }

    public function testLoginUserSuccess()
    {
        $this->registerUser();
        $this->testLoginSuccess($this->getUserRegistrationCredentials());
    }

    public function testLoginCompanySuccess()
    {
        $this->registerCompany();
        $this->testLoginSuccess($this->getCompanyRegistrationCredentials());
    }

    public function testLoginUserFails()
    {
        $this->registerUser();
        $this->testLoginFails($this->getUserRegistrationCredentials());
    }

    public function testLoginCompanyFails()
    {
        $this->registerUser();
        $this->testLoginFails($this->getCompanyRegistrationCredentials());
    }

    public function testLogoutUserSuccess()
    {
        $this->registerUser();
        $credentials = $this->getUserRegistrationCredentials();
        $this->testLogoutSuccess($credentials['email']);
    }

    public function testLogoutCompanySuccess()
    {
        $this->registerCompany();
        $credentials = $this->getCompanyRegistrationCredentials();
        $this->testLogoutSuccess($credentials['email']);
    }

    public function testLogoutFails()
    {
        $request = Request::create('/logout', 'POST');

        $response = $this->registry->logout($request);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertArrayHasKey('error', $response->getData(true));
        $this->assertEquals('Failed to log out user', $response->getData(true)['error']);
    }

    /**
     * @param array $registrationCredentials
     * @return void
     */
    private function testLoginSuccess(array $registrationCredentials): void
    {
        $request = Request::create('/login', 'POST', [
            'email' => $registrationCredentials['email'],
            'password' => $registrationCredentials['password'],
            'role' => $registrationCredentials['role'],
        ]);

        $response = $this->registry->login($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('token', $response->getData(true));
    }

    /**
     * @param array $registrationCredentials
     * @return void
     */
    private function testLoginFails(array $registrationCredentials): void
    {
        $request = Request::create('/login', 'POST', [
            'email' => $registrationCredentials['email'],
            'password' => 'wrongpassword',
            'role' => $registrationCredentials['role'],
        ]);

        $response = $this->registry->login($request);

        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * @param $email
     * @return void
     */
    private function testLogoutSuccess($email): void
    {
        $user = User::where('email', $email)->first();

        $this->actingAs($user, 'api');

        $request = Request::create('/logout', 'POST');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->registry->logout($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
