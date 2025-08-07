@extends('layouts.admin_layout')

@section('title', 'Contact Messages')

@section('content')

{{-- Alert Placeholder --}}
<div id="alert-message" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4 class="fw-bold mb-0">Contact Form Messages</h4>

        <form id="search-form" class="d-flex" method="GET">
            <input type="text" name="search" id="search" class="form-control me-2" placeholder="Search by name, email, or subject" value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    {{-- AJAX Render Target --}}
    <div id="message-list-container">
        @include('admin.contact.partials.message_list')
    </div>
</div>

{{-- Reply Modal --}}
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="reply-form">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="replyModalLabel">Reply to Message</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <input type="hidden" id="msg_id">
            <div class="mb-3">
              <label for="to_email" class="form-label">To Email</label>
              <input type="email" class="form-control" id="to_email" readonly>
            </div>
            <div class="mb-3">
              <label for="reply_subject" class="form-label">Subject</label>
              <input type="text" class="form-control" id="reply_subject">
            </div>
            <div class="mb-3">
              <label for="reply_body" class="form-label">Message</label>
              <textarea class="form-control" id="reply_body" rows="5" required></textarea>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Send Reply</button>
          </div>
        </div>
      </form>
    </div>
</div>  
@endsection

@push('scripts')
<script>
    // Show full message in modal
    $(document).on('click', '.view-message-btn', function () {
        const message = $(this).data('message');
        $('#full-message-content').text(message);
    });

    // Pagination 
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        fetchMessages($(this).attr('href'));
    });

    // search form
    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        fetchMessages();
    });

    // Reset to default if input cleared
    $('#search').on('input', function () {
        if ($(this).val().trim() === '') {
            fetchMessages();
        }
    });

    function fetchMessages(url = "{{ route('admin.contact.index') }}") {
        const search = $('#search').val();

        $.ajax({
            url: url + '?search=' + encodeURIComponent(search),
            type: 'GET',
            beforeSend: function () {
                $('#message-list-container').html('<div class="text-center p-4">Loading...</div>');
            },
            success: function (res) {
                $('#message-list-container').html(res);
            },
            error: function () {
                showMessage('error', 'Failed to load messages.');
            }
        });
    }

    // Open reply modal
    $(document).on('click', '.reply-btn', function () {
        $('#msg_id').val($(this).data('id'));
        $('#to_email').val($(this).data('email'));
        $('#reply_subject').val($(this).data('subject'));
        $('#reply_body').val('');
        $('#replyModal').modal('show');
    });

    // Reply submit
    $('#reply-form').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('admin.contact.reply') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: $('#msg_id').val(),
                to_email: $('#to_email').val(),
                subject: $('#reply_subject').val(),
                body: $('#reply_body').val()
            },
            success: function (res) {
                $('#replyModal').modal('hide');
                showMessage('success', res.message);
            },
            error: function () {
                showMessage('error', 'Failed to send email.');
            }
        });
    });

    // Delete
    $(document).on('click', '.delete-btn', function () {
        let btn = $(this);
        let id = btn.data('id');

        if (confirm('Are you sure you want to delete this message?')) {
            $.ajax({
                url: "{{ route('admin.contact.destroy', ':id') }}".replace(':id', id),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    btn.closest('tr').fadeOut(500, function () {
                        $(this).remove();
                    });
                    showMessage('success', res.message);
                },
                error: function () {
                    showMessage('error', 'Failed to delete message.');
                }
            });
        }
    });

    // Custom Bootstrap Toast Message Function
    function showMessage(type, message) {
        let alertType = type === 'success' ? 'alert-success' : 'alert-danger';
        let icon = type === 'success' 
            ? '<i class="bi bi-check-circle-fill me-2"></i>' 
            : '<i class="bi bi-x-circle-fill me-2"></i>';

        let toast = `
        <div class="alert ${alertType} alert-dismissible fade show shadow" role="alert">
            ${icon}${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        `;

        $('#alert-message').html(toast);

        setTimeout(function() {
            $('#alert-message .alert').alert('close');
        }, 4000);
    }

</script>
@endpush
