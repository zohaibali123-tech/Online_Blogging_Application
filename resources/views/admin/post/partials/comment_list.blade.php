@foreach ($comments as $comment)
    <div class="border rounded p-3 mb-3 bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $comment->user->first_name.' '.$comment->user->last_name ?? 'Unknown User' }}</strong>
                <small class="text-muted"> • {{ $comment->created_at->diffForHumans() }}</small>
            </div>
            <span class="badge {{ $comment->is_active ? 'bg-success' : 'bg-secondary' }}">
                {{ $comment->is_active ? 'Active' : 'InActive' }}
            </span>
        </div>

        <!-- Display comment -->
        <p class="mb-2 comment-text" id="comment-text-{{ $comment->id }}">{{ $comment->comment }}</p>

        <!-- Edit form (hidden by default) -->
        @if ($comment->user_id === auth()->id())
            <div class="mb-2" id="edit-form-{{ $comment->id }}" style="display: none;">
                <textarea class="form-control mb-2 edit-comment-box" rows="2">{{ $comment->comment }}</textarea>
                <button class="btn btn-sm btn-success save-comment" data-id="{{ $comment->id }}">💾 Save</button>
                <button class="btn btn-sm btn-secondary cancel-edit" data-id="{{ $comment->id }}">✖ Cancel</button>
            </div>
        @endif

        <div class="d-flex gap-2 mt-2">
            @if(auth()->user()->role_id == 1)
                <form class="d-inline">
                    @csrf
                    <button type="button"
                            class="btn btn-sm toggle-comment {{ $comment->is_active === 'Active' ? 'btn-danger' : 'btn-success' }}"
                            data-id="{{ $comment->id }}">
                        {{ $comment->is_active === 'Active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            @endif
            @if ($comment->user_id === auth()->id())
                <button class="btn btn-sm btn-warning edit-comment-btn" data-id="{{ $comment->id }}">✏ Edit</button>
            @endif
            <!-- DELETE button (for comment owner or admin) -->
            @if ($comment->user_id === auth()->id() || auth()->user()->role_id == 1)
                <button class="btn btn-sm btn-danger delete-comment" data-id="{{ $comment->id }}">🗑 Delete</button>
            @endif
        </div>
    </div>
@endforeach

{{-- Pagination --}}
@if ($comments->hasPages())
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item {{ $comments->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $comments->previousPageUrl() }}">Previous</a>
        </li>
        @for ($i = 1; $i <= $comments->lastPage(); $i++)
            <li class="page-item {{ $i == $comments->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $comments->url($i) }}">{{ $i }}</a>
            </li>
        @endfor
        <li class="page-item {{ !$comments->hasMorePages() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $comments->nextPageUrl() }}">Next</a>
        </li>
    </ul>
</nav>
@endif
