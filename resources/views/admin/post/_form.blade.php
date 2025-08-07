@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $route }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method === 'PUT') @method('PUT') @endif

    {{-- Blog & Category --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Select Blog</label>
            <select name="blog_id" class="form-select" required>
                <option value="" disabled {{ old('blog_id', optional($post)->blog_id) ? '' : 'selected' }}>-- Select Blog --</option>
                @foreach($blogs as $blog)
                    <option value="{{ $blog->id }}" {{ old('blog_id', optional($post)->blog_id) == $blog->id ? 'selected' : '' }}>
                        {{ $blog->blog_title }}
                    </option>
                @endforeach
            </select>
        </div>

        @php
            $selectedCategories = old('category_ids', isset($post) ? $post->categories->pluck('id')->toArray() : []);
        @endphp

        <div class="col-md-6 mb-3">
            <label class="form-label">Select Categories</label>
            <select name="category_ids[]" class="form-select" multiple required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                        {{ $category->category_title }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Post Info --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Post Information</div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Post Title</label>
                <input type="text" class="form-control" name="post_title" value="{{ old('post_title', optional($post)->post_title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Post Summary</label>
                <textarea class="form-control" name="post_summary" rows="2" required>{{ old('post_summary', optional($post)->post_summary) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Post Description</label>
                <textarea class="form-control" name="post_discription" rows="5" required>{{ old('post_discription', optional($post)->post_discription) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Post Image</label>
                <input type="file" class="form-control" name="featured_image">
                @if(optional($post)->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" class="img-fluid mt-2" style="max-height: 150px;">
                @endif
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="post_status">
                        <option value="Active" {{ old('post_status', optional($post)->post_status) == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="InActive" {{ old('post_status', optional($post)->post_status) == 'InActive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Allow Comments?</label>
                    <select class="form-select" name="is_comment_allowed">
                        <option value="1" {{ old('is_comment_allowed', optional($post)->is_comment_allowed) == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('is_comment_allowed', optional($post)->is_comment_allowed) == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Attachments --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Post Attachments</div>
        <div class="card-body">
            <div id="attachment-wrapper">
                {{-- Existing Attachments --}}
                @if(!empty($post))
                    @foreach ($post->attachments as $attachment)
                        @php
                            $ext = pathinfo($attachment->post_attachment_path, PATHINFO_EXTENSION);
                            $icons = [
                                'pdf' => 'bi-file-earmark-pdf text-danger',
                                'doc' => 'bi-file-earmark-word text-primary',
                                'docx' => 'bi-file-earmark-word text-primary',
                                'xls' => 'bi-file-earmark-excel text-success',
                                'xlsx' => 'bi-file-earmark-excel text-success',
                                'png' => 'bi-file-earmark-image text-info',
                                'jpg' => 'bi-file-earmark-image text-info',
                                'jpeg' => 'bi-file-earmark-image text-info',
                                'gif' => 'bi-file-earmark-image text-info',
                            ];
                            $iconClass = $icons[strtolower($ext)] ?? 'bi-file-earmark';
                        @endphp

                        <div class="row justify-content-center mb-2 attachment-row" data-id="{{ $attachment->id }}">
                            <input type="hidden" name="existing_attachment_ids[]" value="{{ $attachment->id }}">
                            <input type="hidden" name="delete_attachment_ids[]" class="delete-attachment-id" value="">

                            <div class="col-md-8">
                                <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $attachment->post_attachment_title }}</strong><br>
                                        <small class="text-muted">
                                            <i class="bi {{ $iconClass }} me-1"></i>
                                            <a href="{{ asset('storage/' . $attachment->post_attachment_path) }}" target="_blank">
                                                {{ basename($attachment->post_attachment_path) }}
                                            </a>
                                        </small>
                                    </div>

                                    <button type="button" class="btn btn-sm btn-danger btn-remove-existing" data-id="{{ $attachment->id }}">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                {{-- New Attachments --}}
                <div class="row g-3 align-items-end mb-2 attachment-row">
                    <div class="col-md-4">
                        <label class="form-label">Attachment Title</label>
                        <input type="text" class="form-control" name="post_attachment_title[]">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Attachment File</label>
                        <input type="file" class="form-control" name="post_attachment_file[]">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="post_attachment_status[]" class="form-select">
                            <option value="Active">Active</option>
                            <option value="InActive">InActive</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-remove">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            </div>
            <button type="button" id="add-attachment" class="btn btn-outline-primary mt-2">
                <i class="bi bi-plus-circle"></i> Add More Attachments
            </button>
        </div>
    </div>

    {{-- This hidden div will collect IDs to delete --}}
    <div id="delete-attachment-fields"></div>

    <div class="text-end">
        <button class="btn btn-success" type="submit">
            <i class="bi bi-check-circle me-1"></i> {{ $method === 'PUT' ? 'Update' : 'Save' }} Post
        </button>
    </div>
</form>

@push('scripts')
<script>
    // Add new attachment row
    document.getElementById('add-attachment').addEventListener('click', function () {
        const wrapper = document.getElementById('attachment-wrapper');
        const newRow = document.createElement('div');
        newRow.className = 'row g-3 align-items-end mb-2 attachment-row';
        newRow.innerHTML = `
            <div class="col-md-4">
                <input type="text" class="form-control" name="post_attachment_title[]" placeholder="Attachment title">
            </div>
            <div class="col-md-4">
                <input type="file" class="form-control" name="post_attachment_file[]">
            </div>
            <div class="col-md-3">
                <select name="post_attachment_status[]" class="form-select">
                    <option value="Active">Active</option>
                    <option value="InActive">InActive</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-remove">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        `;
        wrapper.appendChild(newRow);
    });

    // Remove newly added attachment rows
    document.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove')) {
            e.target.closest('.attachment-row').remove();
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-remove-existing').forEach(button => {
            button.addEventListener('click', function () {
                const row = button.closest('.attachment-row');
                const id = row.dataset.id;

                // Set delete input value
                const hiddenInput = row.querySelector('.delete-attachment-id');
                hiddenInput.value = id;

                row.style.transition = 'opacity 0.3s';
                row.style.opacity = 0;
                setTimeout(() => {
                    row.style.display = 'none';
                }, 300);
            });
        });
    });
</script>
@endpush
