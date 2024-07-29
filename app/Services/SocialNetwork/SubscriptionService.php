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
use Illuminate\Support\Str;

class SubscriptionService
{
    protected ResponseService $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

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
            return $this->responseService->notFound($e->getMessage());
        }
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        try {
            return $this->manageSubscription($request, SubscriptionAction::UNSUBSCRIBE);
        } catch (Exception $e) {
            return $this->responseService->notFound($e->getMessage());
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
                    default => $this->responseService->badRequest('No subscription action found')
                };
            }
            return $this->responseService->badRequest('Subscription ID or Subscriber ID was not set');
        } catch (Exception $e) {
            return $this->responseService->internalServerError("Error during {$action}: {$e->getMessage()}");
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
