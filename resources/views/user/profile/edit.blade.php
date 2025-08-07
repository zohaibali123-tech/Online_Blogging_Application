@extends('layouts.user_layout')

@section('title', 'Edit Profile')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #b4bde4, #82ebd7);
    }

    .edit-card {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        color: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        padding: 30px;
    }

    label {
        font-weight: 500;
    }

    .form-control {
        border-radius: 10px;
        border: none;
    }

    .form-control:focus {
        box-shadow: 0 0 5px rgba(255,255,255,0.6);
    }

    .btn-save {
        background-color: #ffffffcc;
        color: #000;
        font-weight: bold;
        border: none;
    }

    .btn-save:hover {
        background-color: #fff;
        color: #000;
    }
</style>

<div class="container mt-5">
    <h2 class="text-white fw-bold mb-4">Edit Profile</h2>

    <div class="edit-card">
        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Gender</label>
                    <select name="gender" class="form-control" required>
                        <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->date_of_birth) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Home Town</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Profile Image</label><br>
                    @if($user->user_image)
                        <img src="{{ asset('storage/' . $user->user_image) }}" alt="Profile" class="mb-2 rounded-circle" width="60" height="60">
                    @endif
                    <input type="file" name="user_image" class="form-control mt-2">
                </div>
            </div>

            <button type="submit" class="btn btn-save mt-3">Update Profile</button>
            <a href="{{ route('user.profile.show') }}" class="btn btn-secondary mt-3">Cancel</a>
        </form>
    </div>
</div>
@endsection
