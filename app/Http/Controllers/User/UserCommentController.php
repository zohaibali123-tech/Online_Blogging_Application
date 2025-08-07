<?php

namespace App\Http\Controllers\User;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserCommentController extends Controller
{
    public function fetch(Request $request, $postId)
    {
        $comments = Comment::with('user')
            ->where('post_id', $postId)
            ->where('is_active', 'Active')
            ->latest()
            ->paginate(5);

        return view('user.post.partials.comment_list', compact('comments'))->render();
    }

    public function store(Request $request, $postId)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $post = \App\Models\Post::findOrFail($postId);

        if (!$post->is_comment_allowed) {
            return response()->json(['error' => 'Commenting is disabled for this post.'], 403);
        }

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $postId,
            'comment' => $request->comment,
        ]);

        return response()->json(['success' => true, 'message' => 'Comment added successfully.']);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $comment->update([
            'comment' => $request->comment,
        ]);

        return response()->json(['success' => true, 'message' => 'Comment updated successfully.']);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true, 'message' => 'Comment deleted successfully.']);
    }
}
