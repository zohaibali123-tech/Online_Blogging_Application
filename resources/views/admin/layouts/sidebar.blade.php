<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 250px; min-height: 100vh; overflow-y: auto;">
    {{-- Profile Card --}}
    <div class="text-center mb-3">
        <img src="{{ asset('storage/' . Auth::user()->user_image) ?? asset('default-avatar.png') }}" 
             class="rounded-circle mb-2" alt="Profile Picture" width="60" height="60">
        <h6 class="mb-0 text-white fw-semibold" style="font-size: 14px;">
            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
        </h6>
        <small class="text-white-50 d-block mb-2" style="font-size: 12px;">
            {{ Auth::user()->email ?? 'username' }}
        </small>
        <a href="{{ route('admin.user.profile', Auth::user()->id) }}" class="btn btn-outline-light btn-xs" style="font-size: 11px; padding: 2px 8px;">View profile</a>
    </div>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="bi bi-house me-2"></i> Home
            </a>
        </li>
        <li>
            <a href="{{ route('admin.blog.index') }}" class="nav-link text-white {{ request()->is('admin/blog') ? 'active' : '' }}">
                <i class="bi bi-plus-circle me-2"></i> Add Blog
            </a>
        </li>
        <li>
            <a href="{{ route('admin.category.index') }}" class="nav-link text-white {{ request()->is('admin/category') ? 'active' : '' }}">
                <i class="bi bi-folder-plus me-2"></i> Add Category
            </a>
        </li>
        <li>
            <a href="{{ route('admin.post.index') }}" class="nav-link text-white {{ request()->is('admin/post') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-plus me-2"></i> Add Post
            </a>
        </li>
        <li>
            <a href="{{ route('admin.user.index') }}" class="nav-link text-white {{ request()->is('admin/user') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> All Users
            </a>
        </li>
        <li>
            <a href="{{ route('admin.user.approved') }}" class="nav-link text-white {{ request()->is('admin/user/approved') ? 'active' : '' }}">
                <i class="bi bi-person-check-fill me-2"></i> Approved Users
            </a>
        </li>
        <li>
            <a href="{{ route('admin.user.pending') }}" class="nav-link text-white {{ request()->is('admin/user/pending') ? 'active' : '' }}">
                <i class="bi bi-person-dash-fill me-2"></i> Pending Users
            </a>
        </li>
        <li>
            <a href="{{ route('admin.user.rejected') }}" class="nav-link text-white {{ request()->is('admin/user/rejected') ? 'active' : '' }}">
                <i class="bi bi-person-x-fill me-2"></i> Rejected Users
            </a>
        </li>
        <li>
            <a href="{{ route('admin.follow.blog') }}" class="nav-link text-white {{ request()->is('admin/follow-logs') ? 'active' : '' }}">
                <i class="bi bi-person-heart me-2"></i> Followed Blogs
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.index') }}" class="nav-link text-white {{ request()->is('admin/settings') ? 'active' : '' }}">
                <i class="bi bi-gear-fill me-2"></i> Website Settings
            </a>
        </li>
        <li>
            <a href="" class="nav-link text-white"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
        <div class="mt-5"></div>
    </ul>
</div>
