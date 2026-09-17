@extends('layouts.app')

@section('title', 'Set New Password')

@section('content')
@component('auth.partials.shell', [
    'eyebrow' => 'Password recovery',
    'title' => 'Set a new password',
    'description' => 'Choose a strong password to protect your admin account.',
    'showcaseTitle' => 'Keep access secure as the admin panel grows.',
])
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-floating mb-3">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" placeholder="admin@mrtee.local" required autocomplete="email" autofocus>
            <label for="email">Email address</label>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-floating mb-3">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="New password" required autocomplete="new-password">
            <label for="password">New password</label>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-floating mb-4">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm new password" required autocomplete="new-password">
            <label for="password-confirm">Confirm new password</label>
        </div>

        <button type="submit" class="btn btn-primary login-button w-100">
            Reset password <i class="bi bi-key-fill ms-1"></i>
        </button>
    </form>
@endcomponent
@endsection
