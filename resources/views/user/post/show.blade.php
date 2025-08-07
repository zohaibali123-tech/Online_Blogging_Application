@extends('layouts.user_layout')

@section('title', $post->post_title)

@section('content')
<style>
    .badge-category {
        background: #0f2027;
        color: white;
        font-size: 0.7rem;
        padding: 5px 12px;
        border-radius: 30px;
        margin: 2px;
        display: inline-block;
    }

    .comment-section {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.07);
    }

    .comment {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .btn-load-more-comments {
        background: #fff;
        color: #0f2027;
        border-radius: 30px;
        padding: 6px 20px;
        font-weight: 600;
        border: 1px solid #ccc;
    }

    .btn-load-more-comments:hover {
        background-color: #f0f0f0;
    }
</style>

{{-- Toast Alert --}}
<div id="alert-message" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

{{-- Post Card --}}
<div class="container mt-5 mb-5">
    <div class="card shadow-lg rounded-4 overflow-hidden text-white" style="background: linear-gradient(135deg, #0f2027, #2c5364);">
        <div class="row g-0">
            {{-- Image --}}
            <div class="col-md-6">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Post Image" class="img-fluid w-100 h-100" style="object-fit: cover;">
            </div>

            {{-- Details --}}
            <div class="col-md-6 p-4 d-flex flex-column justify-content-between">
                <div>
                    <h2 class="fw-bold">{{ $post->post_title }}</h2>

                    <p class="fst-italic mt-2 mb-1 text-light">
                        <small>
                            By <strong>{{ optional($post->blog->user)->first_name . ' ' . optional($post->blog->user)->last_name ?? 'Unknown' }}</strong> • {{ $post->created_at->format('F j, Y') }}
                        </small>
                    </p>

                    <div class="mb-3">
                        <span class="badge bg-warning text-dark me-2">Blog: {{ $post->blog->blog_title }}</span>
                        @foreach ($post->categories as $category)
                            <span class="badge bg-light text-dark me-1">Category: {{ $category->category_title }}</span>
                        @endforeach
                    </div>

                    @if ($post->post_summary)
                        <p class="text-white-50">{{ Str::limit(strip_tags($post->post_summary), 150) }}</p>
                    @endif

                    <p class="mt-3">
                        <i class="bi bi-chat-dots"></i> 
                        {{ $post->comments->count() }} {{ Str::plural('comment', $post->comments->count()) }}
                    </p>
                </div>

                <div class="text-end mt-3">
                    <a href="#comment-form" class="btn btn-light btn-sm rounded-pill px-4">Comment Now</a>
                </div>
            </div>
        </div>

        {{-- Description inside post card --}}
        <div class="p-4 bg-white text-dark">
            {!! $post->post_discription !!}
        </div>

        {{-- Post Attachments --}}
        @if ($post->attachments->where('is_active', 'Active')->count())
            <div class="container mt-4">
                <div class="accordion" id="attachmentAccordion">
                    <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden">
                        <h2 class="accordion-header" id="attachHeading">
                            <button class="accordion-button collapsed bg-gradient text-dark fw-semibold" type="button" 
                                    style="background: linear-gradient(135deg, #f0f2f5, #d9e2ec); border-radius: 0;"
                                    data-bs-toggle="collapse" data-bs-target="#attachCollapse" 
                                    aria-expanded="false" aria-controls="attachCollapse">
                                📎 Attachments ({{ $post->attachments->where('is_active', 'Active')->count() }})
                            </button>
                        </h2>
                        <div id="attachCollapse" class="accordion-collapse collapse" 
                            aria-labelledby="attachHeading" data-bs-parent="#attachmentAccordion">
                            <div class="accordion-body p-0 bg-light rounded-bottom-4">
                                <ul class="list-group list-group-flush">
                                    @foreach ($post->attachments->where('is_active', 'Active') as $att)
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3 bg-white">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-paperclip text-primary"></i>
                                                <a href="{{ asset('storage/' . $att->post_attachment_path) }}" target="_blank" 
                                                class="text-decoration-none text-link fw-medium">
                                                    {{ $att->post_attachment_title }}
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

{{-- Comments --}}
<div class="container mb-5">
    <div class="comment-section">
        <h4 class="fw-bold text-white mb-4">Comments</h4>

        @auth
            @if ($post->is_comment_allowed)
                <form id="comment-form" class="mb-4">
                    @csrf
                    <textarea id="comment_body" name="comment" class="form-control mb-2" rows="3" placeholder="Write your comment..."></textarea>
                    <button type="submit" class="btn btn-light btn-sm">Post Comment</button>
                </form>
            @else
                <p class="text-light">Commenting is disabled for this post.</p>
            @endif
        @endauth

        @guest
            <p class="text-light">Please <a href="{{ route('login') }}" class="text-warning">login</a> to comment.</p>
        @endguest

        <div id="comment-list"></div>

        <div class="text-center mt-3">
            <button id="load-more-comments" class="btn btn-load-more-comments" data-page="2" style="display:none;">Load More Comments</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const postId = {{ $post->id }};

    function showMessage(type, message) {
        const toast = `
            <div class="alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show shadow" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
        $('#alert-message').html(toast);
        setTimeout(() => $('#alert-message .alert').alert('close'), 4000);
    }

    function loadComments(page = 1) {
        $.get(`/user/post/${postId}/comments?page=${page}`, function (html) {
            if (page === 1) {
                $('#comment-list').html(html);
            } else {
                $('#comment-list').append(html);
            }
            $('#load-more-comments').toggle(html.includes('data-next-page')).data('page', page + 1);
        });
    }

    // Load More Pagination
    $(function () {
        loadComments();

        $('#load-more-comments').click(function () {
            loadComments($(this).data('page'));
        });

        $('#comment-form').submit(function (e) {
            e.preventDefault();
            $.post(`/user/post/${postId}/comment`, $(this).serialize(), function (res) {
                if (res.success) {
                    $('#comment_body').val('');
                    loadComments();
                    showMessage('success', res.message);
                }
            }).fail(() => showMessage('error', 'Failed to post comment.'));
        });

        $(document).on('click', '.btn-edit-comment', function () {
            let id = $(this).data('id');
            $(`#comment-body-${id}`).hide();
            $(`#edit-form-${id}`).removeClass('d-none');
        });

        $(document).on('click', '.btn-cancel-edit', function () {
            let id = $(this).data('id');
            $(`#edit-form-${id}`).addClass('d-none');
            $(`#comment-body-${id}`).show();
        });

        $(document).on('submit', '.edit-comment-form', function (e) {
            e.preventDefault();
            let id = $(this).attr('id').split('-')[2];
            $.ajax({
                url: `/user/comment/${id}`,
                method: 'PUT',
                data: $(this).serialize(),
                success: function (res) {
                    if (res.success) {
                        loadComments();
                        showMessage('success', res.message);
                    }
                },
                error: () => showMessage('error', 'Could not update comment.')
            });
        });

        $(document).on('click', '.btn-delete-comment', function () {
            let id = $(this).data('id');
            if (confirm('Delete this comment?')) {
                $.ajax({
                    url: `/user/comment/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (res) {
                        if (res.success) {
                            $(`#comment-${id}`).fadeOut();
                            showMessage('success', res.message);
                        }
                    },
                    error: () => showMessage('error', 'Failed to delete comment.')
                });
            }
        });
    });
</script>
@endpush
