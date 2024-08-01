<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Services\FeedService;
use Illuminate\Http\Request;

class PostsFeedController extends Controller
{
    public function __construct(private readonly FeedService $feedService) {}

    public function __invoke(Request $request, string $userId)
    {
        return PostResource::collection($this->feedService->getPosts($userId, ...$request->only(['page', 'limit'])));
    }
}
