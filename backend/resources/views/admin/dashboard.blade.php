@extends('layouts.app')

@section('title', 'Dashboard')
@section('eyebrow', 'Overview')
@section('page-title', 'Dashboard')

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

<div class="page-panel mt-4 p-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <p class="small fw-bold text-uppercase text-primary mb-1">First module</p>
            <h2 class="h5 fw-black mb-0">Recent users</h2>
        </div>
        <a class="btn btn-tee" href="{{ route('admin.users.index') }}">
            <i class="bi bi-people-fill me-1"></i>
            Manage Users
        </a>
    </div>

    <div class="table-responsive">
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
                        <td><span class="status-pill role">{{ str($user->role)->headline() }}</span></td>
                        <td><span class="status-pill {{ $user->status === 'active' ? 'active' : 'muted' }}">{{ str($user->status)->headline() }}</span></td>
                        <td>{{ $user->created_at?->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
