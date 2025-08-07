<?php

// Modals
use App\Models\Blog;
use App\Models\Setting;
use App\Models\Category;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Route;

// Global Routes Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedbackController;

// Admin Routes Controllers
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FollowBlogController;

// User Routes Controllers
use App\Http\Controllers\User\UserBlogController;
use App\Http\Controllers\User\UserPostController;
use App\Http\Controllers\User\UsersController;
use App\Http\Controllers\User\UserCommentController;
use App\Http\Controllers\User\UserCategoryController;
use App\Http\Controllers\User\UserDashboardController;

// Landing Page
Route::get('/', function () {
    $navbarCategories = Category::where('category_status', 1)->take(5)->get();
    $siteSetting = Setting::first();
    $blogs = Blog::where('blog_status', 1)->latest()->take(6)->get();
    $categories = Category::where('category_status', 1)->latest()->take(6)->get();
    $feedbacks = ContactMessage::latest()->take(6)->get();

    return view('welcome', compact('navbarCategories', 'siteSetting', 'blogs', 'categories', 'feedbacks'));
})->name('welcome');

// Login Page
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Login Submit
Route::post('/login', [AuthController::class, 'login']);

// Register Page 
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Register Submit
Route::post('/register', [AuthController::class, 'register']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:Admin'])->name('admin.')->group(function () {
    // Dashboard Routes
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Blog Routes
    Route::resource('blog', BlogController::class);
    Route::patch('blog/{id}/toggle', [BlogController::class, 'toggle'])->name('blog.toggle');
     // Category Routes
    Route::resource('category', CategoryController::class);
    Route::patch('category/{id}/toggle', [CategoryController::class, 'toggle'])->name('category.toggle');
    // Post Routes
    Route::resource('post', PostController::class);
    Route::patch('post/{id}/toggle', [PostController::class, 'toggle'])->name('post.toggle');
    // User Routes
    Route::get('user/approved', [UserController::class, 'approved'])->name('user.approved');
    Route::get('user/pending', [UserController::class, 'pending'])->name('user.pending');
    Route::get('user/rejected', [UserController::class, 'rejected'])->name('user.rejected');
    Route::resource('user', UserController::class);
    Route::patch('user/{id}/toggle', [UserController::class, 'toggleStatus'])->name('user.toggle');
    Route::patch('user/{id}/approve', [UserController::class, 'approve'])->name('user.approve');
    Route::get('user/{id}/profile', [UserController::class, 'showProfile'])->where('id', '[0-9]+')->name('user.profile');
    // Comment Routes
    Route::resource('comment', CommentController::class)->only(['store', 'update', 'destroy']);
    Route::patch('comment/{id}/toggle', [CommentController::class, 'toggle'])->name('comment.toggle');
    Route::get('comment/post/{postId}', [CommentController::class, 'getByPost'])->name('comment.byPost');
    // Follow/UnFollow Routes
    Route::get('follow-logs', [FollowBlogController::class, 'index'])->name('follow.blog');
    // Setting Routes
    Route::resource('settings', SettingController::class)->only(['index', 'store', 'update']);
    // Contact Messages Routes
    Route::get('contact_messages', [ContactMessageController::class, 'index'])->name('contact.index');
    Route::post('contact/reply', [ContactMessageController::class, 'reply'])->name('contact.reply');
    Route::delete('contact/{id}', [ContactMessageController::class, 'destroy'])->name('contact.destroy');
});

// User Routes
Route::prefix('user')->middleware(['auth', 'role:User'])->name('user.')->group(function () {
    // Dashboard Routes
    Route::get('index', [UserDashboardController::class, 'index'])->name('index');
    // Blogs Routes
    Route::get('blog', [UserBlogController::class, 'index'])->name('blog.index');
    Route::get('blog/{id}', [UserBlogController::class, 'show'])->name('blog.show');
    Route::get('search', [UserBlogController::class, 'search'])->name('blog.search');
    // Category Routes
    Route::get('categories', [UserCategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/{id}', [UserCategoryController::class, 'show'])->name('categories.show');
    // Posts Routes
    Route::get('post', [UserPostController::class, 'index'])->name('post.index');
    Route::get('post/{id}', [UserPostController::class, 'show'])->name('post.show');
    // Comments Routes
    Route::post('post/{post}/comment', [UserCommentController::class, 'store'])->name('comment.store');
    Route::put('comment/{id}', [UserCommentController::class, 'update'])->name('comment.update');
    Route::delete('comment/{id}', [UserCommentController::class, 'destroy'])->name('comment.destroy');
    Route::get('post/{post}/comments', [UserCommentController::class, 'fetch'])->name('comment.fetch');
    // Profile Routes
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    // Follow/Unfollow Routes
    Route::post('/blog/{id}/follow-toggle', [FollowController::class, 'toggle'])->name('blog.follow.toggle');
    Route::get('followed-blogs', [FollowController::class, 'followedBlogs'])->name('followed.blogs');
    // Light/Dark Mode Routes
    Route::post('toggle-theme', [UsersController::class, 'toggleTheme'])->name('toggleTheme');
});

// About Us
Route::get('/about', function () {
    return view('about');
})->name('about');

// Contact Form
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');

