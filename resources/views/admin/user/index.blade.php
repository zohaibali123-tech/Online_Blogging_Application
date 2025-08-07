@extends('layouts.admin_layout')

@section('title', 'Manage Users')

@section('content')
<style>
    .table td, .table th {
        vertical-align: middle;
    }
    .table .btn {
        font-size: 0.75rem;
        padding: 4px 8px;
    }
    .search-wrapper {
        max-width: 400px;
    }
</style>

{{-- Alert Placeholder --}}
<div id="alert-message" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

<div class="container-fluid">
    {{-- Top Heading + Search + Create --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <h3 class="fw-bold mb-0">All Registered Users</h3>
            <a href="{{ route('admin.user.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus-fill"></i> Create User
            </a>
        </div>
    
        <form method="GET" action="{{ route('admin.user.index') }}" class="d-flex search-wrapper">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by name or city" value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>    

    {{-- Table Load Area --}}
    <div id="user-list">
        @include('admin.user.partials.user_table')
    </div>
</div>
@endsection

@push('head')
    <meta name="user-list-route" content="{{ route('admin.user.index') }}">
@endpush

@push('scripts')
{{-- Success / Error Toast Flash Messages --}}
@if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            showMessage('success', "{{ session('success') }}");
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            showMessage('error', "{{ session('error') }}");
        });
    </script>
@endif

<script src="{{ asset('js/user.js') }}"></script>
@endpush
