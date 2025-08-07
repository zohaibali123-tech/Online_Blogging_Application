@extends('layouts.user_layout')

@section('title', 'Followed Blogs')

@section('content')
<style>
    .blog-card {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        color: white;
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s ease-in-out;
    }

    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.3);
    }

    .blog-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .blog-card-body {
        padding: 15px;
    }

    .btn-unfollow {
        background-color: #ff4d4d;
        color: white;
        border: none;
    }

    .btn-unfollow:hover {
        background-color: #e60000;
    }

    .load-more-btn {
        background: #fff;
        color: #000;
        font-weight: bold;
        padding: 8px 20px;
        border: none;
        border-radius: 5px;
    }

    .load-more-btn:hover {
        background: #f1f1f1;
    }
</style>

{{-- Alert Placeholder --}}
<div id="alert-message" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

<div class="container mt-5">
    <h2 class="text-white text-center fw-bold mb-4">Your Followed Blogs</h2>

    <div id="followed-blog-list" class="row g-4">
        @include('user.follow.partials.followed_blog_list')
    </div>

    <div class="text-center mt-4">
        <button id="load-more-followed" class="load-more-btn">Load More</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let page = 1;

    function showMessage(type, message) {
        const toast = `
            <div class="alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show shadow" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
        $('#alert-message').html(toast);
        setTimeout(() => $('#alert-message .alert').alert('close'), 4000);
    }

    // Load More Pagination
    $('#load-more-followed').on('click', function () {
        page++;
        $.ajax({
            url: "{{ route('user.followed.blogs') }}?page=" + page,
            type: "GET",
            beforeSend: function () {
                $('#load-more-followed').text('Loading...');
            },
            success: function (data) {
                if (data.trim() === '') {
                    $('#load-more-followed').text('No More Blogs').prop('disabled', true);
                } else {
                    $('#followed-blog-list').append(data);
                    $('#load-more-followed').text('Load More');
                }
            },
            error: function () {
                showMessage('error', 'Something went wrong while loading more.');
                $('#load-more-followed').text('Load More');
            }
        });
    });

    $(document).on('click', '.btn-unfollow', function () {
        const blogId = $(this).data('blog-id');
        const card = $(this).closest('.col-md-4');

        $.post(`/user/blog/${blogId}/follow-toggle`, {
            _token: '{{ csrf_token() }}'
        }, function (res) {
            if (res.status === 'unfollowed') {
                card.fadeOut(400, function () {
                    $(this).remove();
                });
                showMessage('success', 'Unfollowed successfully!');
            } else {
                showMessage('danger', 'Something went wrong.');
            }
        });
    });
</script>
@endpush
