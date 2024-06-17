<?php

namespace App\Traits;

use App\Models\Company;
use App\Models\RegularUser;
use App\Models\User;
use Illuminate\Http\Request;

trait ProfileTrait
{
    private function getUserAndRole($id)
    {
        $user = User::with('role')->find($id);
        if (!$user) {
            return null;
        }

        $role = $user->role->name ?? null;
        return [$user, $role];
    }

    /**
     * @param Request $request
     * @param mixed $query
     * @param array $results
     * @return array
     */
    public function getRegularUsersSearchResults(Request $request, mixed $query, array $results): array
    {
        if ($request->has('users') || $request->has('all')) {
            $users1 = RegularUser::where('first_name', 'LIKE', "%{$query}%")->where('last_name', 'LIKE', "%{$query}%")->get();
            $users2 = RegularUser::where('first_name', 'LIKE', "%{$query}%")->orWhere('last_name', 'LIKE', "%{$query}%")->get();
            $users = $users1->merge($users2)->unique('id')->values();

            foreach ($users as $user) {
                $results[] = [
                    'id' => User::where('user_id', $user->id)->id,
                    'name' => "{$user->first_name} {$user->last_name}"
                ];
            }
        }
        return $results;
    }

    /**
     * @param Request $request
     * @param mixed $query
     * @param array $results
     * @return array
     */
    public function getCompaniesSearchResults(Request $request, mixed $query, array $results): array
    {
        if ($request->has('companies') || $request->has('all')) {
            $companies = Company::where('name', 'LIKE', "%{$query}%")->get();

            foreach ($companies as $company) {
                $results[] = [
                    'id' => User::where('user_id', $company->id)->value('id'),
                    'name' => $company->name
                ];
            }
        }
        return $results;
    }
}
