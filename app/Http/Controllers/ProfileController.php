<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\RegularUser;
use App\Models\User;
use App\Models\UserContact;
use App\Traits\CompanyProfileTrait;
use App\Traits\ProfileTrait;
use App\Traits\RegularUserProfileTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    use ProfileTrait;
    use CompanyProfileTrait;
    use RegularUserProfileTrait;

    public function show($id): JsonResponse
    {
        list($user, $role) = $this->getUserAndRole($id);

        if ($role == UserRole::RegularUser) {
            $profile = $this->getRegularUserProfile($user);
        } elseif ($role == UserRole::Company) {
            $profile = $this->getCompanyProfile($user);
        } else {
            return response()->json(['error' => 'Invalid role'], 400);
        }

        return response()->json($profile);
    }

    public function update(Request $request, $id): JsonResponse
    {
        list($user, $role) = $this->getUserAndRole($id);

        if ($role == UserRole::RegularUser) {
            return $this->updateUserInformation(RegularUser::where('id', $user->user_id)->first(), $request);
        } elseif ($role == UserRole::Company) {
            return $this->updateCompanyInformation(Company::where('id', $user->company_id)->first(), $request);
        }

        return response()->json(['error' => 'Invalid request data'], 400);
    }

    /**
     * Add a new contact for a user.
     */
    public function subscribe(Request $request): JsonResponse
    {
        if ($request->has(['subscriberId', 'subscriptionId'])) {
            $data['subscriber_id'] = User::where('id', $request->input('subscriberId'))->user_id;
            $data['subscription_id'] = $request->input('subscriptionId');
            UserContact::insert($data);

            return response()->json(['message' => 'Subscribed successfully'], 200);
        }

        return response()->json(['message' => 'Bad request'], 400);
    }

    /**
     * Remove a new contact for a user.
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        if ($request->has(['subscriberId', 'subscriptionId'])) {
            DB::table('user_contacts')->delete(UserContact::where('subscriber_id', $request->input('subscriberId'))->where('subscription_id', $request->input('subscriptionId'))->id);

            return response()->json(['message' => 'Unsubscribed successfully'], 200);
        }

        return response()->json(['message' => 'Bad request'], 400);
    }

    public function search(Request $request): JsonResponse
    {
        if ($request->has('query') && ($request->has('users') || $request->has('companies') || $request->has('all'))) {
            $query = $request->input('query');
            $results[] = $this->getRegularUsersSearchResults($request, $query, []);
            $results[] = $this->getCompaniesSearchResults($request, $query, $results);
            return response()->json(['results' => $results]);
        }

        return response()->json(['message' => 'Bad request'], 400);
    }
}
