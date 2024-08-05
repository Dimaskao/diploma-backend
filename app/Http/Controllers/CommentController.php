<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function index($postId, Request $request)
    {
        return Comment::where('post_id', $postId)->paginate($request->get('limit') ?? 15);
    }

    public function store(StoreCommentRequest $request)
    {
        $comment = Comment::create([
            'text'    => $request->get('text'),
            'post_id' => $request->get('post_id'),
            'user_id' => $request->get('user_id'),
        ]);

        return response()->json($comment, 201);
    }

    public function show(Comment $comment)
    {
        return response()->json($comment);
    }

    public function update(Request $request, Comment $comment)
    {
        if (! Gate::allows('update', $comment)) {
            abort(403);
        }

        $comment->update($request->only(['text']));

        return response()->json($comment->refresh());
    }

    public function destroy(Comment $comment)
    {
        if (! Gate::allows('delete', $comment)) {
            abort(403);
        }

        $comment->delete();

        return response()->json(['message' => 'Successfully deleted comment']);
    }
}
