@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<h1 class="auth-title">Reset Password</h1>
<p class="auth-subtitle">Create a new password for your account</p>

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="mb-3">
        <label for="email" class="form-label">{{ __('Email Address') }}</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Enter new password">
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm new password">
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">
            {{ __('Reset Password') }}
        </button>
    </div>

    <div class="text-center">
        <p>Remember your password? <a href="{{ route('login') }}" class="text-decoration-none">Back to login</a></p>
    </div>
</form>
@endsection
