{{-- Blog Cards --}}
<div class="row">
    @forelse ($blogs as $blog)
        <div class="col-md-4 mb-4">
            <div class="admin-card position-relative text-white shadow"
                style="background: url('{{ asset('storage/' . $blog->blog_background_image) }}') center center / cover no-repeat; min-height: 180px;">
                
                <div class="bg-dark bg-opacity-50 p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-2">{{ $blog->blog_title }}</h5>
                        <p class="mb-1"><strong>Posts/Page:</strong> {{ $blog->post_per_page }}</p>
                        <p class="mb-1"><strong>Status:</strong>
                            <span class="badge {{ $blog->blog_status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $blog->blog_status }}
                            </span>
                        </p>
                    </div>

                    <div class="d-flex flex-wrap gap-1 mt-2">
                        <a href="{{ route('admin.blog.show', $blog->id) }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-eye"></i> View
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-light editBlogBtn" data-id="{{ $blog->id }}">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning toggleStatusBtn" data-id="{{ $blog->id }}" data-status="{{ $blog->blog_status }}">
                            <i class="bi bi-toggle-{{ $blog->blog_status == 'Active' ? 'on' : 'off' }}"></i>
                            {{ $blog->blog_status == 'Active' ? 'Deactivate' : 'Activate' }}
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger deleteBlogBtn" data-id="{{ $blog->id }}">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted">
            <p>No blogs found.</p>
        </div>
    @endforelse
</div>

{{-- Bootstrap Pagination --}}
@if ($blogs->hasPages())
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center mt-4">
            <li class="page-item {{ $blogs->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $blogs->previousPageUrl() ?? '#' }}">Previous</a>
            </li>
            @foreach ($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
                <li class="page-item {{ $blogs->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            <li class="page-item {{ $blogs->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $blogs->nextPageUrl() ?? '#' }}">Next</a>
            </li>
        </ul>
    </nav>
@endif
