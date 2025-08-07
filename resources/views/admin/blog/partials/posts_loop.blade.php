<div class="row">
    @forelse ($posts as $post)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card post-card h-100 shadow-sm">
                @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top featured-img" alt="Post Image">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-primary">{{ $post->post_title }}</h5>
                    <p class="card-text mb-4">
                        {{ \Illuminate\Support\Str::limit(strip_tags($post->post_discription), 100) }}
                    </p>
                    <a href="{{ route('admin.post.show', $post->id) }}" class="mt-auto btn btn-sm btn-outline-primary">
                        Read Full Post
                    </a>
                </div>
                <div class="card-footer text-muted small">
                    {{ $post->created_at->format('M d, Y') }}
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted">
            <p>No posts found in this blog.</p>
        </div>
    @endforelse
</div>

{{-- Bootstrap Pagination --}}
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        {{-- Previous --}}
        <li class="page-item {{ $posts->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $posts->previousPageUrl() }}" data-page="{{ $posts->currentPage() - 1 }}">Previous</a>
        </li>

        {{-- Pages --}}
        @for ($i = 1; $i <= $posts->lastPage(); $i++)
            <li class="page-item {{ $posts->currentPage() == $i ? 'active' : '' }}">
                <a class="page-link" href="{{ $posts->url($i) }}" data-page="{{ $i }}">{{ $i }}</a>
            </li>
        @endfor

        {{-- Next --}}
        <li class="page-item {{ !$posts->hasMorePages() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $posts->nextPageUrl() }}" data-page="{{ $posts->currentPage() + 1 }}">Next</a>
        </li>
    </ul>
</nav>
