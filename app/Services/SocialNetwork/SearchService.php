<?php

namespace App\Services\SocialNetwork;

use App\Enums\ResponseKeys;
use App\Enums\SearchType;
use App\Models\Company;
use App\Models\RegularUser;
use App\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchService
{
    protected ResponseService $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    public function search(Request $request): JsonResponse
    {
        if (!$request->has(SearchType::SEARCH_TYPE) || !$request->has('query')) {
            $this->responseService->badRequest();
        }

        $searchType = $request->input(SearchType::SEARCH_TYPE);
        $query = $request->input('query');

        $results = [
            'users' => $searchType === SearchType::USERS || $searchType === SearchType::ALL ? $this->getRegularUsersSearchResults($query) : [],
            'companies' => $searchType === SearchType::COMPANIES || $searchType === SearchType::ALL ? $this->getCompaniesSearchResults($query) : [],
        ];

        return $this->responseService->success($results);
    }

    /**
     * @param $query
     * @return array
     */
    protected function getRegularUsersSearchResults($query): array
    {
        return RegularUser::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"])
            ->with(['user' => function ($query) {
                $query->select('id', 'avatar_url', 'profileable_id', 'profileable_type');
            }])
            ->get()
            ->unique('id')
            ->map(function ($regularUser) {
                return [
                    'id' => $regularUser->user->id,
                    'name' => "{$regularUser->first_name} {$regularUser->last_name}",
                    'avatar_url' => $regularUser->user->avatar_url ?? null
                ];
            })
            ->toArray();
    }

    /**
     * @param $query
     * @return array
     */
    protected function getCompaniesSearchResults($query): array
    {
        return Company::where('name', 'LIKE', "%{$query}%")
            ->with(['user' => function ($query) {
                $query->select('id', 'avatar_url', 'profileable_id', 'profileable_type');
            }])
            ->get()
            ->unique('id')
            ->map(function ($company) {
                return [
                    'id' => $company->user->id,
                    'name' => $company->name,
                    'avatar_url' => $company->user->avatar_url ?? null
                ];
            })
            ->toArray();
    }
}
