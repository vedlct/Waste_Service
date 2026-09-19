@extends('layouts.app')

@section('title', 'Coverage Regions')
@section('eyebrow', 'Coverage')
@section('page-title', 'Coverage Regions')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Coverage Regions'],
])

@section('content')
@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Where we operate',
            'title' => 'Manage coverage regions',
            'description' => 'Regions group the areas shown on the Areas We Cover page.',
        ])
            @slot('actions')
                <a class="btn btn-outline-tee" href="{{ route('admin.coverage-areas.index') }}">
                    <i class="bi bi-geo-alt me-1"></i>
                    Coverage Areas
                </a>
                <a class="btn btn-tee" href="{{ route('admin.coverage-regions.create') }}">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Region
                </a>
            @endslot
        @endcomponent
    @endslot

    <table id="coverage-regions-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>Region</th>
                <th>Status</th>
                <th>Areas</th>
                <th>Sort</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
    </table>
@endcomponent
@endsection

@push('scripts')
<script @nonce>
    new DataTable('#coverage-regions-table', {
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.coverage-regions.data') }}',
        order: [[3, 'asc']],
        columns: [
            { data: 'region', name: 'name' },
            { data: 'is_active', name: 'is_active' },
            { data: 'areas', name: 'areas_count', orderable: false, searchable: false },
            { data: 'sort_order', name: 'sort_order', searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ]
    });
</script>
@endpush
