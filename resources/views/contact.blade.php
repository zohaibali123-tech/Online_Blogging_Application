@extends('layouts.user_layout')

@section('title', 'Contact Us')

@section('content')
<style>
    .section-title-card {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        color: white;
        border-radius: 16px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.1);
        margin-top: 40px;
        margin-bottom: 40px;
    }

    .form-card {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
    }

    .form-control,
    .form-select {
        border-radius: 10px;
    }

    .btn-send {
        background: #2c5364;
        color: white;
        font-weight: 600;
        border-radius: 8px;
        padding: 10px 30px;
    }

    .btn-send:hover {
        background: #1b2f3a;
    }

    .map-frame {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        height: 370px;
    }
</style>

{{-- Header Card --}}
<div class="container">
    <div class="section-title-card">
        <h1 class="display-6 fw-bold">Contact Us</h1>
        <p class="lead mt-2">We’d love to hear from you! Fill the form and we’ll get back soon.</p>
    </div>
</div>

{{-- Contact Form --}}
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-7 mb-4">
            <div class="form-card">
                @if(session('success'))
                    <div class="alert alert-success shadow">{{ session('success') }}</div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name *</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Enter your name" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email *</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Enter your email" required>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject *</label>
                        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror"
                            value="{{ old('subject') }}" placeholder="Subject..." required>
                        @error('subject') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Message *</label>
                        <textarea name="message" id="message" rows="5" class="form-control @error('message') is-invalid @enderror"
                            placeholder="Write your message..." required>{{ old('message') }}</textarea>
                        @error('message') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <button type="submit" class="btn btn-send">Send Message</button>
                </form>
            </div>
        </div>

        {{-- Optional Map --}}
        <div class="col-md-5 d-flex align-items-stretch">
            <div class="map-frame w-100 bg-light text-center d-flex justify-content-center align-items-center">
                {{-- Replace with working iframe below --}}
                <p class="text-muted">Google Map will appear here (iframe broken)</p>
            </div>
        </div>
    </div>
</div>
@endsection
