@extends('layouts.app')

@section('title', 'Users')
@section('eyebrow', 'User Management')
@section('page-title', 'Users')

@section('content')
<div class="page-panel p-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <p class="small fw-bold text-uppercase text-primary mb-1">Admin accounts</p>
            <h2 class="h5 fw-black mb-0">Manage panel users</h2>
        </div>
        <a class="btn btn-tee" href="{{ route('admin.users.create') }}">
            <i class="bi bi-plus-lg me-1"></i>
            Add User
        </a>
    </div>

    <div class="table-responsive">
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
    </div>
</div>
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
