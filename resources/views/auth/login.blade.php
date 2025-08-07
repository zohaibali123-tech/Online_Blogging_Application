@extends('layouts.auth_layout')

@section('title', 'Login')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 9999;" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 9999;" role="alert">
        {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<style>
    body {
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        background-attachment: fixed;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 1rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }

    .btn-gradient {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        color: #fff;
        border: none;
    }

    .btn-gradient:hover {
        background: linear-gradient(135deg, #00f2fe, #4facfe);
        color: #fff;
    }

    .auth-link {
        color: #fff;
        text-decoration: none;
    }

    .auth-link:hover {
        color: #ffc107;
        text-decoration: none;
    }
</style>

<div class="row justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-6 col-lg-5">
        <div class="text-center mb-4 text-white">
            <img src="{{ asset('cover.png') }}" alt="Logo" style="height: 80px;">
            <h2 class="mt-3 fw-bold">Welcome Back!</h2>
            <p class="text-light">Login to your account</p>
        </div>
        <div class="card glass-card border-0">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                        <label for="email">Email address</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>
                        <a href="#" onclick="alert('Forgot password functionality coming soon')" class="text-decoration-none small auth-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-gradient w-100">Log In</button>
                </form>
            </div>
        </div>
        <div class="text-center mt-3 text-white">
            <p class="small">
                Don't have an account?
                <a href="{{ route('register') }}" class="auth-link fw-bold">Register here</a>
            </p>
        </div>
    </div>
</div>
@endsection

<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) alert.classList.remove('show');
    }, 4000);
</script>


