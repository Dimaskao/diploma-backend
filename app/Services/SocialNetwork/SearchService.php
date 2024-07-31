<?php

namespace App\Services\SocialNetwork;

use App\Enums\ResponseKey;
use App\Enums\SearchType;
use App\Models\Company;
use App\Models\RegularUser;
use App\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            ResponseKey::USERS => $searchType === SearchType::USERS || $searchType === SearchType::ALL ? $this->getRegularUsersSearchResults($query) : [],
            ResponseKey::COMPANIES => $searchType === SearchType::COMPANIES || $searchType === SearchType::ALL ? $this->getCompaniesSearchResults($query) : [],
        ];

        return $this->responseService->success($results);
    }

    protected function getRegularUsersSearchResults($query): array
    {
        return RegularUser::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"])
            ->with(['userProfile.user' => function ($query) {
                $query->select('id', 'avatar_url', 'user_profile_id');
            }])
            ->get()
            ->unique('id')
            ->map(function ($regularUser) {
                return [
                    'id' => $regularUser->userProfile->user->id,
                    'name' => "{$regularUser->first_name} {$regularUser->last_name}",
                    'avatar_url' => $user->avatar_url ?? null
                ];
            })
            ->filter()
            ->toArray();
    }

    protected function getCompaniesSearchResults($query): array
    {
        return Company::where('name', 'LIKE', "%{$query}%")
            ->with(['userProfile.user' => function ($query) {
                $query->select('id', 'avatar_url', 'user_profile_id');
            }])
            ->get()
            ->unique('id')
            ->map(function ($company) {
                return [
                    'id' => $company->userProfile->user->id,
                    'name' => $company->name,
                    'avatar_url' => $user->avatar_url ?? null
                ];
            })
            ->filter()
            ->toArray();
    }
}
