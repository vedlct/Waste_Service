@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
@component('auth.partials.shell', [
    'eyebrow' => 'Password recovery',
    'title' => 'Reset your password',
    'description' => 'Enter your admin email and we will send a secure reset link.',
    'showcaseTitle' => 'Recover access without leaving the admin workflow.',
])
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-floating mb-4">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="admin@mrtee.local" required autocomplete="email" autofocus>
            <label for="email">Email address</label>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary login-button w-100">
            Send reset link <i class="bi bi-envelope-arrow-up ms-1"></i>
        </button>

        <div class="text-center mt-4">
            <a class="fw-bold text-decoration-none" href="{{ route('login') }}">Back to login</a>
        </div>
    </form>
@endcomponent
@endsection
