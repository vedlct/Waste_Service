@extends('layouts.app')

@section('title', 'Load Packages')
@section('eyebrow', 'Pricing Catalogue')
@section('page-title', 'Load Packages')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Load Packages'],
])

@section('content')
@include('admin.partials.placeholder-warning', [
    'count' => $placeholderCount,
    'label' => 'load packages',
])

@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Pricing catalogue',
            'title' => 'Manage load packages',
            'description' => 'Fixed-size man and van options priced by volume, weight, and loading time.',
        ])
            @slot('actions')
                <a class="btn btn-tee" href="{{ route('admin.load-packages.create') }}">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Package
                </a>
            @endslot
        @endcomponent
    @endslot

    <table id="load-packages-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>Package</th>
                <th>Price</th>
                <th>Capacity</th>
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
    new DataTable('#load-packages-table', {
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.load-packages.data') }}',
        order: [[5, 'asc']],
        columns: [
            { data: 'package', name: 'name' },
            { data: 'price_inc_vat_pence', name: 'price_inc_vat_pence', searchable: false },
            { data: 'capacity', name: 'max_weight_kg', orderable: false, searchable: false },
            { data: 'pricing_status', name: 'pricing_status' },
            { data: 'is_active', name: 'is_active' },
            { data: 'sort_order', name: 'sort_order', searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ]
    });
</script>
@endpush
