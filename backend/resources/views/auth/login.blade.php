@extends('layouts.app')

@section('title', 'Login')

@section('content')
@component('auth.partials.shell', [
    'eyebrow' => 'Secure admin login',
    'title' => 'Welcome back',
    'description' => 'Sign in to continue to the MR. TEE admin panel.',
])
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-floating mb-3">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="admin@mrtee.local" required autocomplete="email" autofocus>
            <label for="email">Email address</label>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-floating mb-3">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
            <label for="password">Password</label>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            @if (Route::has('password.request'))
                <a class="fw-bold text-decoration-none" href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary login-button w-100">
            Sign in <i class="bi bi-arrow-right ms-1"></i>
        </button>
    </form>
@endcomponent
@endsection
