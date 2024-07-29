<?php

namespace App\Services\Auth;

use App\Enums\ResponseKey;
use App\Enums\UserRole;
use App\Factories\UserFactory;
use App\Interfaces\Factory;
use App\Models\User;
use App\Services\Response\ResponseService;
use App\Services\ValidationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\ClientRepository;
use RuntimeException;

class AuthService
{
    protected Factory $factory;
    protected ValidationService $validationService;
    protected ResponseService $responseService;

    public function __construct(ValidationService $validationService, UserFactory $factory, ResponseService $responseService)
    {
        $this->validationService = $validationService;
        $this->factory = $factory;
        $this->responseService = $responseService;
    }

    /**
     * Register a new user or company.
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $data = $this->validationService->validate($request->all(), $this->registrationRules());
            $result = $this->factory->create($data);
            return $this->responseService->created($result);
        } catch (ValidationException $e) {
            return $this->responseService->badRequest("Validation error: {$e->getMessage()}");
        } catch (Exception $e) {
            return $this->responseService->internalServerError("Failed to create user or company, {$e->getMessage()}");
        }
    }

    /**
     * Log in user or company.
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $this->validationService->validate($request->all(), $this->loginRules());
            $credentials = $request->only('email', 'password');
            $role = $request->input('role');
            $token = $this->userLogin($credentials, $role);

            if ($token) {
                return $this->responseService->success([ResponseKey::TOKEN => $token]);
            } else {
                return $this->responseService->unauthorized("Invalid credentials");
            }
        } catch (ValidationException $e) {
            return $this->responseService->badRequest("Validation error: {$e->getMessage()}");
        }
        catch (Exception $e) {
            return $this->responseService->internalServerError("Unexpected error occurred during user login, error: {$e->getMessage()}");
        }
    }

    /**
     * Log out user or company.
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->userLogout($request->user());
            return $this->responseService->success();
        } catch (Exception $e) {
            return $this->responseService->internalServerError("Failed to log out user, {$e->getMessage()}");
        }
    }

    protected function registrationRules(): array
    {
        return [
            'first_name' => 'required_if:role,user|string|max:255',
            'last_name' => 'required_if:role,user|string|max:255',
            'name' => 'required_if:role,company|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:user,company,admin',
            'permissions' => 'required_if:role,admin|array'
        ];
    }

    protected function loginRules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:user,company,admin'
        ];
    }

    private function userLogin(array $credentials, string $role)
    {
        $user = User::where('email', $credentials['email'])->first();

        if ($user && in_array($role, UserRole::values()) && Hash::check($credentials['password'], $user->password)) {
            $personalAccessClient = (new ClientRepository())->personalAccessClient();

            if (!$personalAccessClient) {
                throw new RuntimeException('Personal access client not found. Please create one.');
            }

            return $user->createToken('Personal Access Token', ['*'])->accessToken;
        }

        return false;
    }

    private function userLogout($user): void
    {
        $tokens = $user->tokens;
        foreach ($tokens as $token) {
            $token->revoke();
        }
    }
}
