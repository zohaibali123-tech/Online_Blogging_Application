// Flash Toast Message 
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
    setTimeout(() => $('#alert-message .alert').alert('close'), 4000);
}

$(document).ready(function () {

    // AJAX Pagination
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        fetchUsers(url);
    });

    // Toggle is_active
    $(document).on('click', '.toggle-status-btn', function () {
        const userId = $(this).data('id');
        const $btn = $(this);

        $.ajax({
            url: `/admin/user/${userId}/toggle`,
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                if (res.status === 'success') {
                    const newStatus = res.new_status;
                    const isActive = newStatus === 'Active';

                    $btn
                        .text(isActive ? 'Deactivate' : 'Activate')
                        .removeClass('btn-success btn-secondary')
                        .addClass(isActive ? 'btn-secondary' : 'btn-success');

                    const $badge = $btn.closest('tr').find('.user-status-badge');
                    $badge
                        .text(newStatus)
                        .removeClass('bg-success bg-secondary')
                        .addClass(isActive ? 'bg-success' : 'bg-secondary');

                    showMessage('success', res.message);
                }
            },
            error: function () {
                showMessage('error', 'Failed to toggle user status.');
            }
        });
    });

    // Approve / Reject / Pending
    $(document).on('click', '.approve-btn', function () {
        const userId = $(this).data('id');
        const status = $(this).data('status');
        const $btn = $(this);

        $.ajax({
            url: `/admin/user/${userId}/approve`,
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { status },
            success: function (res) {
                if (res.status === 'success') {
                    const newStatus = res.new_status;

                    if (window.location.href.includes('approved') ||
                        window.location.href.includes('pending') ||
                        window.location.href.includes('rejected')) {
                        $btn.closest('tr').fadeOut();
                    }

                    const $badge = $btn.closest('tr').find('.user-approval-badge');
                    $badge
                        .text(newStatus)
                        .removeClass('bg-warning bg-success bg-danger text-dark')
                        .addClass(newStatus === 'Approved' ? 'bg-success' :
                            (newStatus === 'Rejected' ? 'bg-danger' : 'bg-warning text-dark'));

                    const $btnGroup = $btn.closest('td').find('.btn-group');
                    let buttons = '';
                    ['Approved', 'Pending', 'Rejected'].forEach((s) => {
                        if (s !== newStatus) {
                            buttons += `
                                <button class="btn btn-sm ${s === 'Approved' ? 'btn-success' : (s === 'Rejected' ? 'btn-danger' : 'btn-warning text-dark')} approve-btn" 
                                    data-id="${userId}" data-status="${s}">
                                    ${s}
                                </button>
                            `;
                        }
                    });
                    $btnGroup.html(buttons);

                    showMessage('success', res.message);
                }
            },
            error: function () {
                showMessage('error', 'Failed to update approval status.');
            }
        });
    });

    // Search submit
    $('.search-wrapper button[type="submit"]').on('click', function (e) {
        e.preventDefault();
        let searchVal = $('input[name="search"]').val();
        fetchUsers(searchVal);
    });

    // Auto fetch when input cleared
    $('input[name="search"]').on('input', function () {
        let val = $(this).val();
        if (val === '') {
            fetchUsers('');
        }
    });

    //  Fetch Users (search or paginate) 
    function fetchUsers(url_or_search = '') {
        let url = '';

        if (url_or_search.includes('http')) {
            url = url_or_search;
        } else {
            let route = $('meta[name="user-list-route"]').attr('content');
            url = route + '?search=' + encodeURIComponent(url_or_search);
        }

        $.ajax({
            url: url,
            type: 'GET',
            beforeSend: function () {
                $('#user-list').html('<div class="text-center my-4"><div class="spinner-border text-primary"></div></div>');
            },
            success: function (res) {
                $('#user-list').html(res);
            },
            error: function () {
                showMessage('error', 'Failed to fetch users.');
            }
        });
    }

});
