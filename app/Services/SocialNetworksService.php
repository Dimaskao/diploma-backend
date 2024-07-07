<?php

namespace App\Services;

use App\Enums\SearchType;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\RegularUser;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialNetworksService
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function search(Request $request): JsonResponse
    {
        if ($request->has('searchType')) {
            $searchType = $request->input('searchType');
            $results = [];

            if ($searchType == SearchType::USERS || $searchType == SearchType::ALL) {
                $results[] = $this->getRegularUsersSearchResults($request, []);
            }

            if ($searchType == SearchType::COMPANIES || $searchType == SearchType::ALL) {
                $results[] = $this->getCompaniesSearchResults($request, $results);
            }

            return response()->json(['results' => $results]);
        }

        return response()->json(['message' => 'Bad request'], 400);
    }

    public function subscribe(Request $request): JsonResponse
    {
        try {
            return $this->subscriptionService->subscribe($request);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        try {
            return $this->subscriptionService->unsubscribe($request);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * @param Request $request
     * @param array $results
     * @return array
     */
    protected function getRegularUsersSearchResults(Request $request, array $results): array
    {
        $query = $request->input('query');

        if ($request->has('users') || $request->has('all')) {
            $users1 = RegularUser::where('first_name', 'LIKE', "%{$query}%")
                ->where('last_name', 'LIKE', "%{$query}%")
                ->get();
            $users2 = RegularUser::where('first_name', 'LIKE', "%{$query}%")
                ->orWhere('last_name', 'LIKE', "%{$query}%")
                ->get();
            $users = $users1->merge($users2)->unique('id')->values();

            foreach ($users as $user) {
                $results[] = [
                    'id' => User::where('user_id', $user->id)->value('id'),
                    'name' => "{$user->first_name} {$user->last_name}"
                ];
            }
        }
        return $results;
    }

    /**
     * @param Request $request
     * @param array $results
     * @return array
     */
    protected function getCompaniesSearchResults(Request $request, array $results): array
    {
        $query = $request->input('query');

        if ($request->has('companies') || $request->has('all')) {
            $companies = Company::where('name', 'LIKE', "%{$query}%")->get();

            foreach ($companies as $company) {
                $results[] = [
                    'id' => User::where('company_id', $company->id)->value('id'),
                    'name' => $company->name
                ];
            }
        }
        return $results;
    }
}
