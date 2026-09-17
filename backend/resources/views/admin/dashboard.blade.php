@extends('layouts.app')

@section('title', 'Dashboard')
@section('eyebrow', 'Overview')
@section('page-title', 'Dashboard')
@php($breadcrumbs = [['label' => 'Dashboard']])

@section('content')
<div class="row g-3">
    @foreach ([
        ['label' => 'Users', 'value' => $stats['users'], 'icon' => 'people-fill'],
        ['label' => 'Services', 'value' => $stats['services'], 'icon' => 'truck'],
        ['label' => 'Price Items', 'value' => $stats['priceItems'], 'icon' => 'tags-fill'],
        ['label' => 'Bookings', 'value' => $stats['bookings'], 'icon' => 'calendar-check-fill'],
    ] as $stat)
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="metric-icon"><i class="bi bi-{{ $stat['icon'] }}"></i></div>
                    <span class="small fw-bold text-muted">Live</span>
                </div>
                <div class="display-6 fw-black mt-3">{{ number_format($stat['value']) }}</div>
                <div class="text-muted fw-semibold">{{ $stat['label'] }}</div>
            </div>
        </div>
    @endforeach
</div>

@component('admin.partials.table-card', ['class' => 'mt-4 p-4'])
    @slot('header')
        @component('admin.partials.page-header', ['eyebrow' => 'First module', 'title' => 'Recent users', 'class' => 'mb-3'])
            @slot('actions')
                <a class="btn btn-tee" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people-fill me-1"></i>
                    Manage Users
                </a>
            @endslot
        @endcomponent
    @endslot

    @if ($recentUsers->isEmpty())
        @component('admin.partials.empty-state', [
            'icon' => 'people-fill',
            'title' => 'No users yet',
            'message' => 'Create the first admin account to start managing the panel.',
        ])
            @slot('actions')
                <a class="btn btn-tee" href="{{ route('admin.users.create') }}">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add User
                </a>
            @endslot
        @endcomponent
    @else
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recentUsers as $user)
                    <tr>
                        <td class="fw-bold">{{ $user->name }}</td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            @include('admin.partials.status-badge', [
                                'label' => str($user->role)->headline(),
                                'class' => 'role',
                            ])
                        </td>
                        <td>
                            @include('admin.partials.status-badge', [
                                'label' => str($user->status)->headline(),
                                'class' => $user->status === 'active' ? 'active' : 'muted',
                            ])
                        </td>
                        <td>{{ $user->created_at?->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endcomponent
@endsection
