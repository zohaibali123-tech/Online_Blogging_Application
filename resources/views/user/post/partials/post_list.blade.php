@foreach ($posts as $post)
    <div class="col-md-4">
        <div class="post-card h-100">
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Post Image">
            <div class="p-4">
                {{-- Blog Badge --}}
                <div class="mb-2">
                    <span class="badge badge-blog">{{ $post->blog->blog_title ?? 'Unknown Blog' }}</span>
                </div>

                {{-- Categories --}}
                <div class="mb-2">
                    @foreach ($post->categories as $category)
                        <span class="badge badge-category">{{ $category->category_title }}</span>
                    @endforeach
                </div>

                <h5 class="fw-bold">{{ $post->post_title }}</h5>
                <p class="mb-3">{{ Str::limit(strip_tags($post->post_summary), 100) }}</p>

                {{-- Attachments Collapse --}}
                @if($post->attachments->where('is_active', 'Active')->count())
                <div class="mt-2">
                    <button class="btn btn-sm btn-outline-primary w-100 text-start" data-bs-toggle="collapse" data-bs-target="#attachments-{{ $post->id }}">
                        <i class="bi bi-paperclip"></i> Show Attachments
                    </button>
                    <div class="collapse mt-2" id="attachments-{{ $post->id }}">
                        <ul class="list-group list-group-flush">
                            @foreach($post->attachments->where('is_active', 'Active') as $att)
                                @php
                                    $ext = pathinfo($att->post_attachment_path, PATHINFO_EXTENSION);
                                    $icons = [
                                        'pdf' => 'bi-file-earmark-pdf',
                                        'doc' => 'bi-file-earmark-word',
                                        'docx' => 'bi-file-earmark-word',
                                        'xls' => 'bi-file-earmark-excel',
                                        'xlsx' => 'bi-file-earmark-excel',
                                        'png' => 'bi-file-earmark-image',
                                        'jpg' => 'bi-file-earmark-image',
                                        'jpeg' => 'bi-file-earmark-image',
                                        'gif' => 'bi-file-earmark-image',
                                    ];
                                    $icon = $icons[strtolower($ext)] ?? 'bi-file-earmark';
                                @endphp
                                <li class="list-group-item d-flex align-items-center small">
                                    <i class="bi {{ $icon }} me-2 text-primary"></i>
                                    <a href="{{ asset('storage/' . $att->post_attachment_path) }}" target="_blank" class="text-decoration-underline">
                                        {{ $att->post_attachment_title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="post-meta">
                        By {{ $post->blog->user->first_name ?? 'Unknown' }}<br>
                        {{ $post->created_at->diffForHumans() }}
                    </small>
                    <a href="{{ route('user.post.show', $post->id) }}" class="btn btn-read-more btn-sm">Read More</a>
                </div>
            </div>
        </div>
    </div>
@endforeach
