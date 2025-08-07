<?php

namespace App\Http\Controllers\User;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('posts')
                        ->where('category_status', 'Active')
                        ->latest()
                        ->paginate(6);

        if ($request->ajax()) {
            return view('user.category.partials.category_list', compact('categories'))->render();
        }

        return view('user.category.index', compact('categories'));
    }

    public function show(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $posts = $category->posts()
                        ->with(['blog.user'])
                        ->where('post_status', 'Active')
                        ->latest()
                        ->paginate(6);

        if ($request->ajax()) {
            return view('user.category.partials.post_list', compact('posts'))->render();
        }

        return view('user.category.show', compact('category', 'posts'));
    }

}
