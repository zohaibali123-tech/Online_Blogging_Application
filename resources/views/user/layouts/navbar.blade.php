<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background: linear-gradient(135deg, #0f2027, #2c5364);">
  <div class="container">
    <a class="navbar-brand fw-bold text-white" href="{{ route('user.index') }}">
      @if(isset($siteSetting) && $siteSetting->site_logo)
        <img src="{{ asset('storage/' . $siteSetting->site_logo) }}" alt="Logo" style="height: 40px;" class="me-2">
      @else
        <img src="{{ asset('cover.png') }}" alt="Logo" style="height: 40px;" class="me-2">
      @endif
      {{ $siteSetting->site_name ?? 'MyBlog' }}
    </a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link text-white fw-semibold mx-2 {{ request()->is('/user/index') ? 'active' : '' }}" href="{{ route('user.index') }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white fw-semibold mx-2 {{ request()->is('/user/blog') ? 'active' : '' }}" href="{{ route('user.blog.index') }}">Blogs</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white fw-semibold mx-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Categories
          </a>
          <ul class="dropdown-menu">
            @isset($navbarCategories)
              @foreach($navbarCategories as $category)
                <li><a class="dropdown-item" href="{{ route('user.categories.show', $category->id) }}">{{ $category->category_title }}</a></li>
              @endforeach
            @endisset
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item text-primary fw-semibold" href="{{ route('user.categories.index') }}">
                → See All Categories
              </a>
            </li>
          </ul>
        </li>        
        <li class="nav-item">
          <a class="nav-link text-white fw-semibold mx-2 {{ request()->is('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white fw-semibold mx-2 {{ request()->is('contact') ? 'active' : '' }}" href="{{ route('contact.show') }}">Contact</a>
        </li>
      </ul>

      {{-- Search Bar --}}
      <form class="d-flex ms-lg-3 my-2 my-lg-0" action="{{ route('user.blog.search') }}" method="GET">
        <input class="form-control me-2 rounded-pill" type="search" name="query" placeholder="Search blogs..." aria-label="Search" style="min-width: 180px;">
        <button class="btn btn-outline-light rounded-pill" type="submit">Search</button>
      </form>    

      {{-- Auth Links --}}
      <ul class="navbar-nav ms-lg-3 align-items-center">
        @auth
        <li class="nav-item ms-3">
          <form method="POST" action="{{ route('user.toggleTheme') }}" id="themeToggleForm">
            @csrf
            <div class="form-check form-switch d-flex align-items-center text-white">
              <input class="form-check-input" type="checkbox" id="themeSwitch"
                     onchange="document.getElementById('themeToggleForm').submit();"
                     {{ auth()->user()->theme_mode === 'dark' ? 'checked' : '' }}>
              <label class="form-check-label ms-2" for="themeSwitch">
                {{ auth()->user()->theme_mode === 'dark' ? 'Dark' : 'Light' }}
              </label>
            </div>
          </form>
        </li>
        @endauth
        @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white fw-semibold mx-2 d-flex align-items-center" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="{{ asset('storage/' . (Auth::user()->user_image ?? 'default-profile.png')) }}" alt="Profile" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;">
              {{ Auth::user()->first_name.' '.Auth::user()->last_name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="{{ route('user.profile.show') }}">My Profile</a></li>
              <li><a class="dropdown-item" href="{{ route('user.followed.blogs') }}">Followed Blogs</a></li>
              <li><a class="dropdown-item" href="{{ route('logout') }}"
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </ul>
          </li>
        @else
          <li class="nav-item d-none d-lg-block">
            <a href="{{ route('login') }}" class="btn btn-sm btn-light text-primary ms-3 px-4 fw-semibold">Login</a>
          </li>
          <li class="nav-item d-none d-lg-block">
            <a href="{{ route('register') }}" class="btn btn-sm btn-light text-primary ms-2 px-3 fw-semibold">Register</a>
          </li>
        @endauth               
      </ul>
    </div>
  </div>
</nav>

{{--  Navbar Styling --}}
<style>
  .navbar-nav .nav-link:hover {
    color: #e2e2e2 !important;
  }

  .navbar-dark .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 30 30'%3e%3cpath stroke='white' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
  }

  .btn-outline-light:hover {
    background-color: #ffffff;
    color: #2c5364;
  }

  .dropdown-menu {
    min-width: 200px;
  }
  .dropdown-item:hover {
    background-color: #f1f1f1;
    color: #2c5364;
  }
</style>
