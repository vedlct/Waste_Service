@extends('layouts.app')

@section('title', 'Reviews')
@section('eyebrow', 'Content')
@section('page-title', 'Reviews')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Reviews'],
])

@section('content')
@if ($pendingCount > 0)
    <div class="alert alert-warning border-0 rounded-4 d-flex gap-3 align-items-start mb-4">
        <i class="bi bi-hourglass-split fs-4"></i>
        <div>
            <div class="fw-black">{{ $pendingCount }} review(s) waiting for moderation</div>
            <div class="small mb-0">Pending reviews are never shown on the website. Publish or reject them from the list below.</div>
        </div>
    </div>
@endif

@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Customer feedback',
            'title' => 'Moderate reviews',
            'description' => $publishedCount . ' review(s) are currently live on the website.',
        ])
            @slot('actions')
                <a class="btn btn-tee" href="{{ route('admin.reviews.create') }}">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Review
                </a>
            @endslot
        @endcomponent

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <label class="form-label fw-bold" for="filter-status">Status</label>
                <select id="filter-status" class="form-select select2">
                    <option value="">All statuses</option>
                    @foreach (config('reviews.statuses') as $key => $status)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $status['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label fw-bold" for="filter-rating">Rating</label>
                <select id="filter-rating" class="form-select select2">
                    <option value="">Any rating</option>
                    @for ($star = config('reviews.max_rating', 5); $star >= 1; $star--)
                        <option value="{{ $star }}">{{ $star }} star{{ $star === 1 ? '' : 's' }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-sm-6 col-lg-4">
                <label class="form-label fw-bold" for="filter-service">Service</label>
                <select id="filter-service" class="form-select select2">
                    <option value="">All services</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endslot

    <table id="reviews-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>Reviewer</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Service</th>
                <th>Source</th>
                <th>Status</th>
                <th>Published</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
    </table>
@endcomponent
@endsection

@push('scripts')
<script @nonce>
    const reviewsTable = new DataTable('#reviews-table', {
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.reviews.data') }}',
            data: function (params) {
                params.status = document.getElementById('filter-status').value;
                params.rating = document.getElementById('filter-rating').value;
                params.service_id = document.getElementById('filter-service').value;
            }
        },
        order: [[6, 'desc']],
        columns: [
            { data: 'reviewer', name: 'reviewer_name' },
            { data: 'rating', name: 'rating', searchable: false },
            { data: 'body', name: 'body' },
            { data: 'service', name: 'service.name', orderable: false },
            { data: 'source', name: 'source' },
            { data: 'status', name: 'status' },
            { data: 'published_at', name: 'published_at', searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ]
    });

    $('#filter-status, #filter-rating, #filter-service').on('change', function () {
        reviewsTable.ajax.reload();
    });
</script>
@endpush
