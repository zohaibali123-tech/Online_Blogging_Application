<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'post_id' => $request->post_id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'is_active' => 1
        ]);        

        return response()->json([
            'status' => 'success',
            'message' => 'Comment added successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if (auth()->id() !== $comment->user_id && auth()->user()->role_id != 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized.'
            ], 403);
        }

        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment->comment = $request->comment;
        $comment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $comment = Comment::find($request->id);

        if (!$comment) {
            return response()->json(['status' => 'error', 'message' => 'Comment not found.'], 404);
        }

        $user = auth()->user();

        if ($user->role_id == 1 || $user->id == $comment->user_id) {
            $comment->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Comment deleted successfully.'
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 403);
    }

    public function toggle($id)
    {
        $user = auth()->user();

        if ($user->role_id !== 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $comment = Comment::findOrFail($id);
        $comment->is_active = $comment->is_active === 'Active' ? 'InActive' : 'Active';
        $comment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment status updated to ' . $comment->is_active . '.'
        ]);
    }

    public function getByPost(Request $request, $postId)
    {
        $comments = Comment::with('user')
            ->where('post_id', $postId)
            ->orderBy('created_at', 'desc')
            ->paginate(3);

        return view('admin.post.partials.comment_list', compact('comments'))->render();
    }
}
