<?php

namespace App\Traits;

use App\Enums\SubscriptionAction;
use App\Enums\UserRole;
use App\Models\User;
use App\Models\UserContact;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait ProfileTrait
{
    use CompanyProfileTrait, RegularUserProfileTrait;

    protected function getUserProfile($id, $role): JsonResponse
    {
        $user = $this->getUser($id);

        if ($user && $role) {
            return match ($role) {
                UserRole::REGULAR_USER => $this->getRegularUserProfile($user),
                UserRole::COMPANY => $this->getCompanyProfile($user),
                default => response()->json(['message' => 'Unexpected error occurred during processing profile data'], 500),
            };
        }
        return response()->json(['message' => "User or role with id '$id' does not exist"], 404);
    }

    protected function updateUserProfile(Request $request, $id, $role): JsonResponse
    {
        $user = $this->getUser($id);

        if ($user && $role) {
            return match ($role) {
                UserRole::REGULAR_USER => $this->updateUserInformation($user, $request),
                UserRole::COMPANY => $this->updateCompanyInformation($user, $request),
                default => response()->json(['message' => 'Unexpected error occurred during updating profile data'], 500),
            };
        }
        return response()->json(['message' => "User or role with id '$id' does not exist"], 404);
    }

    /**
     * @param $id
     * @return User
     */
    private function getUser($id): User
    {
        return User::find($id);
    }
}
