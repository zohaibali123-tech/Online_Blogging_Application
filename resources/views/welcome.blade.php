@extends('layouts.user_layout')

@section('title', 'Landing')

@section('content')

{{-- Hero Section --}}
<section class="py-5 bg-light text-center">
    <div class="container">
        <h1 class="display-5 fw-bold">Welcome to Our Blogging Platform</h1>
        <p class="lead">Discover blogs, explore categories, and connect with the community.</p>
        @auth
          <a href="{{ route('user.index') }}" class="btn btn-success btn-lg mt-3">
            Go to Dashboard
          </a>
        @else
          <a href="{{ route('login') }}" class="btn btn-primary btn-lg mt-3">Get Started</a>
        @endauth
    </div>
</section>

{{-- Blog Section --}}
<section class="py-5">
    <div class="container">
        <h2 class="mb-4 text-center">Featured Blogs</h2>
        <div class="row">
            @foreach($blogs as $blog)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="{{ asset('storage/' . $blog->blog_background_image) }}"
                            class="card-img" alt="Blog Image" style="height: 250px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $blog->blog_title }}</h5>
                            <p class="card-text">{{ $blog->post_per_page }}</p>

                            @guest
                                <button class="btn btn-outline-primary btn-sm show-login-msg">View Blog</button>
                            @else
                                <a href="{{ route('user.blog.show', $blog->id) }}" class="btn btn-outline-primary btn-sm">View Blog</a>
                            @endguest
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{--  Category Section  --}}
<section class="py-5">
    <div class="container">
        <h2 class="mb-4 text-center">Categories</h2>
        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $category->category_title }}</h5>
                            <p class="card-text">{{ Str::limit($category->category_description, 80) }}</p>

                            @guest
                                <button class="btn btn-outline-primary btn-sm show-login-msg">View Posts</button>
                            @else
                                <a href="{{ route('user.categories.show', $category->id) }}" class="btn btn-outline-primary btn-sm">View Posts</a>
                            @endguest
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Feedback Section --}}
<section class="py-5">
    <div class="container">
        <h2 class="mb-4 text-center">Feedback</h2>
        <div class="row">
            @foreach($feedbacks as $feedback)
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted">"{{ $feedback->message }}"</p>
                            <p class="fw-bold mb-0">— {{ $feedback->name }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            @guest
                <button class="btn btn-outline-success show-login-msg">Give Feedback</button>
            @else
                <a href="{{ route('contact.show') }}" class="btn btn-outline-success">Give Feedback</a>
            @endguest
        </div>
    </div>
</section>

@endsection


<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".show-login-msg").forEach(btn => {
            btn.addEventListener("click", function () {
                let alertBox = document.createElement("div");
                alertBox.className = "alert alert-warning alert-dismissible fade show position-fixed top-0 end-0 m-3";
                alertBox.style.zIndex = 9999;
                alertBox.role = "alert";
                alertBox.innerHTML = `
                    <strong>Login Required!</strong> Please login to access this feature.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.body.appendChild(alertBox);
                setTimeout(() => { alertBox.remove(); }, 4000);
            });
        });
    });
</script>
