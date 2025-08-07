<?php

namespace App\Http\Controllers\User;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserPostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with(['blog.user', 'categories'])
            ->where('post_status', 'Active')
            ->whereHas('blog', function ($q) {
                $q->where('blog_status', 'Active');
            })
            ->whereDoesntHave('categories', function ($q) {
                $q->where('category_status', 'InActive');
            })
            ->latest()
            ->paginate(6);

        if ($request->ajax()) {
            return view('user.post.partials.post_list', compact('posts'))->render();
        }

        return view('user.post.index', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::with(['blog.user', 'categories'])
            ->where('id', $id)
            ->where('post_status', 'Active')
            ->whereHas('blog', function ($q) {
                $q->where('blog_status', 'Active');
            })
            ->whereDoesntHave('categories', function ($q) {
                $q->where('category_status', 'InActive');
            })
            ->first();

        if (!$post) {
            return view('user.post.not_found');
        }

        return view('user.post.show', compact('post'));
    }

}
