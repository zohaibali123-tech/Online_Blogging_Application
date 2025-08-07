@extends('layouts.user_layout')

@section('title', 'All Posts')

@section('content')
<style>
    .post-card {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        color: white;
        border-radius: 15px;
        overflow: hidden;
        transition: 0.3s;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    .post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.25);
    }

    .post-card img {
        height: 220px;
        width: 100%;
        object-fit: cover;
    }

    .post-meta {
        font-size: 0.85rem;
        color: #ccc;
    }

    .badge-category, .badge-blog {
        background: #fff;
        color: #2c5364;
        border-radius: 30px;
        font-size: 0.75rem;
        padding: 5px 10px;
        margin-right: 5px;
        font-weight: 600;
    }

    .btn-read-more {
        background: #fff;
        color: #2c5364;
        font-weight: 600;
        border-radius: 30px;
    }

    .btn-read-more:hover {
        background: #e2e2e2;
        color: #1a2a33;
    }

    .btn-load-more {
        background: #fff;
        color: #2c5364;
        font-weight: bold;
    }

    .btn-load-more:hover {
        background: #e2e2e2;
    }
</style>

<div class="container py-5">
    <h2 class="text-center fw-bold mb-5 text-white">Explore All Posts</h2>

    <div id="post-list" class="row g-4">
        @include('user.post.partials.post_list')
    </div>

    <div class="text-center mt-4">
        @if ($posts->hasMorePages())
            <button id="load-more-btn" class="btn btn-load-more px-4 py-2" data-next-page="{{ $posts->currentPage() + 1 }}">Load More</button>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Load More Pagination
    $('#load-more-btn').on('click', function () {
        let page = $(this).data('next-page');
        let btn = $(this);

        $.ajax({
            url: '?page=' + page,
            type: 'GET',
            beforeSend: function () {
                btn.text('Loading...');
            },
            success: function (data) {
                $('#post-list').append(data);
                btn.data('next-page', page + 1);

                if (!data.includes('data-next-page')) {
                    btn.remove();
                } else {
                    btn.text('Load More');
                }
            },
            error: function () {
                btn.text('Try Again');
            }
        });
    });
</script>
@endpush
