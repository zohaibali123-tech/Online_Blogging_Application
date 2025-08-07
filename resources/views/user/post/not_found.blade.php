@extends('layouts.user_layout')

@section('title', 'Post Not Available')

@section('content')
<style>
    .post-card {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        color: white;
        border-radius: 15px;
        overflow: hidden;
        transition: 0.3s;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        padding: 40px 20px;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="post-card text-center">
                <img src="{{ asset('images/not-found.svg') }}" alt="Not Found" class="img-fluid mb-4" style="max-height: 250px;">
                <h2 class="text-danger">Post Not Available</h2>
                <p class="text-white">
                    The post you're trying to view is not available. It might have been removed, or it's temporarily inactive.
                </p>
                <a href="{{ route('user.post.index') }}" class="btn btn-light mt-3">
                    <i class="bi bi-arrow-left"></i> Go Back to Posts
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
