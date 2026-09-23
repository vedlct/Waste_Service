@extends('layouts.app')

@section('title', 'Service Items')
@section('eyebrow', 'Pricing Catalogue')
@section('page-title', 'Service Items')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Service Items'],
])

@section('content')
@include('admin.partials.placeholder-warning', [
    'count' => $placeholderCount,
    'label' => 'service items',
])

@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Pricing catalogue',
            'title' => 'Manage service items',
            'description' => 'Every priced line a customer can add to a booking, grouped by price category.',
        ])
            @slot('actions')
                <a class="btn btn-outline-tee" href="{{ route('admin.price-categories.index') }}">
                    <i class="bi bi-tags me-1"></i>
                    Categories
                </a>
                <a class="btn btn-tee" href="{{ route('admin.service-items.create') }}">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Item
                </a>
            @endslot
        @endcomponent

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-4">
                <label class="form-label fw-bold" for="filter-category">Filter by category</label>
                <select id="filter-category" class="form-select select2">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) request('category') === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-4">
                <label class="form-label fw-bold" for="filter-status">Filter by pricing status</label>
                <select id="filter-status" class="form-select select2">
                    <option value="">All statuses</option>
                    @foreach (config('pricing.statuses') as $key => $status)
                        <option value="{{ $key }}" @selected(request('pricing_status') === $key)>{{ $status['label'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endslot

    <table id="service-items-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>Image</th>
                <th>Item</th>
                <th>Category</th>
                <th>Price</th>
                <th>Pricing</th>
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
    const serviceItemsTable = new DataTable('#service-items-table', {
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.service-items.data') }}',
            data: function (params) {
                params.price_category_id = document.getElementById('filter-category').value;
                params.pricing_status = document.getElementById('filter-status').value;
            }
        },
        order: [[6, 'asc']],
        columns: [
            { data: 'preview', name: 'image_id', orderable: false, searchable: false },
            { data: 'item', name: 'name' },
            { data: 'category', name: 'priceCategory.name', orderable: false },
            { data: 'price_pence', name: 'price_pence', searchable: false },
            { data: 'pricing_status', name: 'pricing_status' },
            { data: 'is_active', name: 'is_active' },
            { data: 'sort_order', name: 'sort_order', searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ]
    });

    $('#filter-category, #filter-status').on('change', function () {
        serviceItemsTable.ajax.reload();
    });
</script>
@endpush
