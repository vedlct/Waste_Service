@extends('layouts.app')

@section('title', 'My Profile')
@section('eyebrow', 'Account Settings')
@section('page-title', 'My Profile')
@php($breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'My Profile'],
])

@section('content')
<div class="row g-4">
    <div class="col-xl-7">
        @component('admin.partials.panel', ['class' => 'p-4 h-100'])
            @component('admin.partials.page-header', ['eyebrow' => 'Profile details', 'title' => 'Your admin account'])
                @slot('actions')
                    @include('admin.partials.status-badge', [
                        'label' => str($user->status)->headline(),
                        'class' => $user->status === 'active' ? 'active' : 'muted',
                    ])
                @endslot
            @endcomponent

            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold" for="name">Name</label>
                        <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold" for="email">Email</label>
                        <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Role</label>
                        <div class="form-control bg-light">{{ str($user->role)->headline() }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Last login</label>
                        <div class="form-control bg-light">{{ $user->last_login_at?->format('d M Y, h:i A') ?? 'Never recorded' }}</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end border-top mt-4 pt-4">
                    <button class="btn btn-tee" type="submit">
                        <i class="bi bi-check2 me-1"></i>
                        Save Profile
                    </button>
                </div>
            </form>
        @endcomponent
    </div>

    <div class="col-xl-5">
        @component('admin.partials.panel', ['class' => 'p-4 h-100'])
            <div class="metric-icon mb-3"><i class="bi bi-shield-lock-fill"></i></div>
            <p class="small fw-bold text-uppercase text-primary mb-1">Password</p>
            <h2 class="h5 fw-black mb-3">Update password</h2>

            <form method="POST" action="{{ route('admin.profile.password.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold" for="current_password">Current password</label>
                    <input id="current_password" type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password" required>
                    @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" for="password">New password</label>
                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold" for="password_confirmation">Confirm new password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required>
                </div>

                <button class="btn btn-tee w-100" type="submit">
                    <i class="bi bi-key-fill me-1"></i>
                    Update Password
                </button>
            </form>
        @endcomponent
    </div>
</div>
@endsection
