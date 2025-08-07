@foreach ($categories as $category)
    <div class="col-md-4">
        <a href="{{ route('user.categories.show', $category->id) }}" class="text-decoration-none">
            <div class="p-4 category-card h-100 shadow-sm d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="bi bi-folder-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $category->category_title }}</h5>
                            <small class="text-white-50">{{ $category->posts_count }} {{ Str::plural('Post', $category->posts_count) }}</small>
                        </div>
                    </div>
                    <p class="mb-0 text-white-75">{{ Str::limit($category->category_description ?? 'No description available.', 100) }}</p>
                </div>
                <div class="text-end mt-3">
                    <span class="badge bg-light text-dark px-3 py-2 fw-semibold">View Category</span>
                </div>
            </div>
        </a>
    </div>
@endforeach
