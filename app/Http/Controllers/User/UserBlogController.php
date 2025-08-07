<?php

namespace App\Http\Controllers\User;

use App\Models\Blog;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserBlogController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $blogs = Blog::where('blog_status', 'Active')
                        ->latest()
                        ->paginate(6);
            return view('user.blog.partials.blog_list', compact('blogs'))->render();
        }

        $blogs = Blog::where('blog_status', 'Active')
                    ->latest()
                    ->paginate(6);

        return view('user.blog.index', compact('blogs'));
    }

    public function show($id)
    {
        $blog = Blog::with('user')->findOrFail($id);

        if (request()->ajax()) {
            $posts = Post::where('blog_id', $blog->id)
                        ->with(['blog.user'])
                        ->latest()
                        ->paginate($blog->post_per_page);

            return view('user.blog.partials.post_list', compact('posts'))->render();
        }

        $posts = Post::where('blog_id', $blog->id)
                    ->with(['blog.user'])
                    ->latest()
                    ->paginate($blog->post_per_page);

        return view('user.blog.show', compact('blog', 'posts'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $blogs = Blog::where('blog_status', 'Active')
            ->where(function ($q) use ($query) {
                $q->where('blog_title', 'LIKE', "%$query%")
                ->orWhereHas('user', function ($q2) use ($query) {
                    $q2->where('first_name', 'LIKE', "%$query%")
                        ->orWhere('last_name', 'LIKE', "%$query%");
                });
            })
            ->latest()
            ->paginate(6);

        return view('user.blog.index', compact('blogs', 'query'));
    }

}
