@extends('layouts.admin_layout')

@section('title', 'Post Detail')

@section('content')

<style>
    /* Match post card and comment card height */
    .equal-height-container {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .comment-scroll-container {
        max-height: 450px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .comment-scroll-container::-webkit-scrollbar {
        width: 6px;
    }

    .comment-scroll-container::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 3px;
    }

    @media (max-width: 991px) {
        .comment-scroll-container {
            max-height: none;
        }
    }
</style>

{{-- Alert Placeholder --}}
<div id="alert-message" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

<div class="container-fluid py-3">
    <div class="row g-4">

        <!-- Post Details Column -->
        <div class="col-lg-7">
            <div class="card shadow-lg rounded-4">
                <div class="card-body">
                    <h2 class="card-title mb-3 text-primary">{{ $post->post_title }}</h2>

                    <div class="mb-2"><strong>📝 Summary:</strong> {{ $post->post_summary }}</div>
                    <div class="mb-2"><strong>📄 Description:</strong> {!! $post->post_discription !!}</div>
                    <div class="mb-2"><strong>🚦 Status:</strong> 
                        <span class="badge {{ $post->post_status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $post->post_status }}
                        </span>
                    </div>
                    <div class="mb-2"><strong>📘 Blog:</strong> {{ $post->blog->blog_title ?? '-' }}</div>
                    <div class="mb-3"><strong>🏷️ Categories:</strong> {{ $post->categories->pluck('category_title')->join(', ') }}</div>

                    @if ($post->featured_image)
                        <div class="text-center my-3">
                            <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                alt="Featured Image"
                                class="img-fluid rounded shadow-sm"
                                style="max-width: 100%; height: auto; max-height: 400px; object-fit: contain;">
                        </div>
                    @endif

                    {{-- Collapsible Attachments --}}
                    @if ($post->attachments->count())
                        <div class="accordion mb-3" id="attachmentAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="attachHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#attachCollapse" aria-expanded="false" aria-controls="attachCollapse">
                                        📎 View Attachments ({{ $post->attachments->count() }})
                                    </button>
                                </h2>
                                <div id="attachCollapse" class="accordion-collapse collapse" aria-labelledby="attachHeading" data-bs-parent="#attachmentAccordion">
                                    <div class="accordion-body p-0">
                                        <ul class="list-group list-group-flush">
                                            @foreach ($post->attachments as $att)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <a href="{{ asset('storage/' . $att->post_attachment_path) }}" target="_blank" class="text-decoration-none text-primary">
                                                        {{ $att->post_attachment_title }}
                                                    </a>
                                                    <span class="badge {{ $att->is_active == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $att->is_active }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('admin.post.index') }}" class="btn btn-outline-dark mt-3">← Back to Posts</a>
                </div>
            </div>
        </div>

        <!-- Comments Column (Collapsible on mobile) -->
        <div class="col-lg-5 d-flex flex-column">
            <!-- Toggle Button (for mobile) -->
            <button class="btn btn-info w-100 mb-3 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#commentCollapse" aria-expanded="false" aria-controls="commentCollapse">
                💬 Toggle Comments
            </button>
        
            <div class="collapse show d-lg-block h-100" id="commentCollapse">
                <div class="card shadow-lg rounded-4 h-100">
                    <div class="card-body equal-height-container">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                            <h4 class="mb-0 text-info">💬 Comments</h4>
                            <span class="badge bg-primary rounded-pill fs-6">{{ $post->comments->count() }} Comments</span>
                        </div>
        
                        <!-- Comment Form -->
                        <div class="border p-3 rounded mb-4 bg-light">
                            <h5 class="mb-3">📝 Add a Comment (as Admin)</h5>
                            <form id="adminCommentForm">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <div class="mb-3">
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Write your comment..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Post Comment</button>
                            </form>
                        </div>
        
                        <!-- Scrollable Comment Section -->
                        <div id="commentSection" class="comment-scroll-container mt-auto"></div>
        
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    const postId = '{{ $post->id }}';
    loadComments();

    // Load Comments
    function loadComments(page = 1) {
        $.get("{{ url('admin/comment/post') }}/" + postId + "?page=" + page, function (data) {
            $('#commentSection').html(data);
        });
    }

    // Submit Comment
    $('#adminCommentForm').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            type: "POST",
            url: "{{ route('admin.comment.store') }}",
            data: form.serialize(),
            success: function (res) {
                form[0].reset();
                loadComments();
                showMessage('success', res.message);
            },
            error: function (xhr) {
                let err = xhr.responseJSON?.message || 'Error occurred';
                showMessage('error', err);
            }
        });
    });

    // Toggle comment status
    $(document).on('click', '.toggle-comment', function () {
        let btn = $(this);
        let id = btn.data('id');

        $.ajax({
            type: "PATCH",
            url: "/admin/comment/" + id + "/toggle",
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function (res) {
                showMessage('success', res.message);

                if (btn.hasClass('btn-danger')) {
                    btn.removeClass('btn-danger').addClass('btn-success').text('Activate');
                } else {
                    btn.removeClass('btn-success').addClass('btn-danger').text('Deactivate');
                }
            },
            error: function (xhr) {
                if (xhr.status === 403) {
                    showMessage('error', 'Unauthorized action.');
                } else {
                    showMessage('error', 'Change Status failed');
                }
            }
        });
    });

    // Bootstrap-style pagination
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        loadComments(page);
    });

    // Show edit box
    $(document).on('click', '.edit-comment-btn', function () {
        let id = $(this).data('id');
        $('#comment-text-' + id).hide();
        $('#edit-form-' + id).show();
    });

    // Cancel edit
    $(document).on('click', '.cancel-edit', function () {
        let id = $(this).data('id');
        $('#edit-form-' + id).hide();
        $('#comment-text-' + id).show();
    });

    // Save edited comment
    $(document).on('click', '.save-comment', function () {
        let id = $(this).data('id');
        let textarea = $('#edit-form-' + id).find('.edit-comment-box');
        let newComment = textarea.val();

        $.ajax({
            type: 'POST',
            url: `/admin/comment/${id}`,
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PUT',
                comment: newComment
            },
            success: function (res) {
                showMessage('success', res.message);
                $('#edit-form-' + id).hide();
                $('#comment-text-' + id).text(newComment).show();
            },
            error: function () {
                showMessage('error', 'Failed to update comment');
            }
        });
    });

    // Delete Comment
    $(document).on('click', '.delete-comment', function () {
        let commentId = $(this).data('id');
        let confirmed = confirm('Are you sure you want to delete this comment?');
        
        if (!confirmed) return;

        $.ajax({
            url: `/admin/comment/${commentId}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}',
                id: commentId
            },
            success: function (res) {
                if (res.status === 'success') {
                    $(`button[data-id="${commentId}"]`).closest('.border').fadeOut();
                    showMessage('success', res.message);
                }
            },
            error: function () {
                showMessage('error', 'Error deleting comment.');
            }
        });
    });

    // Custom Bootstrap Toast Message Function
    function showMessage(type, message) {
        let alertType = type === 'success' ? 'alert-success' : 'alert-danger';
        let icon = type === 'success' 
            ? '<i class="bi bi-check-circle-fill me-2"></i>' 
            : '<i class="bi bi-x-circle-fill me-2"></i>';

        let toast = `
        <div class="alert ${alertType} alert-dismissible fade show shadow" role="alert">
            ${icon}${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        `;

        $('#alert-message').html(toast);

        setTimeout(function() {
            $('#alert-message .alert').alert('close');
        }, 4000);
    }

});
</script>
@endpush
