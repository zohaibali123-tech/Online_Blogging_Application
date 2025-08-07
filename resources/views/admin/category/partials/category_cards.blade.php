<div class="row" id="category-list">
    @forelse($categories as $category)
        <div class="col-md-4 mb-4">
                <div class="admin-card shadow p-3 text-dark">
                    <h5 class="fw-bold">{{ $category->category_title }}</h5>
                    <p><strong>Posts:</strong>
                        <span class="badge bg-info ms-2">
                            {{ $category->posts()->count() }} 
                        </span>
                    </p>
                    <p><strong>Description:</strong> {{ $category->category_description }}</p>
                    <p><strong>Status:</strong>
                        <span class="badge {{ $category->category_status === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $category->category_status }}
                        </span>
                    </p>
                    <p><strong>Total Posts:</strong> {{ $category->posts_count }}</p>
                    <div class="mt-3 d-flex justify-content-between">
                        <a href="{{ route('admin.category.show', $category->id) }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-eye"></i> View
                        </a>
                        <button class="btn btn-sm btn-outline-primary editCategoryBtn" data-id="{{ $category->id }}">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-outline-warning toggleStatusBtn" data-id="{{ $category->id }}" data-status="{{ $category->category_status }}">
                            <i class="bi bi-toggle-{{ $category->category_status === 'Active' ? 'on' : 'off' }}"></i>
                            {{ $category->category_status === 'Active' ? 'Deactivate' : 'Activate' }}
                        </button>
                        <button class="btn btn-sm btn-outline-danger deleteCategoryBtn" data-id="{{ $category->id }}">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted">
            <p>No categories found.</p>
        </div>
    @endforelse
</div>

{{-- Bootstrap Pagination --}}
@if ($categories->hasPages())
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center mt-4">
            {{-- Previous Page Link --}}
            <li class="page-item {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $categories->previousPageUrl() ?? '#' }}">Previous</a>
            </li>

            {{-- Pagination Elements --}}
            @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                <li class="page-item {{ $categories->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Next Page Link --}}
            <li class="page-item {{ $categories->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $categories->nextPageUrl() ?? '#' }}">Next</a>
            </li>
        </ul>
    </nav>
@endif
