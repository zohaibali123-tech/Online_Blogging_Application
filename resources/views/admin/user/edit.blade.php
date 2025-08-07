@extends('layouts.admin_layout')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <h3 class="fw-bold mb-4">Edit User</h3>
    @include('admin.user.partials.form', ['user' => $user, 'roles' => $roles])
</div>
@endsection
