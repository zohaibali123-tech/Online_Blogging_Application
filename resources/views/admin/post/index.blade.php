@extends('layouts.admin_layout')

@section('title', 'All Posts')

@section('content')

{{-- Alert Placeholder --}}
<div id="alert-message" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

<div class="container-fluid">
    <div class="row align-items-center mb-4 g-3">
        <!-- Title -->
        <div class="col-lg-3 col-md-12">
            <h3 class="fw-bold mb-0">All Posts</h3>
        </div>
    
        <!-- Search Bar -->
        <div class="col-lg-6 col-md-8 col-sm-12">
            <form action="{{ route('admin.post.index') }}" method="GET" class="d-flex">
                <input
                    type="text"
                    name="search"
                    class="form-control me-2"
                    placeholder="Search posts by title..."
                    value="{{ request('search') }}"
                >
                <button type="submit" class="btn btn-primary px-3">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.post.index') }}" class="btn btn-secondary ms-2">Reset</a>
                @endif
            </form>
        </div>
    
        <!-- Create Button -->
        <div class="col-lg-3 col-md-4 col-sm-12 text-md-end text-start">
            <a href="{{ route('admin.post.create') }}" class="btn btn-primary w-100 w-md-auto px-3 py-2">
                <i class="bi bi-plus-circle me-1"></i> Create Post
            </a>
        </div>
    </div>

    {{-- Posts Grid --}}
    <div class="row">
        @foreach ($posts as $post)
        <div class="col-md-4 mb-4">
            <div class="admin-card h-100 p-3 shadow-sm rounded ">
                @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" class="img-fluid rounded mb-2" alt="Post Image" style="max-height: 180px; object-fit: cover;">
                @endif
    
                <h5 class="fw-bold mb-1">{{ $post->post_title }}</h5>
                <p class="text-muted small">{{ Str::limit($post->post_summary, 80) }}</p>
    
                <p class="mb-1"><strong>Status:</strong>
                    <span class="badge {{ $post->post_status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $post->post_status }}
                    </span>
                </p>
    
                <p class="mb-1"><strong>Blog:</strong> {{ $post->blog->blog_title ?? '-' }}</p>
                <p class="mb-2"><strong>Categories:</strong> {{ $post->categories->pluck('category_title')->join(', ') }}</p>
    
                {{-- Collapsible Attachments --}}
                @if($post->attachments->count())
                <div class="mb-2">
                    <button class="btn btn-sm btn-outline-info w-100 text-start" data-bs-toggle="collapse" data-bs-target="#attachments-{{ $post->id }}">
                        <i class="bi bi-paperclip"></i> Show Attachments
                    </button>
                    <div class="collapse mt-2" id="attachments-{{ $post->id }}">
                        <ul class="list-group list-group-flush">
                            @foreach($post->attachments as $att)
                            <li class="list-group-item d-flex justify-content-between align-items-center small">
                                <div class="d-flex align-items-center">
                                    {{-- Icon based on extension --}}
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
                                    <i class="bi {{ $icon }} me-2"></i>
                                    <a href="{{ asset('storage/' . $att->post_attachment_path) }}" target="_blank">
                                        {{ $att->post_attachment_title }}
                                    </a>
                                </div>
                                <span class="badge {{ $att->is_active == 'Active' ? 'bg-success' : 'bg-secondary' }}">{{ $att->is_active }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
    
                <div class="d-flex justify-content-between mt-2">
                    <a href="{{ route('admin.post.edit', $post->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>                    
                    <button class="btn btn-sm btn-outline-warning toggleStatusBtn" data-id="{{ $post->id }}" data-status="{{ $post->post_status }}">
                        <i class="bi bi-toggle-{{ $post->post_status === 'Active' ? 'on' : 'off' }}"></i>
                        {{ $post->post_status == 'Active' ? 'Deactivate' : 'Activate' }}
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $post->id }}">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
                <div class="mt-2">
                    <a href="{{ route('admin.post.show', $post->id) }}" class="btn btn-sm btn-outline-secondary w-100 text-start">
                        <i class="bi bi-eye"></i> See More
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{-- @empty
        <div class="col-12 text-center text-muted">
            <p>No Post found.</p>
        </div>
    @endempty         --}}
</div>
{{-- Bootstraps Pagination --}}
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center mt-4">

        {{-- Previous Page Link --}}
        <li class="page-item {{ $posts->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $posts->previousPageUrl() ?? '#' }}">Previous</a>
        </li>

        {{-- Pagination Elements --}}
        @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
            <li class="page-item {{ $posts->currentPage() == $page ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
            </li>
        @endforeach

        {{-- Next Page Link --}}
        <li class="page-item {{ $posts->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link" href="{{ $posts->nextPageUrl() ?? '#' }}">Next</a>
        </li>

    </ul>
</nav>
@endsection

@push('scripts')
<script>
    // Custom Bootstrap Toast Message Function
    window.showMessage = function (type, message) {
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
    };
</script>
{{-- Flash Message Render --}}
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showMessage('success', @json(session('success')));
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showMessage('error', @json(session('error')));
        });
    </script>
@endif
<script>
    $(document).ready(function() {

        $(document).on('click', '.delete-btn', function () {
            let button = $(this);
            let postId = button.data('id');

            if (confirm('Are you sure you want to delete this post?')) {
                $.ajax({
                    url: `/admin/post/${postId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status === 'success') {

                            button.closest('.col-md-4').fadeOut(300, function () {
                                $(this).remove();
                            });
                            showMessage('success',response.message);
                        } else {
                            showMessage('error', response.message || 'Failed to delete post.')
                        }
                    },
                    error: function () {
                        showMessage('error','An error occurred while deleting.');
                    }
                });
            }
        });

        // Toggle
        $(document).on('click', '.toggleStatusBtn', function () {
            const button = $(this);
            const postId = button.data('id');

            $.ajax({
                url: `/admin/post/${postId}/toggle`,
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    if (res.status === 'success') {

                        const card = button.closest('.admin-card');
                        const badge = card.find('.badge');

                        badge
                            .text(res.new_status)
                            .removeClass('bg-success bg-secondary')
                            .addClass(res.new_status === 'Active' ? 'bg-success' : 'bg-secondary');

                        button
                            .html(`<i class="bi bi-toggle-${res.new_status === 'Active' ? 'on' : 'off'}"></i> ${res.new_status === 'Active' ? 'Deactivate' : 'Activate'}`);

                            showMessage('success', res.message);
                    } else {
                        showMessage('error','Status update failed.');
                    }
                },
                error: function () {
                    showMessage('error','Failed to update post status.');
                }
            });
        });

    });
</script>
@endpush
