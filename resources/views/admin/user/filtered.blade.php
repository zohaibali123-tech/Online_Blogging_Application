@extends('layouts.admin_layout')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="fw-bold mb-0">{{ $title }}</h3>
        <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> All Users
        </a>
    </div>

    <div id="user-list">
        @include('admin.user.partials.user_table')
    </div>
</div>
@endsection

@push('scripts')
    {{-- JavaScript jQeury --}}
    <script src="{{ asset('js/user.js') }}"></script>
@endpush
