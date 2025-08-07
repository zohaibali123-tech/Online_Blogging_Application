@extends('layouts.auth_layout')

@section('title', 'Register')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        background-attachment: fixed;
        padding-top: 40px;
        padding-bottom: 40px;
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
        color: #f1f1f1;
        text-decoration: none;
    }

    .auth-link:hover {
        color: #ffc107;
        text-decoration: none;
    }
</style>

<div class="row justify-content-center align-items-center">
    <div class="col-md-7">
        <div class="text-center mb-4 text-white">
            <img src="{{ asset('cover.png') }}" alt="Logo" style="height: 80px;">
            <h2 class="mt-3 fw-bold">Create Account</h2>
            <p class="text-light">Fill in the details to register</p>
        </div>
        <div class="card glass-card border-0">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name" placeholder="Enter first name" required>
                            @error('first_name')
                                <br><small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name" placeholder="Enter last name" required>
                            @error('last_name')
                                <br><small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter email address" required>
                        @error('email')
                            <br><small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                            @error('password')
                                <br><small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Re-type password" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Male" required>
                            <label class="form-check-label">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Female" required>
                            <label class="form-check-label">Female</label>
                        </div>
                        @error('gender')
                            <br><small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="date_of_birth" required>
                        @error('date_of_birth')
                            <br><small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="user_image" class="form-label">Profile Image</label>
                        <input type="file" class="form-control" name="user_image" accept="image/*">
                        @error('user_image')
                            <br><small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="2" placeholder="Enter your address"></textarea>
                        @error('address')
                            <br><small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-gradient w-100">Register</button>
                </form>
            </div>
        </div>
        <div class="text-center mt-3 text-white">
            <p class="small">
                Already have an account?
                <a href="{{ route('login') }}" class="auth-link fw-bold">Login here</a>
            </p>
        </div>
    </div>
</div>
@endsection
