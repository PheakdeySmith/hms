@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<h1 class="auth-title">Reset Password</h1>
<p class="auth-subtitle">Enter your email to receive a password reset link</p>

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label">{{ __('Email Address') }}</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">
            {{ __('Send Password Reset Link') }}
        </button>
    </div>

    <div class="text-center">
        <p>Remember your password? <a href="{{ route('login') }}" class="text-decoration-none">Back to login</a></p>
    </div>
</form>
@endsection
