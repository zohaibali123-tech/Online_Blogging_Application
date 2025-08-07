@extends('layouts.admin_layout')

@section('title', 'Edit Post')

@section('content')
<div class="container">
    <h3 class="fw-bold mb-4">Edit Post</h3>

    @include('admin.post._form', [
        'route' => route('admin.post.update', $post->id),
        'method' => 'PUT',
        'post' => $post,
    ])
</div>
@endsection
