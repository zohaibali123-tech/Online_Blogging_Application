@foreach ($comments as $comment)
    <div class="comment" id="comment-{{ $comment->id }}">
        <div class="d-flex justify-content-between">
            <div>
                <div class="comment-author">
                    {{ $comment->user->first_name }} {{ $comment->user->last_name }}
                    @if ($comment->user->role_id == 1)
                        <span class="badge bg-danger ms-2">Admin</span>
                    @endif
                </div>
                <div class="comment-time">{{ $comment->created_at->diffForHumans() }}</div>
            </div>            
            @auth
                @if (auth()->id() === $comment->user_id)
                    <div class="comment-actions">
                        <button class="btn btn-sm btn-outline-primary btn-edit-comment" data-id="{{ $comment->id }}">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-delete-comment" data-id="{{ $comment->id }}">
                            Delete
                        </button>
                    </div>
                @endif
            @endauth
        </div>

        <div class="mt-2 comment-body" id="comment-body-{{ $comment->id }}">
            {{ $comment->comment }}
        </div>

        {{-- Edit Form --}}
        <form class="edit-comment-form mt-2 d-none" id="edit-form-{{ $comment->id }}">
            @csrf
            @method('PUT')
            <textarea class="form-control mb-2" name="comment" rows="2">{{ $comment->comment }}</textarea>
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-sm btn-success">Update</button>
                <button type="button" class="btn btn-sm btn-secondary btn-cancel-edit" data-id="{{ $comment->id }}">Cancel</button>
            </div>
        </form>
    </div>
@endforeach
@if ($comments->hasMorePages())
    <span data-next-page style="display: none;"></span>
@endif
