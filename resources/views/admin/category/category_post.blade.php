@extends('layouts.admin_layout')

@section('title', $category->category_title)

@section('content')
<div class="container py-4">

    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('admin.category.index') }}" class="btn btn-outline-secondary">
            ← Back to All Categories
        </a>
    </div>

    {{-- Category Header --}}
    <div class="mb-5">
        <div class="card shadow-sm border-0 text-center bg-light">
            <div class="card-body">
                <h1 class="display-5 fw-bold mb-1">{{ $category->category_title }}</h1>
                <p class="text-muted mb-3">{{ $category->category_description }}</p>
                <span class="badge bg-primary-subtle text-dark fs-6 fw-medium px-3 py-2">
                    Total Posts: {{ $category->posts()->count() }}
                </span>
            </div>
        </div>
    </div>

    {{-- Posts Grid --}}
    <div id="posts-container">
        @include('admin.category.partials.posts_loop', ['posts' => $posts])
    </div>
</div>

{{-- Styles --}}
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
</style>
@endsection

@push('scripts')
<script>
    // Ajax Pagination
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');

        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                $('#posts-container').html(response);
            },
            error: function(xhr) {
                alert('Failed to load posts.');
            }
        });
    });
</script>
@endpush
