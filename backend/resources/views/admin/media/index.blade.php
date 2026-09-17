@extends('layouts.app')

@section('title', 'Media Library')
@section('eyebrow', 'Content Assets')
@section('page-title', 'Media Library')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Media Library'],
])

@section('content')
@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Uploaded assets',
            'title' => 'Manage media assets',
            'description' => 'Review stored images and files for use across CMS modules.',
        ])
            @slot('actions')
                <a class="btn btn-tee" href="{{ route('admin.media.create') }}">
                    <i class="bi bi-cloud-arrow-up-fill me-1"></i>
                    Upload Media
                </a>
            @endslot
        @endcomponent
    @endslot

    <table id="media-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>Preview</th>
                <th>File</th>
                <th>Type</th>
                <th>Size</th>
                <th>Dimensions</th>
                <th>Uploaded By</th>
                <th>Created</th>
            </tr>
        </thead>
    </table>
@endcomponent
@endsection

@push('scripts')
<script>
    new DataTable('#media-table', {
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.media.data') }}',
        order: [[6, 'desc']],
        columns: [
            { data: 'preview', name: 'path', orderable: false, searchable: false },
            { data: 'file', name: 'original_name', orderable: true, searchable: true },
            { data: 'mime_type', name: 'mime_type' },
            { data: 'size_bytes', name: 'size_bytes', searchable: false },
            { data: 'dimensions', name: 'width', searchable: false },
            { data: 'uploaded_by', name: 'uploadedBy.name', orderable: false },
            { data: 'created_at', name: 'created_at' }
        ]
    });
</script>
@endpush
