<?php

namespace App\Services;

use App\Enums\SearchType;
use App\Models\Company;
use App\Models\RegularUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchService
{
    public function search(Request $request): JsonResponse
    {
        if (!$request->has('searchType') || !$request->has('query')) {
            return response()->json(['message' => 'Bad request'], 400);
        }

        $searchType = $request->input('searchType');
        $query = $request->input('query');

        $results['users'] = ($searchType == SearchType::USERS || $searchType == SearchType::ALL) ? $this->getRegularUsersSearchResults($query) : [];
        $results['companies'] = ($searchType == SearchType::COMPANIES || $searchType == SearchType::ALL) ? $this->getCompaniesSearchResults($query) : [];

        return response()->json(['results' => $results]);
    }

    /**
     * @param Request $request
     * @param array $results
     * @param $query
     * @return array
     */
    protected function getRegularUsersSearchResults($query): array
    {
        return RegularUser::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => User::where('user_id', $user->id)->value('id'),
                    'name' => "{$user->first_name} {$user->last_name}"
                ];
            })
            ->toArray();
    }

    /**
     * @param Request $request
     * @param array $results
     * @param $query
     * @return array
     */
    protected function getCompaniesSearchResults($query): array
    {
        return Company::where('name', 'LIKE', "%{$query}%")
            ->get()
            ->map(function ($company) {
                return [
                    'id' => User::where('company_id', $company->id)->value('id'),
                    'name' => $company->name
                ];
            })
            ->toArray();
    }
}
