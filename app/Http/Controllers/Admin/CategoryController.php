<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::withCount('posts')->latest();

        if ($request->has('search') && $request->search != '') {
            $query->where('category_title', 'LIKE', '%' . $request->search . '%');
        }

        $categories = $query->paginate(6);

        if ($request->ajax()) {
            return view('admin.category.partials.category_cards', compact('categories'))->render();
        }

        return view('admin.category.category', compact('categories'));
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
        $validator = Validator::make($request->all(), [
            'category_title' => 'required|string|max:100',
            'category_description' => 'nullable|string|max:255',
            'category_status' => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Category::create([
            'category_title' => ucfirst($request->category_title),
            'category_description' => $request->category_description,
            'category_status' => $request->category_status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully!',
            'data' => $category
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::with('posts')->findOrFail($id);
        $posts = $category->posts()->latest()->paginate(3);

        if (request()->ajax()) {
            return view('admin.category.partials.post_loop', compact('posts'))->render();
        }

        return view('admin.category.category_post', compact('category', 'posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'category_title' => 'required|string|max:100',
            'category_description' => 'nullable|string|max:255',
            'category_status' => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Category::findOrFail($id);

        $category->update([
            'category_title' => ucfirst($request->category_title),
            'category_description' => $request->category_description,
            'category_status' => $request->category_status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully!',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully!',
        ]);
    }

    public function toggle($id)
    {
        $category = Category::findOrFail($id);
        $newStatus = $category->category_status === 'Active' ? 'InActive' : 'Active';
        $category->category_status = $newStatus;
        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => "Category status updated to {$newStatus}.",
            'new_status' => $newStatus
        ]);
    }
}
