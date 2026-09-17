@extends('layouts.app')

@section('title', 'Confirm Password')

@section('content')
@component('auth.partials.shell', [
    'eyebrow' => 'Security check',
    'title' => 'Confirm your password',
    'description' => 'Re-enter your password before continuing to this protected admin action.',
    'showcaseTitle' => 'Extra checks keep sensitive admin actions protected.',
])
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-floating mb-4">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
            <label for="password">Password</label>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary login-button w-100">
            Confirm password <i class="bi bi-shield-check ms-1"></i>
        </button>

        @if (Route::has('password.request'))
            <div class="text-center mt-4">
                <a class="fw-bold text-decoration-none" href="{{ route('password.request') }}">Forgot password?</a>
            </div>
        @endif
    </form>
@endcomponent
@endsection
