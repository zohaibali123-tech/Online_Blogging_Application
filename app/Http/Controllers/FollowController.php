<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogFollower;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(Request $request, $blogId)
    {
        $userId = auth()->id();

        $existing = BlogFollower::where('follower_id', $userId)
                    ->where('blog_following_id', $blogId)
                    ->first();

        if ($existing) {
            if ($existing->status === 'Followed') {
                $existing->update(['status' => 'Unfollowed']);
                return response()->json(['status' => 'unfollowed']);
            } else {
                $existing->update(['status' => 'Followed']);
                return response()->json(['status' => 'followed']);
            }
        } else {
            BlogFollower::create([
                'follower_id' => $userId,
                'blog_following_id' => $blogId,
                'status' => 'Followed',
            ]);

            return response()->json(['status' => 'followed']);
        }
    }

    public function followedBlogs()
    {
        $followedBlogs = Blog::whereHas('followers', function ($q) {
            $q->where('follower_id', auth()->id())->where('status', 'Followed');
        })->with(['user', 'followers'])->paginate(6);

        if (request()->ajax()) {
            return view('user.follow.partials.followed_blog_list', compact('followedBlogs'))->render();
        }

        return view('user.follow.followed_blogs', compact('followedBlogs'));
    }

}
