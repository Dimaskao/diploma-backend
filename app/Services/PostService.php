<?php

namespace App\Services;

use App\Http\Resources\PostResource;
use App\Models\RegularUser;

class PostService
{
    public function getUserSubscriptionsPosts(string $userId, int $page, int $limit): array
    {
        //return RegularUser::find($userId)->subscriptions()->subscription()->with('subscription')->paginate($limit, '*', 'page', $page)->all();
        throw new \Exception("To be implemented");
    }
}
