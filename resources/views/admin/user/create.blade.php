@extends('layouts.admin_layout')

@section('title', 'Create New User')

@section('content')
<div class="container-fluid">
    <h3 class="fw-bold mb-4">Create New User</h3>
    @include('admin.user.partials.form', ['roles' => $roles])
</div>
@endsection
