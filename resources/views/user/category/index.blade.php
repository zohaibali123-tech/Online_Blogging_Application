@extends('layouts.user_layout')

@section('title', 'All Categories')

@section('content')
<style>
    .category-card {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        border-radius: 12px;
        color: white;
        transition: 0.3s ease;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }

    .category-card h5,
    .category-card p {
        color: #ffffff;
    }

    .btn-load-more {
        background: white;
        color: #2c5364;
        font-weight: bold;
        border: none;
        transition: 0.3s ease;
    }

    .btn-load-more:hover {
        background: #e2e2e2;
        color: #0f2027;
    }

    .text-white-75 {
        color: rgba(255, 255, 255, 0.75) !important;
    }
</style>

<div class="container py-5">
    <h2 class="text-white fw-bold text-center mb-4">Browse Categories</h2>

    <div id="category-list" class="row g-4">
        @include('user.category.partials.category_list')
    </div>

    <div class="text-center mt-4">
        @if ($categories->hasMorePages())
            <button id="load-more-btn" class="btn btn-load-more px-4 py-2" data-next-page="{{ $categories->currentPage() + 1 }}">Load More</button>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Load More Pagination
    $('#load-more-btn').on('click', function() {
        let page = $(this).data('next-page');
        let btn = $(this);

        $.ajax({
            url: '?page=' + page,
            type: 'GET',
            beforeSend: function() {
                btn.text('Loading...');
            },
            success: function(data) {
                $('#category-list').append(data);
                btn.data('next-page', page + 1);

                // Remove button if no more pages
                if (!data.includes('data-next-page')) {
                    btn.remove();
                } else {
                    btn.text('Load More');
                }
            },
            error: function() {
                btn.text('Try Again');
            }
        });
    });
</script>
@endpush
