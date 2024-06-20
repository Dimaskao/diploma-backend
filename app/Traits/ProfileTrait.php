<?php

namespace App\Traits;

use App\Models\Company;
use App\Models\RegularUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait ProfileTrait
{
    private function getUserAndRole($id)
    {
        $user = User::find($id);
        if (!$user) {
            return null;
        }
        return [$user, $user->role->name ?? null];
    }

    /**
     * @param Request $request
     * @param array $results
     * @return array
     */
    public function getRegularUsersSearchResults(Request $request, array $results): array
    {
        $users = collect();

        if ($request->has('first_name') && !$request->has('last_name')) {
            $firstName = $request->input('first_name');
            $users = RegularUser::where('first_name', 'LIKE', "%$firstName")->get();
        }

        if ($request->has('last_name') && !$request->has('first_name')) {
            $lastName = $request->input('last_name');
            $users = $users->merge(RegularUser::where('first_name', 'LIKE', "%$lastName")->get());
        }

        if ($request->has('first_name') && $request->has('last_name')) {
            $firstName = $request->input('first_name');
            $lastName = $request->input('last_name');
            $users = $users->merge(RegularUser::where('first_name', 'LIKE', "%$firstName%")->where('last_name', 'LIKE', "%$lastName%")->get());
        }

        Log::error('Users: ' . var_export($users, 1));

        foreach ($users as $user) {
            Log::debug('$user: ' . var_export($user, 1));

            $userModel = User::where('user_id', $user->id)->first();
            if ($userModel) {
                $results[] = [
                    'id' => $userModel->id,
                    'name' => "{$user->first_name} {$user->last_name}"
                ];
            } else {
                Log::error("User model not found for RegularUser ID: {$user->id}");
            }
        }

        return $results;
    }

    /**
     * @param Request $request
     * @param array $results
     * @return array
     */
    public function getCompaniesSearchResults(Request $request, array $results): array
    {
        $companies = [];

        if ($request->has('name')) {
            $name = $request->input('name');
            $companies = Company::where('name', 'LIKE', "%$name%")->get();
        }

        foreach ($companies as $company) {
            Log::debug('$company: ' . var_export($company, 1));

            $userModel = User::where('company_id', $company->id)->first();
            if ($userModel) {
                $results[] = [
                    'id' => $userModel->id,
                    'name' => $company->name
                ];
            } else {
                Log::error("User model not found for Company ID: {$company->id}");
            }
        }

        return $results;
    }
}
