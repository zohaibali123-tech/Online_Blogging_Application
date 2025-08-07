@extends('layouts.admin_layout')

@section('title', 'Blog Follow Logs')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Blog Follow Logs</h4>

        {{-- Search and Filter --}}
        <form id="follow-filter-form" class="d-flex gap-2" method="GET">
            <input type="text" name="query" id="query" class="form-control me-2" placeholder="Search by user or blog..." value="{{ request('query') }}" />

            <select name="status" id="status" class="form-select me-2" style="min-width: 150px;">
                <option value="">All</option>
                <option value="Followed" {{ request('status') == 'Followed' ? 'selected' : '' }}>Followed</option>
                <option value="Unfollowed" {{ request('status') == 'Unfollowed' ? 'selected' : '' }}>Unfollowed</option>
            </select>

            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <div id="follow-log-table">
        @include('admin.follow.partials.follow_blog_list')
    </div>
</div>
@endsection

@push('scripts')
<script>
    // AJAX Pagination
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        fetchFollowLogs($(this).attr('href'));
    });

    // Handle Filter/Search Submit
    $('#follow-filter-form').on('submit', function (e) {
        e.preventDefault();
        let query = $('#query').val().trim();
        let status = $('#status').val();
        let url = "{{ route('admin.follow.blog') }}";

        // Build query string
        if (query || status) {
            url += '?';
            if (query) url += 'query=' + encodeURIComponent(query);
            if (query && status) url += '&';
            if (status) url += 'status=' + encodeURIComponent(status);
        }

        fetchFollowLogs(url);
    });

    // Auto-fetch when input is cleared
    $('#query').on('input', function () {
        let query = $(this).val().trim();
        let status = $('#status').val();
        if (query === '') {
            let url = "{{ route('admin.follow.blog') }}";
            if (status) {
                url += '?status=' + encodeURIComponent(status);
            }
            fetchFollowLogs(url);
        }
    });

    // Reusable function to fetch and replace table
    function fetchFollowLogs(url) {
        $.ajax({
            url: url,
            type: 'GET',
            beforeSend: function () {
                $('#follow-log-table').html('<div class="text-center p-4">Loading...</div>');
            },
            success: function (res) {
                $('#follow-log-table').html(res);
            },
            error: function () {
                alert('Failed to load follow logs.');
            }
        });
    }
</script>
@endpush
