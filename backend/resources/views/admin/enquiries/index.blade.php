@extends('layouts.app')

@section('title', 'Contact Enquiries')
@section('eyebrow', 'Enquiries')
@section('page-title', 'Contact Enquiries')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Contact Enquiries'],
])

@section('content')
@if ($unassignedOpenCount > 0)
    <div class="alert alert-warning border-0 rounded-4 d-flex gap-3 align-items-start mb-4">
        <i class="bi bi-inbox-fill fs-4"></i>
        <div>
            <div class="fw-black">{{ $unassignedOpenCount }} open enquiry(s) have nobody assigned</div>
            <div class="small mb-0">Assign an owner so it is clear who is replying to the customer.</div>
        </div>
    </div>
@endif

@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Website contact form',
            'title' => 'Manage enquiries',
            'description' => $openCount . ' enquiry(s) still need a reply.',
        ])
        @endcomponent

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <label class="form-label fw-bold" for="filter-scope">Show</label>
                <select id="filter-scope" class="form-select select2">
                    <option value="">All enquiries</option>
                    <option value="open" @selected(! in_array(request('scope'), ['unassigned', 'all'], true))>Open only ({{ $openCount }})</option>
                    <option value="unassigned" @selected(request('scope') === 'unassigned')>Open and unassigned ({{ $unassignedOpenCount }})</option>
                </select>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label fw-bold" for="filter-status">Status</label>
                <select id="filter-status" class="form-select select2">
                    <option value="">Any status</option>
                    @foreach (config('enquiries.statuses') as $key => $status)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $status['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-4">
                <label class="form-label fw-bold" for="filter-assignee">Assigned to</label>
                <select id="filter-assignee" class="form-select select2">
                    <option value="">Anyone</option>
                    @foreach ($assignees as $assignee)
                        <option value="{{ $assignee->id }}">{{ $assignee->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endslot

    <table id="enquiries-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>Contact</th>
                <th>Enquiry</th>
                <th>Status</th>
                <th>Source</th>
                <th>Assigned</th>
                <th>Received</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
    </table>
@endcomponent
@endsection

@push('scripts')
<script @nonce>
    const enquiriesTable = new DataTable('#enquiries-table', {
        processing: true,
        serverSide: true,
        language: { searchPlaceholder: 'Name, email, phone or message' },
        ajax: {
            url: '{{ route('admin.enquiries.data') }}',
            data: function (params) {
                params.scope = document.getElementById('filter-scope').value;
                params.status = document.getElementById('filter-status').value;
                params.assigned_to = document.getElementById('filter-assignee').value;
            }
        },
        order: [[5, 'desc']],
        columns: [
            { data: 'contact', name: 'name' },
            { data: 'enquiry', name: 'message' },
            { data: 'status', name: 'status' },
            { data: 'source', name: 'source' },
            { data: 'assignee', name: 'assignee.name', orderable: false },
            { data: 'created_at', name: 'created_at', searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ]
    });

    $('#filter-scope, #filter-status, #filter-assignee').on('change', function () {
        enquiriesTable.ajax.reload();
    });
</script>
@endpush
