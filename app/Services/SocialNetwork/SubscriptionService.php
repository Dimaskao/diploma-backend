<?php

namespace App\Services\SocialNetwork;

use App\Enums\ResponseKeys;
use App\Enums\SubscriptionAction;
use App\Models\RegularUser;
use App\Models\User;
use App\Models\UserContact;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionService
{
    /**
     * $request = [
     * 'subscriberId' => User->id
     * 'subscriptionId' => User->id
     * ]
     */
    public function subscribe(Request $request): JsonResponse
    {
        try {
            return $this->manageSubscription($request, SubscriptionAction::SUBSCRIBE);
        } catch (Exception $e) {
            return response()->json([ResponseKeys::ERROR => $e->getMessage()], 404);
        }
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        try {
            return $this->manageSubscription($request, SubscriptionAction::UNSUBSCRIBE);
        } catch (Exception $e) {
            return response()->json([ResponseKeys::ERROR => $e->getMessage()], 404);
        }
    }

    private function manageSubscription(Request $request, $action): JsonResponse
    {
        try {
            if ($request->has(['subscription_id', 'subscriber_id'])) {
                $subscriber = User::find($request->input('subscriber_id'));
                $subscriptionId = $request->input('subscription_id');
                $subscriberId = RegularUser::where('id', $subscriber->profileable->id)->first()->id;

                return match ($action) {
                    SubscriptionAction::SUBSCRIBE => $this->subscribeUser($subscriberId, $subscriptionId),
                    SubscriptionAction::UNSUBSCRIBE => $this->unsubscribeUser($subscriberId, $subscriptionId),
                    default => response()->json([ResponseKeys::ERROR => 'No subscription action found'], 400)
                };
            }

            return response()->json([ResponseKeys::ERROR => 'Bad request'], 400);
        } catch (Exception $e) {
            return response()->json([ResponseKeys::ERROR => "Error during {$action}: " . $e->getMessage()], 500);
        }
    }

    /**
     * @param $subscriberId
     * @param $subscriptionId
     * @return JsonResponse
     */
    private function subscribeUser($subscriberId, $subscriptionId): JsonResponse
    {
        UserContact::create([
            'id' => (string)Str::uuid(),
            'subscriber_id' => $subscriberId,
            'subscription_id' => $subscriptionId
        ]);
        return response()->json([ResponseKeys::ERROR => 'Subscribed successfully'], 200);
    }

    private function unsubscribeUser($subscriberId, $subscriptionId): JsonResponse
    {
        $userContact = UserContact::where('subscriber_id', $subscriberId)
            ->where('subscription_id', $subscriptionId)
            ->first();

        if ($userContact) {
            $userContact->delete();
            return response()->json([ResponseKeys::ERROR => 'Unsubscribed successfully'], 200);
        }

        return response()->json([ResponseKeys::ERROR => 'Subscription not found'], 404);
    }
}
