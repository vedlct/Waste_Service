@extends('layouts.app')

@section('title', 'FAQs')
@section('eyebrow', 'Content')
@section('page-title', 'FAQs')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'FAQs'],
])

@section('content')
@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Questions and answers',
            'title' => 'Manage FAQs',
            'description' => 'General FAQs appear on the FAQ page. Assign one to a service to show it on that service page instead.',
        ])
            @slot('actions')
                <a class="btn btn-tee" href="{{ route('admin.faqs.create') }}">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add FAQ
                </a>
            @endslot
        @endcomponent

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-4">
                <label class="form-label fw-bold" for="filter-scope">Scope</label>
                <select id="filter-scope" class="form-select select2">
                    <option value="">All FAQs</option>
                    <option value="general">General only ({{ $generalCount }})</option>
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

    <table id="faqs-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>Question</th>
                <th>Scope</th>
                <th>Status</th>
                <th>Sort</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
    </table>
@endcomponent
@endsection

@push('scripts')
<script @nonce>
    const faqsTable = new DataTable('#faqs-table', {
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.faqs.data') }}',
            data: function (params) {
                params.scope = document.getElementById('filter-scope').value;
                params.service_id = document.getElementById('filter-service').value;
            }
        },
        order: [[3, 'asc']],
        columns: [
            { data: 'faq', name: 'question' },
            { data: 'scope', name: 'service.name', orderable: false },
            { data: 'is_active', name: 'is_active' },
            { data: 'sort_order', name: 'sort_order', searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ]
    });

    $('#filter-scope, #filter-service').on('change', function () {
        faqsTable.ajax.reload();
    });
</script>
@endpush
