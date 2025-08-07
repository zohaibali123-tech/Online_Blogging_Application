@extends('layouts.admin_layout')

@section('title', 'Manage Blogs')

@section('content')
<style>
    .blog-card-link:hover .admin-card {
        transform: scale(1.02);
        transition: 0.3s ease-in-out;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }
</style>
{{-- Alert Placeholder --}}
<div id="alert-message" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

<div class="container-fluid">
    
    <div class="row align-items-center mb-4 g-3">
        <!-- Title -->
        <div class="col-lg-3 col-md-12">
            <h3 class="fw-bold mb-0">All Blogs</h3>
        </div>
    
        <!-- Search Bar -->
        <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="d-flex">
                <input type="text" id="blogSearchInput" class="form-control me-2" placeholder="Search blogs by title...">
                <button class="btn btn-primary px-3" id="blogSearchBtn">Search</button>
            </div>
        </div>
    
        <!-- Create Button -->
        <div class="col-lg-3 col-md-4 col-sm-12 text-md-end text-start">
            <button class="btn btn-primary w-100 w-md-auto px-3 py-2" data-bs-toggle="modal" data-bs-target="#createBlogModal">
                <i class="bi bi-plus-circle me-1"></i> Create Blog
            </button>
        </div>
    </div>

    {{-- Blog Cards Load Area --}}
    <div id="blog-list"></div>    
</div>

<!-- Shared Modal Create/Edit Blog -->
<div class="modal fade" id="createBlogModal" tabindex="-1" aria-labelledby="createBlogModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="blogForm" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="blog_id" id="blog_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="createBlogModalLabel">Create New Blog</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Blog Title</label>
                        <input type="text" name="blog_title" id="blog_title" class="form-control" placeholder="Enter blog title">
                        <span class="text-danger" id="error_blog_title"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Posts Per Page</label>
                        <input type="number" name="post_per_page" id="post_per_page" class="form-control" placeholder="e.g. 5">
                        <span class="text-danger" id="error_post_per_page"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Background Image</label>
                        <div id="blogImagePreview" class="mb-2" style="display: none;">
                            <img src="" id="previewImage" class="img-fluid rounded shadow-sm" style="max-height: 150px;" alt="Blog Background">
                        </div>
                        <input type="file" name="blog_background_image" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="blog_status" id="blog_status" class="form-select">
                            <option value="Active">Active</option>
                            <option value="InActive">InActive</option>
                        </select>
                        <span class="text-danger" id="error_blog_status"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveBlogBtn">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {

     // Load Blogs with AJAX
     function loadBlogs(page = 1, search = '') {
        $.ajax({
            url: "{{ route('admin.blog.index') }}",
            type: "GET",
            data: { page: page, search: search },
            success: function (data) {
                $('#blog-list').html(data);
            },
            error: function () {
                $('#blog-list').html('<p class="text-danger text-center">Failed to load blogs.</p>');
            }
        });
    }

    loadBlogs();

    // Search button is clicked
    $('#blogSearchBtn').on('click', function () {
        let searchValue = $('#blogSearchInput').val().trim();
        loadBlogs(1, searchValue);
    });

    // When input is cleared, reload full blog list
    $('#blogSearchInput').on('input', function () {
        if ($(this).val().trim() === '') {
            loadBlogs();
        }
    });

    // Handle pagination click
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        if (page) {
            loadBlogs(page);
        }
    });

    // Blog Create Code
    $('#saveBlogBtn').off('click').on('click', function (e) {
        e.preventDefault();
        let $btn = $(this);
        $btn.prop('disabled', true);

        let formData = new FormData($('#blogForm')[0]);

        // Clear previous error messages
        $('#error_blog_title').text('');
        $('#error_post_per_page').text('');
        $('#error_blog_status').text('');

        $.ajax({
            type: 'POST',
            url: "{{ route('admin.blog.store') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                showMessage('success', response.message);

                $('#createBlogModal').modal('hide');
                $('#blogForm')[0].reset();
                $('#blog_id').val('');
                $('#blogModalTitle').text('Create New Blog');
                $('#saveBlogBtn').text('Create');

                loadBlogs();
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    // Show validation errors
                    if (errors.blog_title) {
                        $('#error_blog_title').text(errors.blog_title[0]);
                    }
                    if (errors.post_per_page) {
                        $('#error_post_per_page').text(errors.post_per_page[0]);
                    }
                    if (errors.blog_status) {
                        $('#error_blog_status').text(errors.blog_status[0]);
                    }

                    showMessage('error', 'Please correct the highlighted fields.');
                } else {
                    showMessage('error', 'Something went wrong. Try again.');
                }
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
        });
    });

    // 1. Edit Button
    $(document).on('click', '.editBlogBtn', function () {
        const blogId = $(this).data('id');
        $.ajax({
            url: `/admin/blog/${blogId}/edit`,
            type: 'GET',
            success: function (res) {
                if (res.status === 'success') {
                    const blog = res.data;

                    $('#blog_id').val(blog.id);
                    $('input[name="blog_title"]').val(blog.blog_title);
                    $('input[name="post_per_page"]').val(blog.post_per_page);
                    $('select[name="blog_status"]').val(blog.blog_status.trim());

                    if (blog.blog_background_image) {
                        $('#previewImage').attr('src', `/storage/${blog.blog_background_image}`);
                        $('#blogImagePreview').show();
                    } else {
                        $('#blogImagePreview').hide();
                    }

                    $('#blogModalTitle').text('Edit Blog');
                    $('#saveBlogBtn').text('Update');

                    const modal = new bootstrap.Modal($('#createBlogModal'));
                    modal.show();
                }
            }
        });
    });

    // 2. Save Button 
    $('#saveBlogBtn').off('click').on('click', function (e) {
        e.preventDefault();
        let formData = new FormData($('#blogForm')[0]);
        const blogId = $('#blog_id').val();
        const url = blogId ? `/admin/blog/${blogId}` : `{{ route('admin.blog.store') }}`;
        if (blogId) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.status === 'success') {
                    showMessage('success', res.message);

                    $('#createBlogModal').modal('hide');
                    $('#blogForm')[0].reset();
                    $('#blog_id').val('');
                    $('#blogModalTitle').text('Create New Blog');
                    $('#saveBlogBtn').text('Create');
                    loadBlogs();
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $('#error_blog_title').text(errors.blog_title ?? '');
                    $('#error_post_per_page').text(errors.post_per_page ?? '');
                    $('#error_blog_status').text(errors.blog_status ?? '');
                    showMessage('error', 'Please correct the highlighted fields.');
                } else {
                    showMessage('error', 'Something went wrong. Please try again.');
                }
            }
        });
    });

    // Blog Delete
    $(document).on('click', '.deleteBlogBtn', function () {
        const blogId = $(this).data('id');
        const $card = $(this).closest('.col-md-4');

        if (!confirm("Are you sure you want to delete this blog?")) return;

        $.ajax({
            url: `/admin/blog/${blogId}`,
            type: 'DELETE',
            data: {
                _token: $('input[name="_token"]').val()
            },
            success: function (res) {
                if (res.status === 'success') {
                    $card.fadeOut(500, function () {
                        $(this).remove();
                    });

                    showMessage('success', res.message);
                    loadBlogs();
                } else {
                    showMessage('error', 'Something went wrong while deleting!');
                }
            },
            error: function () {
                showMessage('error', 'Server error occurred while deleting!');
            }
        });
    });

    // Toggle Blog Status
    $(document).on('click', '.toggleStatusBtn', function () {
        const blogId = $(this).data('id');
        const $btn = $(this);

        $.ajax({
            url: `/admin/blog/${blogId}/toggle`,
            type: 'PATCH',
            data: {
                _token: $('input[name="_token"]').val()
            },
            success: function (res) {
                if (res.status === 'success') {
                    const newStatus = res.new_status;
                    const isActive = newStatus === 'Active';

                    $btn.html(`
                        <i class="bi bi-toggle-${isActive ? 'on' : 'off'}"></i>
                        ${isActive ? 'Deactivate' : 'Activate'}
                    `).data('status', newStatus);

                    const $badge = $btn.closest('.admin-card').find('.badge');
                    $badge
                        .text(newStatus)
                        .removeClass('bg-success bg-secondary')
                        .addClass(isActive ? 'bg-success' : 'bg-secondary');

                    showMessage('success', res.message);
                }
            },
            error: function () {
                showMessage('error', 'Failed to toggle status. Try again.');
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
