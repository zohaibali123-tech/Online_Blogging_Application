@extends('layouts.user_layout')

@section('title', $blog->blog_title)

@section('content')
<style>
    .blog-header {
        position: relative;
        height: 300px;
        overflow: hidden;
        border-radius: 15px;
    }

    .blog-header img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(70%);
    }

    .blog-header .overlay-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #fff;
        text-align: center;
    }

    .blog-header .overlay-text h1 {
        font-size: 2.5rem;
        font-weight: bold;
        text-shadow: 2px 2px 5px rgba(0,0,0,0.4);
    }

    .custom-post-card {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        color: white;
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s ease-in-out;
        position: relative;
    }

    .custom-post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.3);
    }

    .custom-post-card img {
        height: 200px;
        width: 100%;
        object-fit: cover;
    }

    .custom-post-card .card-body {
        padding: 15px;
    }

    .custom-post-card .btn-view {
        background-color: #fff;
        color: #000;
        font-weight: bold;
        margin-top: 10px;
    }

    .custom-post-card .btn-view:hover {
        background-color: #f0f0f0;
        color: #000;
    }

    .pagination .page-link {
        color: #000;
    }

    .pagination .page-item.active .page-link {
        background-color: #fff;
        color: #000;
        font-weight: bold;
        border-color: #fff;
    }
</style>

{{-- Alert Placeholder --}}
<div id="alert-message" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

<div class="container mt-5">
    {{-- Blog Header with Title --}}
    <div class="blog-header mb-5 shadow">
        <img src="{{ asset('storage/' . $blog->blog_background_image) }}" alt="Blog Image">
        <div class="overlay-text">
            <h1>{{ $blog->blog_title }}</h1>
            @php
                $isFollowing = \App\Models\BlogFollower::where('follower_id', auth()->id())
                                ->where('blog_following_id', $blog->id)
                                ->where('status', 'Followed')
                                ->exists();
            @endphp

            @auth
                <button class="btn btn-sm btn-light rounded-pill follow-toggle-btn mt-3"
                    data-blog-id="{{ $blog->id }}">
                    {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                </button>
            @endauth

            <p class="mt-2 text-white">
                {{ $blog->followers->count() }} followers
            </p>
        </div>
    </div>

    {{-- Post List --}}
    <div id="post-list" class="row g-4">
        @include('user.blog.partials.post_list')
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        fetchPosts(url);
    });

    function fetchPosts(url) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                $('#post-list').html(data);
                $('html, body').animate({ scrollTop: $("#post-list").offset().top - 100 }, 400);
            },
            error: function() {
                alert('Failed to load posts.');
            }
        });
    }

    // Follow / Unfollow Toggle
    $(document).on('click', '.follow-toggle-btn', function () {
        const blogId = $(this).data('blog-id');
        const btn = $(this);

        $.post(`/user/blog/${blogId}/follow-toggle`, {
            _token: '{{ csrf_token() }}'
        }, function (res) {
            if (res.status === 'followed') {
                btn.text('Unfollow');
                showMessage('success', 'You are now following this blog.');
            } else if (res.status === 'unfollowed') {
                btn.text('Follow');
                showMessage('success', 'You have unfollowed this blog.');
            } else {
                showMessage('danger', 'Something went wrong.');
            }
        }).fail(function () {
            showMessage('danger', 'Failed to send request.');
        });
    });

    // Show toast message
    function showMessage(type, message) {
        const toast = `
            <div class="alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show shadow" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
        $('#alert-message').html(toast);
        setTimeout(() => $('#alert-message .alert').alert('close'), 4000);
    }
</script>
@endpush
