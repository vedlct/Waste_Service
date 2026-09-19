@extends('layouts.app')

@section('title', 'Coverage Areas')
@section('eyebrow', 'Coverage')
@section('page-title', 'Coverage Areas')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Coverage Areas'],
])

@section('content')
@if ($missingPostcodeCount > 0)
    <div class="alert alert-warning border-0 rounded-4 d-flex gap-3 align-items-start mb-4">
        <i class="bi bi-signpost-split-fill fs-4"></i>
        <div>
            <div class="fw-black">{{ $missingPostcodeCount }} area(s) have no postcode prefix</div>
            <div class="small mb-0">Postcode prefixes are what a booking serviceability check will match against, so fill them in before that goes live.</div>
        </div>
    </div>
@endif

@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Where we operate',
            'title' => 'Manage coverage areas',
            'description' => 'Areas belong to a region and carry the postcode prefix and map coordinates.',
        ])
            @slot('actions')
                <a class="btn btn-outline-tee" href="{{ route('admin.coverage-regions.index') }}">
                    <i class="bi bi-diagram-3 me-1"></i>
                    Regions
                </a>
                <a class="btn btn-tee" href="{{ route('admin.coverage-areas.create') }}">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Area
                </a>
            @endslot
        @endcomponent

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-4">
                <label class="form-label fw-bold" for="filter-region">Region</label>
                <select id="filter-region" class="form-select select2">
                    <option value="">All regions</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}" @selected((int) request('region') === $region->id)>{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-4">
                <label class="form-label fw-bold" for="filter-flag">Show</label>
                <select id="filter-flag" class="form-select select2">
                    <option value="">All areas</option>
                    <option value="featured" @selected(request('flag') === 'featured')>Featured only</option>
                    <option value="missing_postcode" @selected(request('flag') === 'missing_postcode')>Missing postcode prefix ({{ $missingPostcodeCount }})</option>
                </select>
            </div>
        </div>
    @endslot

    <table id="coverage-areas-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>Area</th>
                <th>Region</th>
                <th>Postcode</th>
                <th>Coordinates</th>
                <th>Featured</th>
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
    const coverageAreasTable = new DataTable('#coverage-areas-table', {
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.coverage-areas.data') }}',
            data: function (params) {
                const flag = document.getElementById('filter-flag').value;

                params.coverage_region_id = document.getElementById('filter-region').value;
                params.featured = flag === 'featured' ? '1' : '';
                params.missing_postcode = flag === 'missing_postcode' ? '1' : '';
            }
        },
        order: [[6, 'asc']],
        columns: [
            { data: 'area', name: 'name' },
            { data: 'region', name: 'region.name', orderable: false },
            { data: 'postcode_prefix', name: 'postcode_prefix' },
            { data: 'coordinates', name: 'latitude', orderable: false, searchable: false },
            { data: 'is_featured', name: 'is_featured' },
            { data: 'is_active', name: 'is_active' },
            { data: 'sort_order', name: 'sort_order', searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ]
    });

    $('#filter-region, #filter-flag').on('change', function () {
        coverageAreasTable.ajax.reload();
    });
</script>
@endpush
