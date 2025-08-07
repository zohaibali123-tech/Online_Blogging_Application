@extends('layouts.admin_layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    {{-- Quick Actions --}}
    <div class="d-flex flex-wrap justify-content-end mb-3">
        <a href="{{ route('admin.blog.index') }}" class="btn btn-sm btn-primary me-2 mb-2"><i class="bi bi-plus-circle me-1"></i>Blog</a>
        <a href="{{ route('admin.category.index') }}" class="btn btn-sm btn-success me-2 mb-2"><i class="bi bi-folder-plus me-1"></i>Category</a>
        <a href="{{ route('admin.post.index') }}" class="btn btn-sm btn-info me-2 mb-2"><i class="bi bi-file-earmark-plus me-1"></i>Post</a>
        <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-warning me-2 mb-2"><i class="bi bi-people me-1"></i> Manage Users</a>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-4 my-3">
        <div class="col-md-3">
            <div class="admin-card text-center">
                <h6>Total Blogs</h6>
                <h2 class="text-primary count" data-count="{{ $totalBlogs }}">0</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card text-center">
                <h6>Total Categories</h6>
                <h2 class="text-success count" data-count="{{ $totalCategories }}">0</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card text-center">
                <h6>Total Posts</h6>
                <h2 class="text-info count" data-count="{{ $totalPosts }}">0</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card text-center">
                <h6>Total Users</h6>
                <h2 class="text-warning count" data-count="{{ $totalUsers }}">0</h2>
            </div>
        </div>
    </div>

    {{-- User Status Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="admin-card text-center">
                <h6>Approved Users</h6>
                <h3 class="text-success count" data-count="{{ $approvedUsers }}">0</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-card text-center">
                <h6>Pending Users</h6>
                <h3 class="text-warning count" data-count="{{ $pendingUsers }}">0</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-card text-center">
                <h6>Rejected Users</h6>
                <h3 class="text-danger count" data-count="{{ $rejectedUsers }}">0</h3>
            </div>
        </div>
    </div>

    {{-- Analytics Chart --}}
    <div class="admin-card mb-5">
        <h5 class="mb-3">User Growth Overview</h5>
        <canvas id="userChart" height="70"></canvas>
    </div>

    {{-- Recent Posts Table --}}
    <div class="admin-card">
        <h5 class="mb-3">Recent Posts</h5>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentPosts as $post)
                        <tr>
                            <td>{{ $post->post_title }}</td>
                            <td>
                                @if($post->blog && $post->blog->user)
                                    {{ $post->blog->user->first_name . ' ' . $post->blog->user->last_name }}
                                @else
                                    <em>No user</em>
                                @endif
                            </td>                            
                            <td>
                                <span class="badge 
                                    {{ $post->post_status === 'Acitve' ? 'bg-success' : ($post->post_status === 'InActive' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($post->post_status) }}
                                </span>
                            </td>
                            <td>{{ $post->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-end mt-3">
                <a href="{{ route('admin.post.index') }}" class="btn btn-sm btn-outline-primary">See More</a>
            </div>            
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Count-up animation
    document.querySelectorAll('.count').forEach(el => {
        const target = +el.dataset.count;
        let count = 0;
        const speed = 20;
        const update = () => {
            count += Math.ceil(target / 50);
            if (count >= target) {
                el.textContent = target;
            } else {
                el.textContent = count;
                setTimeout(update, speed);
            }
        };
        update();
    });

    // Chart.js
    const userCounts = @json(array_values($monthlyUserCounts));
    const userLabels = @json($monthlyUserLabels);

    const ctx = document.getElementById('userChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: userLabels,
            datasets: [{
                label: 'Users',
                data: userCounts,
                fill: true,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.1)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
