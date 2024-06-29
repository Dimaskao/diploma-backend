<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return PostResource::collection(Post::paginate(15));
    }

    public function store(StorePostRequest $request)
    {
        $postData = $request->only(['title', 'content', 'user_id']);

        try {
            $post = Post::create([
                'title' => $postData['title'],
                'content' => $postData['content'],
                'user_id' => $postData['user_id']
            ]);

            $post->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('postsImages');
            });
        } catch (\Exception $e) {
            throw new HttpResponseException(response()->json([
                'message'      => $e->getMessage()
            ], 400));
        }

        return response()->json(new PostResource($post),201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new PostResource(Post::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $postData = $request->only(['title', 'content']);

        $post = Post::findOrFail($id);
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
    public function destroy($id)
    {
        Post::findOrFail($id)->delete();

        return response()->json(['message' => 'Successfully deleted post']);
    }
}
