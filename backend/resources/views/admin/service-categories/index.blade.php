@extends('layouts.app')

@section('title', 'Service Categories')
@section('eyebrow', 'Services CMS')
@section('page-title', 'Service Categories')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Service Categories'],
])

@section('content')
@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Service catalogue',
            'title' => 'Manage service categories',
            'description' => 'Categories group services for the website navigation and the admin catalogue.',
        ])
            @slot('actions')
                <a class="btn btn-tee" href="{{ route('admin.service-categories.create') }}">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Category
                </a>
            @endslot
        @endcomponent
    @endslot

    <table id="service-categories-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>Category</th>
                <th>Parent</th>
                <th>Status</th>
                <th>Usage</th>
                <th>Sort</th>
                <th>Created</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
    </table>
@endcomponent
@endsection

@push('scripts')
<script @nonce>
    new DataTable('#service-categories-table', {
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.service-categories.data') }}',
        order: [[4, 'asc']],
        columns: [
            { data: 'category', name: 'name' },
            { data: 'parent', name: 'parent.name', orderable: false },
            { data: 'is_active', name: 'is_active' },
            { data: 'usage', name: 'usage', orderable: false, searchable: false },
            { data: 'sort_order', name: 'sort_order', searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ]
    });
</script>
@endpush
