<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\PostAttachment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(['blog', 'categories', 'attachments'])->latest();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('post_title', 'like', '%' . $searchTerm . '%');
        }

        $posts = $query->paginate(3)->appends(['search' => $request->search]);

        return view('admin.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $blogs = \App\Models\Blog::where('blog_status', 'Active')->get();
        $categories = \App\Models\Category::where('category_status', 'Active')->get();

        return view('admin.post.create', compact('blogs', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blog_id' => 'required|exists:blogs,id',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',

            'post_title' => 'required|string|max:150',
            'post_summary' => 'required|string|max:255',
            'post_discription' => 'required|string',
            'post_status' => 'required|in:Active,Inactive',
            'is_comment_allowed' => 'required|boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'post_attachment_title.*' => 'nullable|string|max:150',
            'post_attachment_path.*' => 'nullable|file|max:5120', // 5MB limit
            'post_attachment_status.*' => 'nullable|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle post image
        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('post_images', 'public');
        }

        $post = Post::create([
            'blog_id' => $request->blog_id,
            'post_title' => $request->post_title,
            'post_summary' => $request->post_summary,
            'post_discription' => $request->post_discription,
            'post_status' => $request->post_status,
            'is_comment_allowed' => $request->is_comment_allowed,
            'featured_image' => $imagePath,
        ]);

        // Pivot Table
        $post->categories()->sync($request->category_ids);

        // Attachments
        if ($request->has('post_attachment_title') && $request->hasFile('post_attachment_file')) {
            foreach ($request->post_attachment_title as $index => $title) {
                $file = $request->file('post_attachment_file')[$index] ?? null;
                if ($file) {
                    $filePath = $file->store('post_attachments', 'public');
                    PostAttachment::create([
                        'post_id' => $post->id,
                        'post_attachment_title' => $title,
                        'post_attachment_path' => $filePath,
                        'is_active' => $request->post_attachment_status[$index] ?? 'Active', // default to Active
                    ]);
                }
            }
        }

        return redirect()->route('admin.post.index')->with('success', 'Post created successfully! ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with(['blog', 'categories', 'attachments'])->findOrFail($id);
        return view('admin.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post = Post::with(['categories', 'attachments'])->findOrFail($id);
        $blogs = Blog::all();
        $categories = Category::all();

        return view('admin.post.edit', compact('post', 'blogs', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'blog_id' => 'required|exists:blogs,id',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',

            'post_title' => 'required|string|max:150',
            'post_summary' => 'required|string|max:255',
            'post_discription' => 'required|string',
            'post_status' => 'required|in:Active,InActive',
            'is_comment_allowed' => 'required|boolean',
            'featured_image' => 'nullable|image|max:2048',

            'post_attachment_title.*' => 'nullable|string|max:150',
            'post_attachment_file.*' => 'nullable|file|max:5120',
            'post_attachment_status.*' => 'nullable|in:Active,InActive',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Delete Attachments
        if ($request->has('delete_attachment_ids')) {
            foreach ($request->delete_attachment_ids as $deleteId) {
                if ($deleteId) {
                    $attachment = PostAttachment::find($deleteId);
                    if ($attachment) {
                        Storage::disk('public')->delete($attachment->post_attachment_path);
                        $attachment->delete();
                    }
                }
            }
        }        

        // New Featured Image
        $imagePath = $post->featured_image;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('post_images', 'public');
        }

        $post->update([
            'blog_id' => $request->blog_id,
            'post_title' => $request->post_title,
            'post_summary' => $request->post_summary,
            'post_discription' => $request->post_discription,
            'post_status' => $request->post_status,
            'is_comment_allowed' => $request->is_comment_allowed,
            'featured_image' => $imagePath,
        ]);

        $post->categories()->sync($request->category_ids);

        // Add New Attachments
        if ($request->has('post_attachment_title')) {
            foreach ($request->post_attachment_title as $index => $title) {
                $file = $request->file('post_attachment_file')[$index] ?? null;
                if ($file) {
                    $filePath = $file->store('post_attachments', 'public');
                    PostAttachment::create([
                        'post_id' => $post->id,
                        'post_attachment_title' => $title,
                        'post_attachment_path' => $filePath,
                        'is_active' => $request->post_attachment_status[$index] ?? 'Active',
                    ]);
                }
            }
        }

        return redirect()->route('admin.post.index')->with('success', 'Post updated successfully! ');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        // Delete Featured Image
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        // Delete Attachments To
        foreach ($post->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->post_attachment_path);
            $attachment->delete();
        }

        $post->categories()->detach();
        $post->delete();

        return response()->json(['status' => 'success', 'message' => 'Post deleted successfully']);
    }

    public function toggle($id)
    {
        $post = Post::findOrFail($id);
        $newStatus = $post->post_status === 'Active' ? 'InActive' : 'Active';
        $post->post_status = $newStatus;
        $post->save();

        return response()->json([
            'status' => 'success',
            'message' => "Category status updated to {$newStatus}.",
            'new_status' => $newStatus
        ]);
    }
}
