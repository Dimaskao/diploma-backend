<?php

namespace App\Services\SocialNetwork;

use App\Enums\ResponseKey;
use App\Enums\SubscriptionAction;
use App\Models\RegularUser;
use App\Models\User;
use App\Models\UserContact;
use App\Services\Response\ResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubscriptionService
{
    protected ResponseService $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    public function subscribe(Request $request): JsonResponse
    {
        return $this->manageSubscription($request, SubscriptionAction::SUBSCRIBE);
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        return $this->manageSubscription($request, SubscriptionAction::UNSUBSCRIBE);
    }

    private function manageSubscription(Request $request, $action): JsonResponse
    {
        try {
            if ($request->has(['subscription_id', 'subscriber_id'])) {
                $subscriberId = User::find($request->input('subscriber_id'))->userProfile->regular_user_id;
                $subscriptionId = $request->input('subscription_id');

                return match ($action) {
                    SubscriptionAction::SUBSCRIBE => $this->subscribeUser($subscriberId, $subscriptionId),
                    SubscriptionAction::UNSUBSCRIBE => $this->unsubscribeUser($subscriberId, $subscriptionId),
                    default => $this->responseService->badRequest('No subscription action found')
                };
            }
            return $this->responseService->badRequest('Subscription ID or Subscriber ID was not set');
        } catch (Exception $e) {
            return $this->responseService->internalServerError("Error during {$action}: {$e->getMessage()}");
        }
    }

    private function subscribeUser($subscriberId, $subscriptionId): JsonResponse
    {
        UserContact::create([
            'id' => (string)Str::uuid(),
            'subscriber_id' => $subscriberId,
            'subscription_id' => $subscriptionId
        ]);

        return $this->responseService->success();
    }

    private function unsubscribeUser($subscriberId, $subscriptionId): JsonResponse
    {
        $userContact = UserContact::where('subscriber_id', $subscriberId)
            ->where('subscription_id', $subscriptionId)
            ->first();

        if ($userContact) {
            $userContact->delete();
            return $this->responseService->success();
        }

        return $this->responseService->notFound("Subscription not found");
    }
}
