<?php

namespace App\Http\Controllers;

use App\Enums\ForSearchType;
use App\Enums\UserRole;
use App\Models\UserContact;
use App\Traits\CompanyProfileTrait;
use App\Traits\ProfileTrait;
use App\Traits\RegularUserProfileTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    use ProfileTrait, CompanyProfileTrait, RegularUserProfileTrait;

    public function show($id): JsonResponse
    {
        list($user, $role) = $this->getUserAndRole($id);
        if ($user && $role) {
            return match ($role) {
                UserRole::RegularUser->value => $this->getRegularUserProfile($user),
                UserRole::Company->value => $this->getCompanyProfile($user),
                default => response()->json(['message' => 'Unexpected error occurred during processing profile data'], 500),
            };
        }
        return response()->json(['message' => "User or role with id '$id' does not exist"], 404);
    }

    public function update(Request $request, $id): JsonResponse
    {
        list($user, $role) = $this->getUserAndRole($id);

        if ($user && $role) {
            return match ($role) {
                UserRole::RegularUser->value => $this->updateUserInformation($user, $request),
                UserRole::Company->value  => $this->updateCompanyInformation($user, $request),
                default => response()->json(['message' => 'Unexpected error occurred during updating profile data'], 500),
            };
        }
        return response()->json(['message' => "User or role with id '$id' does not exist"], 404);
    }

    /**
     * Add a new contact for a user.
     */
    public function subscribe(Request $request): JsonResponse
    {
        try {
            if ($request->has(['subscriptionId', 'subscriberId'])) {
                UserContact::insert([
                    'id' => (string)Str::uuid(),
                    'subscriber_id' => $request->input('subscriberId'),
                    'subscription_id' => $request->input('subscriptionId')
                ]);

                return response()->json(['message' => 'Subscribed successfully'], 200);
            }

            return response()->json(['message' => 'Bad request'], 400);
        } catch (Exception $e) {
            return response()->json(['message' => "Error during subscription user with id {$request->input('subscriberId')} to user with id {$request->input('subscriptionId')}; Error: $e"], 500);
        }
    }

    /**
     * Remove a new contact for a user.
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        try {
            if ($request->has(['subscriberId', 'subscriptionId'])) {
                $subscriberId = $request->input('subscriberId');
                $subscriptionId = $request->input('subscriptionId');

                $userContactRecord = UserContact::where('subscriber_id', $subscriberId)->where('subscription_id', $subscriptionId)->first();
                $userContactRecord->delete();
            } elseif($request->has('recordId')) {
                UserContact::find($request->input('recordId'))->delete();
            } else {
                return response()->json(['message' => 'Bad request'], 400);
            }
            return response()->json(['message' => 'Unsubscribed successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => "Error during unsubscription user with id {$request->input('subscriberId')} to user with id {$request->input('subscriptionId')}; Error: $e"], 500);
        }
    }

    public function search(Request $request): JsonResponse
    {
        if ($request->has('for')) {
            $for = $request->input('for');
            $results = [];

            if ($for == ForSearchType::Users->value || $for == ForSearchType::All->value) {
                $results[] = $this->getRegularUsersSearchResults($request, []);
            }

            if ($for == ForSearchType::Companies->value || $for == ForSearchType::All->value) {
                $results[] = $this->getCompaniesSearchResults($request, $results);
            }

            Log::info('search resluts: ' . var_export($results, 1));

            return response()->json(['results' => $results]);
        }

        return response()->json(['message' => 'Bad request'], 400);
    }
}
