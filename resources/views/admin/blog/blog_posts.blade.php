@extends('layouts.admin_layout')

@section('title', $blog->blog_title)

@section('content')
<style>
    .blog-header {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.6)), 
        url('{{ asset("storage/$blog->blog_background_image") }}') no-repeat center center;
        background-size: cover;
        padding: 80px 20px;
        color: white;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    }

    .post-card {
        transition: all 0.3s ease-in-out;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .featured-img {
        height: 180px;
        object-fit: cover;
    }

    .back-btn {
        margin-top: -30px;
        margin-bottom: 20px;
    }
</style>

<div class="container py-4">

    {{-- Back Button --}}
    <div class="back-btn">
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">
            ← Back to All Blogs
        </a>
    </div>

    {{-- Blog Banner --}}
    <div class="blog-header mb-5">
        <h1 class="display-5 fw-bold">{{ $blog->blog_title }}</h1>
        <p class="badge bg-{{ $blog->blog_status == 'Active' ? 'success' : 'secondary' }} fs-6">
            {{ $blog->blog_status }}
        </p>
        <p class="mt-2">
            <span class="badge bg-primary">
                Total Posts: {{ $blog->posts()->count() }}
            </span>
        </p>
    </div>

    {{-- Posts Grid --}}
    <div id="posts-container">
        @include('admin.blog.partials.posts_loop', ['posts' => $posts, 'blog' => $blog])
    </div>
    
</div>
@endsection

@push('scripts')
<script>
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).data('page');

        $.ajax({
            url: "{{ route('admin.blog.show', $blog->id) }}?page=" + page,
            type: "GET",
            success: function(data) {
                $('#posts-container').html(data);
            },
            error: function() {
                alert('Failed to load posts.');
            }
        });
    });
</script>
@endpush