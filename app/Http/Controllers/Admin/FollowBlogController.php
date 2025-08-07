<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogFollower;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FollowBlogController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $status = $request->input('status');

        $logs = BlogFollower::with(['follower', 'blog'])
            ->when($query, function ($q) use ($query) {
                $q->whereHas('follower', function ($q1) use ($query) {
                    $q1->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%");
                })->orWhereHas('blog', function ($q2) use ($query) {
                    $q2->where('blog_title', 'like', "%{$query}%");
                });
            })
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return view('admin.follow.partials.follow_blog_list', compact('logs'))->render();
        }

        return view('admin.follow.index', compact('logs'));
    }
}
