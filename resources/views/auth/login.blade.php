@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<h1 class="auth-title">Welcome Back</h1>
<p class="auth-subtitle">Sign in to continue to your dashboard</p>

<form method="POST" action="{{ route('login') }}">
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
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3 form-check">
        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label" for="remember">
            {{ __('Remember Me') }}
        </label>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">
            {{ __('Login') }}
        </button>
    </div>

    <div class="text-center mb-3">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-decoration-none">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
    </div>

    @if (Route::has('register'))
    <div class="text-center">
        <p>Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register</a></p>
    </div>
    @endif
</form>
@endsection
