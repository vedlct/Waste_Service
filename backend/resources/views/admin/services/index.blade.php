@extends('layouts.app')

@section('title', 'Services')
@section('eyebrow', 'Services CMS')
@section('page-title', 'Services')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Services'],
])

@section('content')
@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Service catalogue',
            'title' => 'Manage services',
            'description' => 'Each service drives a public service page, its hero, content blocks, and cross-links.',
        ])
            @slot('actions')
                <a class="btn btn-outline-tee" href="{{ route('admin.service-categories.index') }}">
                    <i class="bi bi-diagram-3 me-1"></i>
                    Categories
                </a>
                <a class="btn btn-tee" href="{{ route('admin.services.create') }}">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Service
                </a>
            @endslot
        @endcomponent
    @endslot

    <table id="services-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>Hero</th>
                <th>Service</th>
                <th>Category</th>
                <th>Status</th>
                <th>Flags</th>
                <th>Sort</th>
                <th>Published</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
    </table>
@endcomponent
@endsection

@push('scripts')
<script @nonce>
    new DataTable('#services-table', {
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.services.data') }}',
        order: [[5, 'asc']],
        columns: [
            { data: 'preview', name: 'hero_media_id', orderable: false, searchable: false },
            { data: 'service', name: 'name' },
            { data: 'category', name: 'category.name', orderable: false },
            { data: 'status', name: 'status' },
            { data: 'flags', name: 'flags', orderable: false, searchable: false },
            { data: 'sort_order', name: 'sort_order', searchable: false },
            { data: 'published_at', name: 'published_at', searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ]
    });
</script>
@endpush
