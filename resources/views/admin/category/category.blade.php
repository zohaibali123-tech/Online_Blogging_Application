@extends('layouts.admin_layout')

@section('title', 'Manage Categories')

@section('content')

<style>
    .category-card-link:hover .admin-card {
        transform: scale(1.02);
        transition: 0.3s ease-in-out;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }
</style>

<div class="container-fluid">
    <div class="row align-items-center mb-4 g-3">
        <!-- Title -->
        <div class="col-lg-3 col-md-12">
            <h3 class="fw-bold mb-0">All Categories</h3>
        </div>
    
        <!-- Search Bar -->
        <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="d-flex">
                <input type="text" id="categorySearchInput" class="form-control me-2" placeholder="Search categories by title...">
                <button class="btn btn-primary px-3" id="categorySearchBtn">Search</button>
            </div>
        </div>
    
        <!-- Create Button -->
        <div class="col-lg-3 col-md-4 col-sm-12 text-md-end text-start">
            <button class="btn btn-primary w-100 w-md-auto px-3 py-2" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                <i class="bi bi-plus-circle me-1"></i> Create Category
            </button>
        </div>
    </div>

    {{-- Alert Placeholder --}}
    <div id="alert-message" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

    {{-- Category Cards --}}
    <div class="row">
        <div id="category-list">
            @include('admin.category.partials.category_cards')
        </div>
    </div>
</div>

<!-- Shared Modal: Create/Edit Category -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="categoryForm" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="category_id" id="category_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="createCategoryModalLabel">Create New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Title</label>
                        <input type="text" name="category_title" id="category_title" class="form-control" placeholder="Enter title">
                        <span class="text-danger" id="error_category_title"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="category_description" id="category_description" class="form-control" rows="3" placeholder="Enter description"></textarea>
                        <span class="text-danger" id="error_category_description"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="category_status" id="category_status" class="form-select">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        <span class="text-danger" id="error_category_status"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveCategoryBtn">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // Load categories
    function loadCategories(url = "{{ route('admin.category.index') }}") {
        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                $('#category-list').html(data);
            },
            error: function () {
                showMessage('error', 'Failed to load categories.');
            }
        });
    }

    $('#categorySearchBtn').on('click', function () {
        const query = $('#categorySearchInput').val().trim();
        const url = `{{ route('admin.category.index') }}?search=${encodeURIComponent(query)}`;
        loadCategories(url);
    });

    // When input is cleared, reload all
    $('#categorySearchInput').on('input', function () {
        if ($(this).val().trim() === '') {
            loadCategories();
        }
    });

    // Pagination click
    $(document).on('click', '#category-list .pagination a', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        if (url) {
            loadCategories(url);
        }
    });

    loadCategories();

    // Create Category
    $('#saveCategoryBtn').off('click').on('click', function (e) {
        e.preventDefault();
        let $btn = $(this);
        $btn.prop('disabled', true);

        let formData = new FormData($('#categoryForm')[0]);

        $('#error_category_title').text('');
        $('#error_category_description').text('');
        $('#error_category_status').text('');

        $.ajax({
            url: "{{ route('admin.category.store') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.status === 'success') {
                    showMessage('success', res.message);
                    $('#categoryForm')[0].reset();
                    $('#createCategoryModal').modal('hide');
                    loadCategories();
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $('#error_category_title').text(errors.category_title ?? '');
                    $('#error_category_description').text(errors.category_description ?? '');
                    $('#error_category_status').text(errors.category_status ?? '');
                } else {
                    showMessage('error', 'Something went wrong.');
                }
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
        });
    });

    // When Edit button is clicked
    $(document).on('click', '.editCategoryBtn', function () {
        const categoryId = $(this).data('id');

        $.ajax({
            url: `/admin/category/${categoryId}/edit`,
            type: 'GET',
            success: function (res) {
                if (res.status === 'success') {
                    
                    $('#category_id').val(res.data.id);
                    $('#category_title').val(res.data.category_title);
                    $('#category_description').val(res.data.category_description);
                    $('#category_status').val(res.data.category_status);

                    $('#createCategoryModalLabel').text('Edit Category');
                    $('#saveCategoryBtn').text('Update');

                    $('#createCategoryModal').modal('show');
                }
            },
            error: function () {
                showMessage('error', 'Failed to load category data.');
            }
        });
    });

    // Update Category
    $('#saveCategoryBtn').off('click').on('click', function (e) {
        e.preventDefault();

        let $btn = $(this);
        $btn.prop('disabled', true);

        let formData = new FormData($('#categoryForm')[0]);
        const categoryId = $('#category_id').val();
        const url = categoryId ? `/admin/category/${categoryId}` : `{{ route('admin.category.store') }}`;
        const method = categoryId ? 'POST' : 'POST';

        if (categoryId) {
            formData.append('_method', 'PUT');
        }

        // Clear old errors
        $('#error_category_title').text('');
        $('#error_category_description').text('');
        $('#error_category_status').text('');

        $.ajax({
            url: url,
            type: method,
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.status === 'success') {
                    showMessage('success', res.message);

                    $('#categoryForm')[0].reset();
                    $('#category_id').val('');
                    $('#createCategoryModalLabel').text('Create New Category');
                    $('#saveCategoryBtn').text('Create');
                    $('#createCategoryModal').modal('hide');
                    loadCategories();
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $('#error_category_title').text(errors.category_title ?? '');
                    $('#error_category_description').text(errors.category_description ?? '');
                    $('#error_category_status').text(errors.category_status ?? '');
                } else {
                    showMessage('error', 'Something went wrong.');
                }
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
        });
    });

    // Delete Category
    $(document).on('click', '.deleteCategoryBtn', function () {
        const button = $(this);
        const categoryId = button.data('id');

        if (!confirm('Are you sure you want to delete this post?')) {
            return;
        }

        $.ajax({
            url: `/admin/category/${categoryId}`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function (res) {
                if (res.status === 'success') {
                    showMessage('success', res.message);

                    button.closest('.col-md-4').fadeOut(300, function () {
                        $(this).remove();
                        loadCategories();
                    });
                } else {
                    showMessage('error', res.message || 'Failed to delete post.');
                }
            },
            error: function () {
                showMessage('error', 'Something went wrong while deleting.');
            }
        });
    });

    // Toggle Category Status
    $(document).on('click', '.toggleStatusBtn', function () {
        const categoryId = $(this).data('id');

        $.ajax({
            url: `/admin/category/${categoryId}/toggle`,
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                if (res.status === 'success') {
                    showMessage('success', res.message);
                    loadCategories();
                }
            },
            error: function () {
                showMessage('error', 'Failed to update category status.');
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
