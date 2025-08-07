<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2 me-1"></i> AdminPanel
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-house-door me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/post') ? 'active' : '' }}" href="{{ route('admin.post.index') }}">
                        <i class="bi bi-journal-text me-1"></i> Posts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/user*') ? 'active' : '' }}" href="{{ route('admin.user.index') }}">
                        <i class="bi bi-people me-1"></i> Users
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link position-relative {{ request()->is('admin/contact*') ? 'active' : '' }}"
                       href="{{ route('admin.contact.index') }}">
                        <i class="bi bi-envelope-fill"></i>
                
                        @if($newMessageCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $newMessageCount }}
                            </span>
                        @endif
                    </a>
                </li>                                                

                {{-- Admin Dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                        @if(Auth::user()->user_image)
                            <img src="{{ asset('storage/' . Auth::user()->user_image) }}" alt="User" width="30" height="30" class="rounded-circle me-1">
                        @endif
                        {{ Auth::user()->first_name . ' ' . Auth::user()->last_name ?? 'Admin' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                        <li><a class="dropdown-item" href="{{ route('admin.user.profile', Auth::user()->id) }}">Profile</a></li>
                        <li>
                            <a class="dropdown-item" href=""
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
