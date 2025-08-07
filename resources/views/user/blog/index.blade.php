@extends('layouts.user_layout')

@section('title', 'All Blogs')

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

{{-- Toast Alert --}}
<div id="alert-message" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

<div class="container mt-5">
    <h2 class="text-center text-white fw-bold mb-4">Explore All Blogs</h2>

    @if(isset($query))
        <div class="text-center text-white mb-4">
            <h5>Search Results for: <em>"{{ $query }}"</em></h5>
        </div>
    @endif

    <div id="blog-list" class="row g-4">
        @include('user.blog.partials.blog_list')
    </div>

    <div class="text-center mt-4">
        <button id="load-more" class="load-more-btn">Load More</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let page = 1;

    $('#load-more').on('click', function () {
        page++;
        $.ajax({
            url: "{{ route('user.blog.index') }}?page=" + page,
            type: "GET",
            beforeSend: function () {
                $('#load-more').text('Loading...');
            },
            success: function (data) {
                if (data.trim() === '') {
                    $('#load-more').text('No More Blogs').prop('disabled', true);
                } else {
                    $('#blog-list').append(data);
                    $('#load-more').text('Load More');
                }
            },
            error: function () {
                alert('Something went wrong.');
                $('#load-more').text('Load More');
            }
        });
    });

    // Follow / Unfollow Toggle Handler
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
                showMessage('danger', 'Something went wrong. Please try again.');
            }
        }).fail(function () {
            showMessage('danger', 'Request failed. Please try again later.');
        });
    });

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
