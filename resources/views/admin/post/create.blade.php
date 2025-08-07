@extends('layouts.admin_layout')

@section('title', 'Create New Post')

@section('content')
<div class="container">
    <h3 class="fw-bold mb-4">Create New Post</h3>

    @include('admin.post._form', [
        'route' => route('admin.post.store'),
        'method' => 'POST',
        'post' => null,
    ])
</div>
@endsection
