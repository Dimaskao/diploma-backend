<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function __construct(private readonly PostService $postService) {}

    public function index(Request $request, string $userId)
    {
        $onlyPublicPosts = true;
        if (Gate::allows('viewPrivatePosts', $userId)) {
            $onlyPublicPosts = false;
        }

        return PostResource::collection(
            $this->postService->getUserPosts(
                $userId,
                $request->get('page'),
                $request->get('limit'),
                $onlyPublicPosts
            )
        );
    }
}
