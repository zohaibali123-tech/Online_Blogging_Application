@foreach ($followedBlogs as $blog)
    <div class="col-md-4">
        <div class="blog-card shadow-sm">
            <img src="{{ asset('storage/' . $blog->blog_background_image) }}" alt="{{ $blog->blog_title }}">
            <div class="blog-card-body">
                <h5 class="fw-bold">{{ $blog->blog_title }}</h5>
                <p class="mb-1">Author: {{ $blog->user->first_name }} {{ $blog->user->last_name }}</p>
                <p class="mb-1">{{ $blog->followers->count() }} followers</p>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('user.blog.show', $blog->id) }}" class="btn btn-sm btn-light">View</a>
                    <button class="btn btn-sm btn-unfollow follow-toggle-btn" data-blog-id="{{ $blog->id }}">Unfollow</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
