@extends('layouts.admin_layout')

@section('title', 'User Profile')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 text-center">
                <div class="card-body">
                    @if ($user->user_image)
                        <img src="{{ asset('storage/' . $user->user_image) }}" 
                            alt="Profile Image" class="rounded-circle mb-3 shadow" width="100" height="100">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->first_name . ' ' . $user->last_name) }}&size=128"
                            alt="Default Avatar" class="rounded-circle mb-3 shadow" width="100" height="100">
                    @endif
                    <h5 class="fw-bold">{{ ($user->first_name ?? '') . ' ' . ($user->last_name ?? '') }}</h5>
                    <p class="text-muted small mb-1">{{ $user->email }}</p>
                    <p class="text-muted small">{{ $user->address ?? 'Location N/A' }}</p>

                    <div class="mb-2">
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="badge bg-{{ $user->is_approved ? 'primary' : 'secondary' }}">
                            {{ $user->is_approved ? 'Approved' : 'Pending' }}
                        </span>
                    </div>

                    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="mb-4 fw-semibold">User Details</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Name:</strong> {{ ($user->first_name ?? '') . ' ' . ($user->last_name ?? '') }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                        <li class="list-group-item"><strong>Role:</strong> {{ $roleType ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Date of Birth:</strong> {{ $user->date_of_birth ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Gender:</strong> {{ ucfirst($user->gender) ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Address:</strong> {{ $user->address ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Status:</strong> {{ $user->is_active ? 'Active' : 'Inactive' }}</li>
                        <li class="list-group-item"><strong>Approval:</strong> {{ $user->is_approved ? 'Approved' : 'Pending' }}</li>
                        <li class="list-group-item"><strong>Created At:</strong> {{ $user->created_at->format('d M Y') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
