@extends('layouts.app')

@section('title', 'Pages & SEO')
@section('eyebrow', 'Content')
@section('page-title', 'Pages & SEO')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Pages & SEO'],
])

@section('content')
@if ($placeholderCount > 0)
    <div class="alert alert-warning border-0 rounded-4 d-flex gap-3 align-items-start mb-4">
        <i class="bi bi-search fs-4"></i>
        <div>
            <div class="fw-black">{{ $placeholderCount }} page(s) still have a missing or placeholder meta description</div>
            <div class="small mb-0">Search engines show this text under the page title. Replace it before launch.</div>
        </div>
    </div>
@endif

@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Website pages',
            'title' => 'Manage page titles and search metadata',
            'description' => 'The website\'s pages are built in code, so they cannot be added or removed here; their titles and SEO can.',
        ])
        @endcomponent
    @endslot

    <table id="pages-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>Page</th>
                <th>Search result</th>
                <th>Indexing</th>
                <th>Updated</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
    </table>
@endcomponent
@endsection

@push('scripts')
<script @nonce>
    new DataTable('#pages-table', {
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.pages.data') }}',
        order: [[3, 'desc']],
        columns: [
            { data: 'page', name: 'title' },
            { data: 'seo', name: 'meta_description' },
            { data: 'is_indexable', name: 'is_indexable' },
            { data: 'updated_at', name: 'updated_at', searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ]
    });
</script>
@endpush
