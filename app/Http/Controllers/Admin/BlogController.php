<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Blog::query();

        if ($request->filled('search')) {
            $query->where('blog_title', 'like', '%' . $request->search . '%');
        }

        $blogs = $query->orderBy('created_at', 'desc')->paginate(6);

        if ($request->ajax()) {
            return view('admin.blog.partials.blog_cards', compact('blogs'))->render();
        }

        return view('admin.blog.index', compact('blogs'));
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
        $validator = \Validator::make($request->all(), [
            'blog_title' => 'required|string|max:100',
            'post_per_page' => 'required|integer|min:1',
            'blog_background_image' => 'nullable|image|mimes:jpeg,png,jpg',
            'blog_status' => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('blog_background_image')) {
            $imagePath = $request->file('blog_background_image')->store('blogs', 'public');
        }

        $blog = Blog::create([
            'blog_title' => ucfirst($request->blog_title),
            'post_per_page' => $request->post_per_page,
            'blog_background_image' => $imagePath,
            'blog_status' => $request->blog_status,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Blog created successfully!',
            'data' => $blog
        ]);
    }

    /**
     * Display the specified resource.
     */

    public function show(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $posts = Post::where('blog_id', $blog->id)
                    ->where('post_status', 1)
                    ->latest()
                    ->paginate($blog->post_per_page);
     
        if ($request->ajax()) {
            return view('admin.blog.partials.posts_loop', compact('posts', 'blog'))->render();
        }
     
        return view('admin.blog.blog_posts', compact('blog', 'posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $blog
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'blog_title' => 'required|string|max:100',
            'post_per_page' => 'required|integer|min:1',
            'blog_background_image' => 'nullable|image|mimes:jpeg,png,jpg',
            'blog_status' => 'required|in:Active,Inactive',
        ]);

        $blog = Blog::findOrFail($id);

        if ($request->hasFile('blog_background_image')) {
            if ($blog->blog_background_image && \Storage::disk('public')->exists($blog->blog_background_image)) {
                \Storage::disk('public')->delete($blog->blog_background_image);
            }

            $imagePath = $request->file('blog_background_image')->store('blogs', 'public');
            $blog->blog_background_image = $imagePath;
        }

        $blog->update([
            'blog_title' => ucfirst($request->blog_title),
            'post_per_page' => $request->post_per_page,
            'blog_status' => $request->blog_status,
            'blog_background_image' => $blog->blog_background_image,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Blog updated successfully!',
            'data' => $blog
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->blog_background_image) {
            \Storage::disk('public')->delete($blog->blog_background_image);
        }

        $blog->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog deleted successfully!'
        ]);
    }

    public function toggle($id)
    {
        $blog = Blog::findOrFail($id);
        $newStatus = $blog->blog_status === 'Active' ? 'InActive' : 'Active';
        $blog->blog_status = $newStatus;
        $blog->save();

        return response()->json([
            'status' => 'success',
            'message' => "Blog status updated to {$newStatus}.",
            'new_status' => $newStatus
        ]);
    }
}
