@foreach ($blogs as $blog)
    <div class="col-md-4">
        <div class="blog-card shadow-sm">
            <a href="{{ route('user.blog.show', $blog->id) }}" class="text-decoration-none">
                <img src="{{ asset('storage/' . $blog->blog_background_image) }}" alt="{{ $blog->blog_title }}">
                <div class="blog-card-body">
                    <h5 class="fw-bold text-white">{{ $blog->blog_title }}</h5>
                    <p class="mb-1 text-light">Posts per page: {{ $blog->post_per_page }}</p>
                    <p class="mb-1 text-light">Total posts: {{ $blog->posts->count() }}</p>
                </div>
            </a>

            {{-- Follow/Unfollow --}}
            @auth
                @php
                    $isFollowing = \App\Models\BlogFollower::where('follower_id', auth()->id())
                        ->where('blog_following_id', $blog->id)
                        ->where('status', 'Followed')
                        ->exists();
                @endphp

                <div class="px-3 pb-3">
                    <button class="btn btn-sm btn-light rounded-pill follow-toggle-btn"
                        data-blog-id="{{ $blog->id }}">
                        {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                    </button>
                </div>
            @endauth

            {{-- Follower Count --}}
            <div class="px-3 pb-3">
                <small class="text-light">{{ $blog->followers->count() }} followers</small>
            </div>
        </div>
    </div>
@endforeach
