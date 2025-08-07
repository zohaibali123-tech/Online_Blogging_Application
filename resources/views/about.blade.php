@extends('layouts.user_layout')

@section('title', 'About Us')

@section('content')
<style>
    .about-hero {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        color: white;
        padding: 80px 0;
        text-align: center;
    }

    .about-section {
        padding: 60px 20px;
    }

    .card-feature {
        border-radius: 16px;
        background: linear-gradient(135deg, #0f2027, #2c5364);
        color: white;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.15);
        padding: 30px;
        transition: transform 0.3s ease;
        height: 100%;
    }

    .card-feature:hover {
        transform: translateY(-5px);
    }

    .profile-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #ffffffaa;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        margin-bottom: 20px;
    }

    .section-title {
        color: #2c5364;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .text-muted-light {
        color: #ddd;
    }
</style>

{{-- Hero Section --}}
<div class="about-hero">
    <div class="container">
        <h1 class="display-5 fw-bold">About Me</h1>
        <p class="lead mt-3">Empowering readers and developers through modern design and code simplicity.</p>
    </div>
</div>

{{-- About Section as Card --}}
<div class="container my-5">
    <div class="card-feature text-center">
        <h2 class="mb-3">💡 Our Platform</h2>
        <p class="text-muted-light mb-0">
            This blogging system is a solo-crafted platform aimed to connect creative minds and passionate learners.
            Whether it's sharing your thoughts or diving into a new perspective — the experience here is fast, intuitive, and beautiful.
        </p>
    </div>
</div>

{{-- Mission & Vision --}}
<div class="container mb-5">
    <div class="row text-center">
        <div class="col-md-6 mb-4">
            <div class="card-feature h-100">
                <h4 class="mb-3">🚀 My Mission</h4>
                <p class="text-muted-light">To build clean, responsive, and user-focused web experiences that simplify communication and boost creativity.</p>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card-feature h-100">
                <h4 class="mb-3">🌍 My Vision</h4>
                <p class="text-muted-light">To provide one of the most efficient and elegant Laravel-based platforms for content creators and developers.</p>
            </div>
        </div>
    </div>
</div>

{{-- Meet the Creator as Card --}}
<div class="container text-center mb-5">
    <div class="card-feature d-inline-block px-5 py-4">
        <img src="https://via.placeholder.com/120" class="profile-img" alt="Zohaib Ali">
        <h5 class="fw-bold mt-2 mb-1">Zohaib Ali</h5>
        <p class="text-muted-light mb-0">Founder & Full Stack Developer</p>
    </div>
</div>
@endsection
