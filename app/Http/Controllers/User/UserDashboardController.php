<?php

namespace App\Http\Controllers\User;

use App\Models\Blog;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserDashboardController extends Controller
{
    public function index()
    {
        $sliderBlogs = Blog::where('blog_status', 'Active')
                            ->latest()
                            ->take(6)
                            ->get();

        $recentPosts = Post::with(['blog.user'])
                            ->where('post_status', 'Active')
                            ->whereHas('blog', function ($q) {
                                $q->where('blog_status', 'Active');
                            })
                            ->whereDoesntHave('categories', function ($q) {
                                $q->where('category_status', 'InActive');
                            })
                            ->latest()
                            ->paginate(6);
                        

        $featuredBlogs = Blog::where('blog_status', 'Active')
                            ->inRandomOrder()
                            ->take(8)
                            ->get();

        return view('user.index', compact('sliderBlogs', 'recentPosts', 'featuredBlogs'));
    }
}
