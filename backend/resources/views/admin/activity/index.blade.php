@extends('layouts.app')

@section('title', 'Activity Log')
@section('eyebrow', 'Security')
@section('page-title', 'Activity Log')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Activity Log'],
])

@section('content')
@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', [
            'eyebrow' => 'Audit trail',
            'title' => 'Admin activity',
            'description' => 'Every change made by a signed-in admin, with the before and after values. Kept for '.config('admin_access.activity_retention_days').' days.',
        ])
        @endcomponent

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-4">
                <label class="form-label fw-bold" for="filter-user">User</label>
                <select id="filter-user" class="form-select select2">
                    <option value="">Anyone</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label fw-bold" for="filter-action">Action</label>
                <select id="filter-action" class="form-select select2">
                    <option value="">Any action</option>
                    @foreach (['created', 'updated', 'archived', 'deleted'] as $action)
                        <option value="{{ $action }}">{{ \Illuminate\Support\Str::headline($action) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-4">
                <label class="form-label fw-bold" for="filter-subject">Record type</label>
                <select id="filter-subject" class="form-select select2">
                    <option value="">Any record</option>
                    @foreach ($subjectTypes as $type)
                        <option value="{{ $type }}">{{ \Illuminate\Support\Str::headline(class_basename($type)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endslot

    <table id="activity-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>When</th>
                <th>Who</th>
                <th>Action</th>
                <th>Record</th>
                <th>Changes</th>
            </tr>
        </thead>
    </table>
@endcomponent
@endsection

@push('scripts')
<script @nonce>
    const activityTable = new DataTable('#activity-table', {
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.activity.data') }}',
            data: function (params) {
                params.user_id = document.getElementById('filter-user').value;
                params.action = document.getElementById('filter-action').value;
                params.subject_type = document.getElementById('filter-subject').value;
            }
        },
        order: [[0, 'desc']],
        columns: [
            { data: 'created_at', name: 'created_at', searchable: false },
            { data: 'who', name: 'user.name', orderable: false },
            { data: 'action', name: 'action' },
            { data: 'description', name: 'description' },
            { data: 'details', name: 'changes', orderable: false, searchable: false }
        ]
    });

    $('#filter-user, #filter-action, #filter-subject').on('change', function () {
        activityTable.ajax.reload();
    });
</script>
@endpush
