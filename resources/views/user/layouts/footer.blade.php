<footer class="text-white pt-4" style="background: linear-gradient(135deg, #0f2027, #2c5364);">
    <div class="container text-center text-md-start">
      <div class="row">
  
        {{-- Brand & Description --}}
        <div class="col-md-4 mb-4">
          <h5 class="fw-bold">{{ $siteSetting->site_name ?? 'MyBlog' }}</h5>
          <p class="small">
            {{ $siteSetting->footer_description ?? 'Your go-to platform to share thoughts, read amazing blogs, and connect with the world.' }}
          </p>
        </div>
  
        {{-- Quick Links --}}
        <div class="col-md-4 mb-4">
          <h6 class="fw-bold">Quick Links</h6>
          <ul class="list-unstyled">
            <li><a href="{{ route('user.index') }}" class="text-white text-decoration-none">Home</a></li>
            <li><a href="{{ route('user.blog.index') }}" class="text-white text-decoration-none">Blogs</a></li>
            <li><a href="{{ route('about') }}" class="text-white text-decoration-none">About</a></li>
            <li><a href="{{ route('contact.show') }}" class="text-white text-decoration-none">Contact</a></li>
          </ul>
        </div>
  
        {{-- Social Icons --}}
        <div class="col-md-4 mb-4 text-md-end text-center">
          <h6 class="fw-bold">Follow Us</h6>
          @if(isset($siteSetting))
            @if($siteSetting->facebook_link)
              <a href="{{ $siteSetting->facebook_link }}" target="_blank" class="text-white me-3 fs-5"><i class="bi bi-facebook"></i></a>
            @endif
            @if($siteSetting->twitter_link)
              <a href="{{ $siteSetting->twitter_link }}" target="_blank" class="text-white me-3 fs-5"><i class="bi bi-twitter"></i></a>
            @endif
            @if($siteSetting->instagram_link)
              <a href="{{ $siteSetting->instagram_link }}" target="_blank" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
            @endif
          @endif
        </div>
  
      </div>
    </div>
  
    <div class="text-center py-3 mt-2 border-top border-white">
      <small>&copy; {{ date('Y') }} MyBlog. All rights reserved.</small>
    </div>
  </footer>
  