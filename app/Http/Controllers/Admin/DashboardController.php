<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBlogs = Blog::count();
        $totalCategories = Category::count();
        $totalPosts = Post::count();
        $totalUsers = User::where('role_id', '!=', 1)->count();

        $approvedUsers = User::where('is_approved', 'approved')->count();
        $pendingUsers = User::where('is_approved', 'pending')->count();
        $rejectedUsers = User::where('is_approved', 'rejected')->count();

        $recentPosts = Post::latest()
                        ->with('blog.user')
                        ->take(3)
                        ->get();

        $monthlyUserCounts = User::selectRaw("MONTH(created_at) as month, COUNT(*) as total")
                        ->groupByRaw('MONTH(created_at)')
                        ->pluck('total', 'month')
                        ->toArray();
        
        $monthlyUserLabels = array_map(function($month) {
            return date('M', mktime(0, 0, 0, $month, 1));
        }, array_keys($monthlyUserCounts));

        return view('admin.dashboard', compact(
            'totalBlogs', 'totalCategories', 'totalPosts', 'totalUsers',
            'approvedUsers', 'pendingUsers', 'rejectedUsers',
            'recentPosts', 'monthlyUserCounts','monthlyUserLabels'
        ));
    }
}
