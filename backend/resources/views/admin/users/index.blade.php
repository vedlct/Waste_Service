@extends('layouts.app')

@section('title', 'Users')
@section('eyebrow', 'User Management')
@section('page-title', 'Users')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Users'],
])

@section('content')
@component('admin.partials.table-card')
    @slot('header')
        @component('admin.partials.page-header', ['eyebrow' => 'Admin accounts', 'title' => 'Manage panel users'])
            @slot('actions')
                <a class="btn btn-tee" href="{{ route('admin.users.create') }}">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add User
                </a>
            @endslot
        @endcomponent
    @endslot

    <table id="users-table" class="table table-hover w-100">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Last Login</th>
                <th>Created</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
    </table>
@endcomponent
@endsection

@push('scripts')
<script>
    new DataTable('#users-table', {
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.users.data') }}',
        order: [[5, 'desc']],
        columns: [
            { data: 'account', name: 'name', orderable: true, searchable: true },
            { data: 'email', name: 'email' },
            { data: 'role', name: 'role' },
            { data: 'status', name: 'status' },
            { data: 'last_login_at', name: 'last_login_at' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
        ]
    });
</script>
@endpush
