@extends('layouts.app')

@section('title', 'Extra Charges')
@section('eyebrow', 'Pricing Catalogue')
@section('page-title', 'Extra Charges')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Extra Charges'],
])

@section('content')
@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Pricing catalogue',
            'title' => 'Manage extra charges',
            'description' => 'Surcharges and fees added on top of the booking total, such as Saturday collection.',
        ])
            @slot('actions')
                <a class="btn btn-tee" href="{{ route('admin.extra-charges.create') }}">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Charge
                </a>
            @endslot
        @endcomponent
    @endslot

    <table id="extra-charges-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>Charge</th>
                <th>Amount</th>
                <th>Type</th>
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
    new DataTable('#extra-charges-table', {
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.extra-charges.data') }}',
        order: [[5, 'asc']],
        columns: [
            { data: 'charge', name: 'name' },
            { data: 'amount_pence', name: 'amount_pence', searchable: false },
            { data: 'charge_type', name: 'charge_type' },
            { data: 'pricing_status', name: 'pricing_status' },
            { data: 'is_active', name: 'is_active' },
            { data: 'sort_order', name: 'sort_order', searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ]
    });
</script>
@endpush
