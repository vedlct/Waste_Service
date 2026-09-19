@extends('layouts.app')

@section('title', 'Bookings')
@section('eyebrow', 'Operations')
@section('page-title', 'Bookings')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Bookings'],
])

@section('content')
@if ($awaitingPaymentCount > 0)
    <div class="alert alert-warning border-0 rounded-4 d-flex gap-3 align-items-start mb-4">
        <i class="bi bi-hourglass-split fs-4"></i>
        <div>
            <div class="fw-black">{{ $awaitingPaymentCount }} draft booking(s) are waiting for payment</div>
            <div class="small mb-0">Pay-now bookings stay a draft until the payment succeeds. Online payment capture arrives in the payments phase, so these need chasing by hand for now.</div>
        </div>
    </div>
@endif

@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Collections',
            'title' => 'Manage bookings',
            'description' => $submittedCount . ' booking(s) need confirming and ' . $upcomingCount . ' upcoming collection(s) are scheduled.',
        ])
            @slot('actions')
                <a class="btn btn-outline-tee" href="{{ route('admin.bookings.day-sheet') }}" target="_blank" rel="noopener">
                    <i class="bi bi-printer me-1"></i>
                    Day sheet
                </a>
                <a class="btn btn-outline-tee" href="{{ route('admin.bookings.export') }}" id="export-bookings">
                    <i class="bi bi-filetype-csv me-1"></i>
                    Export CSV
                </a>
            @endslot
        @endcomponent

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <label class="form-label fw-bold" for="filter-scope">Show</label>
                <select id="filter-scope" class="form-select select2">
                    <option value="">All bookings</option>
                    <option value="upcoming" @selected(request('scope') === 'upcoming')>Upcoming collections ({{ $upcomingCount }})</option>
                    <option value="awaiting_payment" @selected(request('scope') === 'awaiting_payment')>Awaiting payment ({{ $awaitingPaymentCount }})</option>
                </select>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label fw-bold" for="filter-status">Status</label>
                <select id="filter-status" class="form-select select2">
                    <option value="">Any status</option>
                    @foreach (config('bookings.statuses') as $key => $status)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $status['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label fw-bold" for="filter-payment">Payment</label>
                <select id="filter-payment" class="form-select select2">
                    <option value="">Any payment status</option>
                    @foreach (config('bookings.payment_statuses') as $key => $status)
                        <option value="{{ $key }}" @selected(request('payment_status') === $key)>{{ $status['label'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endslot

    <table id="bookings-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Customer</th>
                <th>Collection</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Total</th>
                <th>Placed</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
    </table>
@endcomponent
@endsection

@push('scripts')
<script @nonce>
    const bookingsTable = new DataTable('#bookings-table', {
        processing: true,
        serverSide: true,
        language: { searchPlaceholder: 'Reference, name, email, phone or postcode' },
        ajax: {
            url: '{{ route('admin.bookings.data') }}',
            data: function (params) {
                params.scope = document.getElementById('filter-scope').value;
                params.status = document.getElementById('filter-status').value;
                params.payment_status = document.getElementById('filter-payment').value;
            }
        },
        order: [[6, 'desc']],
        columns: [
            { data: 'booking', name: 'reference' },
            { data: 'customer', name: 'billingAddress.last_name', orderable: false },
            { data: 'collection_date', name: 'collection_date', searchable: false },
            { data: 'status', name: 'status' },
            { data: 'payment_status', name: 'payment_status' },
            { data: 'total_pence', name: 'total_pence', searchable: false },
            { data: 'created_at', name: 'created_at', searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ]
    });

    $('#filter-scope, #filter-status, #filter-payment').on('change', function () {
        bookingsTable.ajax.reload();
    });

    // The export follows whatever the list is currently filtered and searched by.
    document.getElementById('export-bookings').addEventListener('click', function (event) {
        const params = new URLSearchParams({
            scope: document.getElementById('filter-scope').value,
            status: document.getElementById('filter-status').value,
            payment_status: document.getElementById('filter-payment').value,
            search: bookingsTable.search(),
        });

        event.currentTarget.href = '{{ route('admin.bookings.export') }}?' + params.toString();
    });
</script>
@endpush
