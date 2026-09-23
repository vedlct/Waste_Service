@extends('layouts.app')

@section('title', 'Price Categories')
@section('eyebrow', 'Pricing Catalogue')
@section('page-title', 'Price Categories')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Price Categories'],
])

@section('content')
@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Pricing catalogue',
            'title' => 'Manage price categories',
            'description' => 'Categories group the priced items customers pick from on the booking pages.',
        ])
            @slot('actions')
                <a class="btn btn-outline-tee" href="{{ route('admin.service-items.index') }}">
                    <i class="bi bi-list-ul me-1"></i>
                    Service Items
                </a>
                <a class="btn btn-tee" href="{{ route('admin.price-categories.create') }}">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Category
                </a>
            @endslot
        @endcomponent
    @endslot

    <table id="price-categories-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>Image</th>
                <th>Category</th>
                <th>Status</th>
                <th>Items</th>
                <th>Sort</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
    </table>
@endcomponent
@endsection

@push('scripts')
<script @nonce>
    new DataTable('#price-categories-table', {
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.price-categories.data') }}',
        order: [[4, 'asc']],
        columns: [
            { data: 'preview', name: 'image_id', orderable: false, searchable: false },
            { data: 'category', name: 'name' },
            { data: 'is_active', name: 'is_active' },
            { data: 'items', name: 'items_count', orderable: false, searchable: false },
            { data: 'sort_order', name: 'sort_order', searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ]
    });
</script>
@endpush
