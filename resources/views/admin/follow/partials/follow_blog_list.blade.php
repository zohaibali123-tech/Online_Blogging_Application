<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle shadow-sm rounded">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>User Name</th>
                <th>Blog Title</th>
                <th>Status</th>
                <th>Followed At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $index => $log)
                <tr>
                    <td>{{ $logs->firstItem() + $index }}</td>
                    <td>{{ $log->follower->first_name ?? 'Unknown' }} {{ $log->follower->last_name ?? '' }}</td>
                    <td>{{ $log->blog->blog_title ?? 'Unknown' }}</td>
                    <td>
                        <span class="badge bg-{{ $log->status === 'Followed' ? 'success' : 'secondary' }}">
                            {{ $log->status }}
                        </span>
                    </td>
                    <td>{{ $log->created_at->format('Y-m-d h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-muted">No follow records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Bootstrap pagination --}}
@if ($logs->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            {{-- Previous --}}
            <li class="page-item {{ $logs->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $logs->previousPageUrl() }}" tabindex="-1">Previous</a>
            </li>

            {{-- Page numbers --}}
            @foreach ($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                <li class="page-item {{ $logs->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Next --}}
            <li class="page-item {{ !$logs->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $logs->nextPageUrl() }}">Next</a>
            </li>
        </ul>
    </nav>
@endif
