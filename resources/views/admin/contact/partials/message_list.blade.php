<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle shadow-sm rounded">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Received At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($messages as $index => $msg)
                <tr>
                    <td>{{ $messages->firstItem() + $index }}</td>
                    <td>{{ $msg->name }}</td>
                    <td>{{ $msg->email }}</td>
                    <td>{{ $msg->subject }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary view-message-btn" 
                                data-message="{{ $msg->message }}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#viewMessageModal">
                            View
                        </button>
                    </td>                    
                    <td>{{ $msg->created_at->format('d M Y h:i A') }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary reply-btn"
                            data-id="{{ $msg->id }}"
                            data-email="{{ $msg->email }}"
                            data-subject="Re: {{ $msg->subject }}">
                            <i class="bi bi-reply"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $msg->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>                    
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-muted text-center">No messages found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- View Full Message Modal -->
<div class="modal fade" id="viewMessageModal" tabindex="-1" aria-labelledby="viewMessageLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewMessageLabel">Full Message</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p id="full-message-content" class="mb-0"></p>
        </div>
      </div>
    </div>
  </div>  

{{-- Pagination --}}
@if ($messages->hasPages())
    <nav aria-label="Message pagination">
        <ul class="pagination justify-content-center">
            {{-- Previous --}}
            <li class="page-item {{ $messages->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $messages->previousPageUrl() }}" tabindex="-1">Previous</a>
            </li>

            {{-- Page numbers --}}
            @foreach ($messages->getUrlRange(1, $messages->lastPage()) as $page => $url)
                <li class="page-item {{ $messages->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Next --}}
            <li class="page-item {{ !$messages->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $messages->nextPageUrl() }}">Next</a>
            </li>
        </ul>
    </nav>
@endif
