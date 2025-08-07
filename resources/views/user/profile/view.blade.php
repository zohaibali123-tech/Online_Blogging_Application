@extends('layouts.user_layout')

@section('title', 'My Profile')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #b4bde4, #82ebd7);
    }

    .profile-card {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        color: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        padding: 30px;
    }

    .profile-card img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #fff;
    }

    .profile-details p {
        font-size: 1rem;
        margin-bottom: 8px;
    }

    .btn-custom {
        background-color: #ffffffcc;
        color: #000;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        transition: all 0.3s ease-in-out;
    }

    .btn-custom:hover {
        background-color: #fff;
        color: #000;
    }
</style>

{{-- Alert Placeholder --}}
<div id="alert-message" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

<div class="container mt-5">
    <h2 class="text-white fw-bold mb-4">My Profile</h2>

    <div class="profile-card">
        <div class="d-flex flex-column flex-md-row align-items-center mb-4">
            <img src="{{ asset('storage/' . ($user->user_image ?? 'default-profile.png')) }}" alt="Profile">
            <div class="ms-md-4 mt-3 mt-md-0">
                <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                <p class="mb-0 text-white-50">{{ $user->email }}</p>
            </div>
        </div>

        <div class="row profile-details">
            <div class="col-md-6">
                <p><strong>Gender:</strong> {{ ucfirst($user->gender) }}</p>
                <p><strong>Date of Birth:</strong> {{ $user->date_of_birth }}</p>
                <p><strong>Approved Status:</strong> {{ $user->is_approved ?? '-' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Home Town:</strong> {{ $user->address ?? '-' }}</p>
                <p><strong>Active Status:</strong> {{ ucfirst($user->is_active) }}</p>
                <p><strong>Registered On:</strong> {{ $user->created_at->format('d M, Y') }}</p>
            </div>
        </div>

        <div class="mt-4 d-flex flex-wrap gap-2">
            <a href="{{ route('user.profile.edit') }}" class="btn btn-custom">
                <i class="bi bi-pencil-square me-1"></i> Edit Profile
            </a>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> Back
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Flash Message Render --}}
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showMessage('success', @json(session('success')));
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showMessage('error', @json(session('error')));
        });
    </script>
@endif
<script>
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

</script>
@endpush
