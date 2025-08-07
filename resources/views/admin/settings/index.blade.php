@extends('layouts.admin_layout')

@section('title', 'Website Settings')

@section('content')
<div class="container">
    <h2>Website Settings</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ $setting ? route('admin.settings.update', $setting->id) : route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($setting) @method('PUT') @endif

        <div class="form-group">
            <label>Site Name</label>
            <input type="text" name="site_name" value="{{ old('site_name', $setting->site_name ?? '') }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Site Logo</label>
            <input type="file" name="site_logo" class="form-control">
            @if(!empty($setting->site_logo))
                <img src="{{ asset('storage/' . $setting->site_logo) }}" alt="Logo" height="50" class="mt-2">
            @endif
        </div>

        <div class="form-group">
            <label>Contact Email</label>
            <input type="email" name="contact_email" value="{{ old('contact_email', $setting->contact_email ?? '') }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Facebook Link</label>
            <input type="url" name="facebook_link" value="{{ old('facebook_link', $setting->facebook_link ?? '') }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Twitter Link</label>
            <input type="url" name="twitter_link" value="{{ old('twitter_link', $setting->twitter_link ?? '') }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Instagram Link</label>
            <input type="url" name="instagram_link" value="{{ old('instagram_link', $setting->instagram_link ?? '') }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Theme Mode</label>
            <select name="theme_mode" class="form-control">
                <option value="light" {{ (old('theme_mode', $setting->theme_mode ?? '') == 'light') ? 'selected' : '' }}>Light</option>
                <option value="dark" {{ (old('theme_mode', $setting->theme_mode ?? '') == 'dark') ? 'selected' : '' }}>Dark</option>
            </select>
        </div>

        <div class="form-group">
            <label>Footer Description</label>
            <textarea name="footer_description" class="form-control">{{ old('footer_description', $setting->footer_description ?? '') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            {{ $setting ? 'Update Settings' : 'Save Settings' }}
        </button>
    </form>
</div>
@endsection
