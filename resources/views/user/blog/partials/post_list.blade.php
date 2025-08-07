@forelse ($posts as $post)
    <div class="col-md-4">
        <div class="card h-100 custom-post-card">
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Post Image">
            <div class="card-body">
                <h5 class="card-title">{{ $post->post_title }}</h5>
                <p class="card-text">{{ Str::limit(strip_tags($post->post_summary), 100) }}</p>
                <a href="{{ route('user.post.show', $post->id) }}" class="btn btn-sm btn-view">View Post</a>
            </div>
            <div class="card-footer bg-transparent border-0">
                <small style="color: #ccc;">
                    By {{ $post->blog->user->first_name ?? 'Unknown' }} | {{ $post->created_at->diffForHumans() }}
                </small>
            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center text-white">
        <p>No posts available for this blog.</p>
    </div>
@endforelse

{{-- Pagination --}}
<div class="col-12 mt-4">
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            {{-- Previous --}}
            @if ($posts->onFirstPage())
                <li class="page-item disabled"><a class="page-link">Previous</a></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $posts->previousPageUrl() }}">Previous</a></li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                <li class="page-item {{ $posts->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Next --}}
            @if ($posts->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $posts->nextPageUrl() }}">Next</a></li>
            @else
                <li class="page-item disabled"><a class="page-link">Next</a></li>
            @endif
        </ul>
    </nav>
</div>
