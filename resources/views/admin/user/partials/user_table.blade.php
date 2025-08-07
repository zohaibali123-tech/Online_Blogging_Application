<table class="table table-bordered table-hover align-middle shadow-sm rounded">
    <thead class="table-light">
        <tr>
            <th>#ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Approval</th>
            <th>Joined At</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge {{ $user->role_id == 1 ? 'bg-dark' : 'bg-info' }}">
                        {{ $user->role_id == 1 ? 'Admin' : 'User' }}
                    </span>
                </td>
                <td>
                    <span class="badge user-status-badge {{ $user->is_active === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $user->is_active }}
                    </span>                    
                </td>
                <td>
                    {{-- Approval Badge --}}
                    <span class="badge user-approval-badge 
                    @if($user->is_approved === 'Pending') bg-warning text-dark
                    @elseif($user->is_approved === 'Approved') bg-success
                    @else bg-danger
                    @endif">
                    {{ $user->is_approved }}
                    </span>

                    {{-- Conditional Approval Buttons --}}
                    @php
                    $approvalOptions = ['Approved', 'Pending', 'Rejected'];
                    $currentStatus = $user->is_approved;
                    $availableStatuses = array_filter($approvalOptions, fn($status) => $status !== $currentStatus);
                    @endphp
                </td>
                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }}</td>
                <td class="text-center">
                    <a href="{{ route('admin.user.profile', $user->id) }}" class="btn btn-sm btn-primary me-1">
                        <i class="bi bi-person-lines-fill"></i> Profile
                    </a>                    
                    <button class="btn btn-sm toggle-status-btn {{ $user->is_active === 'Active' ? 'btn-secondary' : 'btn-success' }}"
                        data-id="{{ $user->id }}">
                        {{ $user->is_active === 'Active' ? 'Deactivate' : 'Activate' }}
                    </button>                                        
                    <div class="btn-group">
                        @foreach ($availableStatuses as $status)
                            <button class="btn btn-sm {{ 
                                $status === 'Approved' ? 'btn-success' : 
                                ($status === 'Rejected' ? 'btn-danger' : 'btn-warning text-dark') 
                            }} approve-btn" 
                            data-id="{{ $user->id }}" data-status="{{ $status }}">
                                {{ $status }}
                            </button>
                        @endforeach
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">No users found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- Pagination --}}
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center mt-4">
        <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $users->previousPageUrl() ?? '#' }}">Previous</a>
        </li>
        @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
            <li class="page-item {{ $users->currentPage() == $page ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
            </li>
        @endforeach
        <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link" href="{{ $users->nextPageUrl() ?? '#' }}">Next</a>
        </li>
    </ul>
</nav>
