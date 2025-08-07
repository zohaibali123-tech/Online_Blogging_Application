<form 
    action="{{ isset($user) ? route('admin.user.update', $user->id) : route('admin.user.store') }}" 
    method="POST" 
    enctype="multipart/form-data" 
    class="row g-4"
>
    @csrf
    @if(isset($user))
        @method('PUT')
    @endif

    {{-- Role --}}
    <div class="col-md-4">
        <label for="role_id" class="form-label">Role Type</label>
        <select name="role_id" id="role_id" class="form-select" required>
            <option value="">Select Role</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" 
                    {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                    {{ $role->role_type }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- First Name --}}
    <div class="col-md-4">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control" 
               value="{{ old('first_name', $user->first_name ?? '') }}" required>
    </div>

    {{-- Last Name --}}
    <div class="col-md-4">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control" 
               value="{{ old('last_name', $user->last_name ?? '') }}" required>
    </div>

    {{-- Email --}}
    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" 
               value="{{ old('email', $user->email ?? '') }}" {{ isset($user) ? 'readonly' : '' }} required>
    </div>

    {{-- Password (only on create) --}}
    @if (!isset($user))
    <div class="col-md-6">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    @endif

    {{-- Gender --}}
    <div class="col-md-4">
        <label for="gender" class="form-label">Gender</label>
        <select name="gender" id="gender" class="form-select" required>
            <option value="">Select Gender</option>
            <option value="Male" {{ old('gender', $user->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('gender', $user->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
        </select>
    </div>

    {{-- Date of Birth --}}
    <div class="col-md-4">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
               value="{{ old('date_of_birth', $user->date_of_birth ?? '') }}">
    </div>

    {{-- Profile Image --}}
    <div class="col-md-4">
        <label for="user_image" class="form-label">Profile Image</label>
        <input type="file" name="user_image" id="user_image" class="form-control">
        @if(isset($user) && $user->user_image)
            <img src="{{ asset('storage/' . $user->user_image) }}" 
                 alt="Profile Image" class="rounded mt-2 shadow" width="80">
        @endif
    </div>

    {{-- Address --}}
    <div class="col-md-12">
        <label for="address" class="form-label">Address</label>
        <input type="text" name="address" id="address" class="form-control"
               value="{{ old('address', $user->address ?? '') }}">
    </div>

    {{-- Approval --}}
    <div class="col-md-6">
        <label for="is_approved" class="form-label">Approval</label>
        <select name="is_approved" id="is_approved" class="form-select" required>
            <option value="Approved" {{ old('is_approved', $user->is_approved ?? '') == 'Approved' ? 'selected' : '' }}>Approved</option>
            <option value="Pending" {{ old('is_approved', $user->is_approved ?? '') == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Rejected" {{ old('is_approved', $user->is_approved ?? '') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
    </div>

    {{-- Status --}}
    <div class="col-md-6">
        <label for="is_active" class="form-label">Status</label>
        <select name="is_active" id="is_active" class="form-select" required>
            <option value="Active" {{ old('is_active', $user->is_active ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
            <option value="InActive" {{ old('is_active', $user->is_active ?? '') == 'InActive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    {{-- Submit --}}
    <div class="col-12 text-end mt-3">
        <button type="submit" class="btn btn-primary px-5">
            <i class="bi bi-save me-1"></i> {{ isset($user) ? 'Update User' : 'Create User' }}
        </button>
    </div>
</form>
