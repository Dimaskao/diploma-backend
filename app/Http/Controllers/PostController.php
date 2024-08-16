<?php

namespace App\Http\Controllers;

use App\Enums\PostStatus;
use App\Enums\PostVisibility;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index(Request $request)
    {
        return PostResource::collection(
            Post::where('status', PostStatus::Published->value)
                ->where('visibility', PostVisibility::Public->value)
                ->paginate($request->get('limit') ?? 15)
        );
    }

    public function store(StorePostRequest $request)
    {
        $postData = $request->only(['title', 'content', 'user_id', 'status', 'visibility']);

        try {
            $post = Post::create([
                'title'      => $postData['title'],
                'content'    => $postData['content'],
                'user_id'    => $postData['user_id'],
                'status'     => $postData['status'],
                'visibility' => $postData['visibility'],
            ]);

            if ($request->has('images')) {
                $post->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('postsImages');
                });
            }
        } catch (\Exception $e) {
            throw new HttpResponseException(response()->json([
                'message' => $e->getMessage() . PHP_EOL . $e->getFile() . PHP_EOL . $e->getLine(),
            ], 400));
        }

        return response()->json(new PostResource($post), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if (! Gate::allows('view', $post)) {
            abort(403);
        }

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        if (! Gate::allows('update', $post)) {
            abort(403);
        }

        $postData = $request->only(['title', 'content', 'status', 'visibility']);

        $post->update($postData);

        if ($request->has('images')) {
            $post->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('postsImages');
            });
        }

        collect($request->get('images_to_remove'))->each(function ($mediaId) use ($post) {
            $post->deleteMedia($mediaId);
        });

        return response()->json(new PostResource($post->refresh()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (! Gate::allows('delete', $post)) {
            abort(403);
        }

        $post->delete();

        return response()->json(['message' => 'Successfully deleted post']);
    }
}
