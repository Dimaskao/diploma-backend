<?php

namespace TestsHelpers\SocialNetwork\Search;

use App\Enums\SearchType;
use App\Enums\UserRole;
use App\Services\Response\ResponseService;
use App\Services\SocialNetwork\SearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use TestsEnums\Entity;
use TestsHelpers\Auth\AuthHelper;
use TestsHelpers\Profile\Users\UsersHelper;
use TestsHelpers\SocialNetwork\Chat\ChatServiceHelper;

trait SearchServiceHelper
{
    use AuthHelper, RefreshDatabase, UsersHelper, ChatServiceHelper;

    protected SearchService $searchService;
    protected ResponseService $responseService;

    protected function setUpSearchService($role): void
    {
        $this->setUpRegistry(Entity::SERVICE, $role);
        $this->responseService = new ResponseService();
        $this->searchService = new SearchService($this->responseService);
    }

    private function search($requestData): JsonResponse
    {
        $request = new Request($requestData);
        return $this->searchService->search($request);
    }

    private function getTestRegularUsersSearchRequest(): array
    {
        $this->role = UserRole::REGULAR_USER;
        $this->refreshCredentials();
        $this->userTest();

        return [
            'query' => 'John',
            'search_type' => SearchType::USERS
        ];
    }

    private function getTestCompaniesSearchRequest(): array
    {
        $this->role = UserRole::COMPANY;
        $this->refreshCredentials();
        $this->userTest();

        return [
            'query' => 'Test Company',
            'search_type' => SearchType::COMPANIES
        ];
    }
}
