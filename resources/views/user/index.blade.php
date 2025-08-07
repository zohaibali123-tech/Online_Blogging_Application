@extends('layouts.user_layout')

@section('title', 'Welcome')

@section('content')
<style>
    .slider-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        overflow: hidden;
        border-radius: 12px;
    }

    .carousel-inner img {
        object-fit: cover;
        height: 300px;
        border-radius: 12px;
    }

    .slider-caption {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        z-index: 10;
        max-width: 600px;
        text-align: center;
    }

    .slider-caption h1 {
        font-weight: bold;
        font-size: 2.5rem;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);
    }

    .slider-caption p {
        font-size: 1.1rem;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
    }

    .btn-learn-more {
        background-color: #ffffffcc;
        color: #333;
        font-weight: 600;
        border: none;
        padding: 10px 20px;
        font-size: 1rem;
        border-radius: 6px;
    }

    .btn-learn-more:hover {
        background-color: #fff;
        color: #000;
    }

    .recent-posts-section {
        background: transparent;
        padding: 60px 0 30px;
    }

    .card.custom-post-card {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        backdrop-filter: blur(10px);
        color: #fff;
        border: none;
    }

    .card.custom-post-card .card-title,
    .card.custom-post-card .card-text,
    .card.custom-post-card .text-muted {
        color: #fff;
    }

    .card.custom-post-card:hover {
        transform: translateY(-5px);
        transition: 0.3s ease-in-out;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .horizontal-slider-section {
        padding: 60px 0 30px;
        background: transparent;
    }

    .horizontal-scroll-wrapper {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding-bottom: 10px;
    }

    .horizontal-scroll-wrapper::-webkit-scrollbar {
        height: 8px;
    }

    .horizontal-scroll-wrapper::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.3);
        border-radius: 10px;
    }

    .blog-card {
        flex: 0 0 auto;
        width: 300px;
        margin-right: 20px;
        background: linear-gradient(135deg, #0f2027, #2c5364);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        overflow: hidden;
        color: #fff;
        transition: transform 0.3s ease;
    }

    .blog-card:hover {
        transform: scale(1.05);
    }

    .blog-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .blog-card-body {
        padding: 15px;
    }

    .blog-card-body h5 {
        font-size: 1.1rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .blog-card-body p {
        font-size: 0.95rem;
    }

    .post-meta {
        color: #e0e0e0;
        font-size: 0.85rem;
    }
</style>

{{-- Main Slider --}}
<div class="container-fluid px-0">
    <div class="slider-wrapper">
        <div id="mainSlider" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner rounded">

                @foreach($sliderBlogs as $index => $blog)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $blog->blog_background_image) }}" class="d-block w-100" alt="{{ $blog->blog_title }}">
                        <div class="slider-caption">
                            <h1>{{ $blog->blog_title }}</h1>
                            <p><strong>Post Per Page</strong> {{ $blog->post_per_page  }} </p>
                            <a href="{{ route('user.blog.show', $blog->id) }}" class="btn btn-learn-more mt-2">Read Blog</a>
                        </div>
                    </div>
                @endforeach

            </div>

            {{-- Carousel Controls --}}
            <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
</div>

{{-- Recent Posts --}}
<div class="recent-posts-section">
    <div class="container">
        <h2 class="mb-5 text-center fw-bold text-white">Recent Posts</h2>
        <div class="row g-4">
            @forelse($recentPosts as $post)
                <div class="col-md-4">    
                    <div class="card h-100 shadow-sm custom-post-card">
                        <a href="{{ route('user.post.show', $post->id) }}" class="text-decoration-none">
                            <img src="{{ asset('storage/' . ($post->featured_image ?? 'default-post.jpg')) }}" class="card-img-top" alt="Post Image" style="height: 200px; object-fit: cover;">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->post_title }}</h5>
                            <p class="card-text">{{ Str::limit($post->post_discription, 100) }}</p>
                            {{-- Collapsible Attachments (Active Only) --}}
                            @if($post->attachments->where('is_active', 'Active')->count())
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-primary w-100 text-start" data-bs-toggle="collapse" data-bs-target="#attachments-{{ $post->id }}">
                                        <i class="bi bi-paperclip"></i> Show Attachments
                                    </button>
                                    <div class="collapse mt-2" id="attachments-{{ $post->id }}">
                                        <ul class="list-group list-group-flush">
                                            @foreach($post->attachments->where('is_active', 'Active') as $att)
                                                @php
                                                    $ext = pathinfo($att->post_attachment_path, PATHINFO_EXTENSION);
                                                    $icons = [
                                                        'pdf' => 'bi-file-earmark-pdf',
                                                        'doc' => 'bi-file-earmark-word',
                                                        'docx' => 'bi-file-earmark-word',
                                                        'xls' => 'bi-file-earmark-excel',
                                                        'xlsx' => 'bi-file-earmark-excel',
                                                        'png' => 'bi-file-earmark-image',
                                                        'jpg' => 'bi-file-earmark-image',
                                                        'jpeg' => 'bi-file-earmark-image',
                                                        'gif' => 'bi-file-earmark-image',
                                                    ];
                                                    $icon = $icons[strtolower($ext)] ?? 'bi-file-earmark';
                                                @endphp
                                                <li class="list-group-item d-flex align-items-center small">
                                                    <i class="bi {{ $icon }} me-2 text-primary"></i>
                                                    <a href="{{ asset('storage/' . $att->post_attachment_path) }}" target="_blank" class="text-decoration-underline">
                                                        {{ $att->post_attachment_title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <small class="post-meta">
                                By {{ $post->blog->user->first_name ?? 'Unknown' }}
                                | {{ $post->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-white">
                    <p>No posts available.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-5 d-flex justify-content-center">
            @if ($recentPosts->lastPage() > 1)
                <nav>
                    <ul class="pagination">
                        {{-- Previous Page --}}
                        <li class="page-item {{ $recentPosts->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $recentPosts->previousPageUrl() }}">Previous</a>
                        </li>

                        {{-- Page Numbers --}}
                        @for ($i = 1; $i <= $recentPosts->lastPage(); $i++)
                            <li class="page-item {{ $i == $recentPosts->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $recentPosts->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Next Page --}}
                        <li class="page-item {{ $recentPosts->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $recentPosts->nextPageUrl() }}">Next</a>
                        </li>
                    </ul>
                </nav>
            @endif
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('user.post.index') }}" class="btn btn-light px-4 py-2 fw-semibold">See All Posts</a>
        </div>
    </div>
</div>

{{-- Horizontal Scroll Slider --}}
<div class="horizontal-slider-section">
    <div class="container">
        <h2 class="mb-4 text-white fw-bold text-center">Featured Blog Highlights</h2>
        <div class="horizontal-scroll-wrapper">
            @forelse ($featuredBlogs as $blog)
                <a href="{{ route('user.blog.show', $blog->id) }}" class="text-decoration-none">
                    <div class="blog-card shadow-sm">
                        <img src="{{ asset('storage/' . $blog->blog_background_image) }}" alt="Blog Image">
                        <div class="blog-card-body">
                            <h5>{{ Str::limit($blog->blog_title, 40) }}</h5>
                            <small class="post-meta">
                                By {{ $blog->user->first_name ?? 'Unknown' }}
                                | {{ $blog->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-white">No featured blogs found.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
