@extends('layouts.user_layout')

@section('title', $category->category_title)

@section('content')
<style>
    .custom-post-card {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        color: white;
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s ease-in-out;
        position: relative;
    }

    .custom-post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.3);
    }

    .custom-post-card img {
        height: 200px;
        width: 100%;
        object-fit: cover;
    }

    .custom-post-card .card-body {
        padding: 15px;
    }

    .custom-post-card .btn-view {
        background-color: #fff;
        color: #000;
        font-weight: bold;
        margin-top: 10px;
    }

    .custom-post-card .btn-view:hover {
        background-color: #f0f0f0;
        color: #000;
    }

    .pagination .page-link {
        color: #000;
    }

    .pagination .page-item.active .page-link {
        background-color: #fff;
        color: #000;
        font-weight: bold;
        border-color: #fff;
    }

    .bi-folder2-open {
        font-size: 1.6rem;
    }
</style>

<div class="container mt-5">
    {{-- Category Header --}}
    <div class="text-center mb-5">
        <div class="d-inline-block px-4 py-4 rounded shadow-lg" style="background-color: #1e2a38; border-left: 5px solid #00d4ff;">
            <h2 class="fw-bold text-white mb-2" style="font-size: 2.2rem;">
                <i class="bi bi-folder2-open me-2 text-info"></i>{{ $category->category_title }}
            </h2>
    
            @if($category->category_description)
                <p class="text-white-50 mb-0" style="max-width: 600px; margin: 0 auto; font-size: 1rem;">
                    {{ $category->category_description }}
                </p>
            @endif
        </div>
    </div>

    {{-- Posts --}}
    <div id="post-list" class="row g-4">
        @include('user.category.partials.post_list')
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        fetchPosts(url);
    });

    function fetchPosts(url) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                $('#post-list').html(data);
                $('html, body').animate({
                    scrollTop: $("#post-list").offset().top - 100
                }, 400);
            },
            error: function() {
                alert('Error loading posts.');
            }
        });
    }
</script>
@endpush
