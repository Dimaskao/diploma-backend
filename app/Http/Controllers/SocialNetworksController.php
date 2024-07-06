<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\RegularUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialNetworksController
{

    protected function searchProfiles(Request $request, $role): JsonResponse
    {
        $query = $request->input('query');
        $results = [];

        if ($role === UserRole::REGULAR_USER) {
            $results = $this->searchRegularUsers($query);
        } elseif ($role === UserRole::COMPANY) {
            $results = $this->searchCompanies($query);
        }

        return response()->json(['results' => $results], 200);
    }

    protected function getProfileData(User $user)
    {
        return User::whereHas('regularUser', function ($query) use ($query) {
            $query->where('first_name', 'LIKE', "%{$query}%")
                ->orWhere('last_name', 'LIKE', "%{$query}%");
        })->get(['id', 'email'])->toArray();
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
    public function search(Request $request): JsonResponse
    {
        try {
            $strategy = $this->factory->getStrategyBasedOnSearchType($request);
            return $strategy->search($request);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
