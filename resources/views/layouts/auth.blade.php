<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hotel Management System') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            overflow: hidden;
        }
        .auth-container {
            height: 100vh;
            display: flex;
            overflow: hidden;
        }
        .auth-image {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100%;
            width: 50%;
            position: relative;
        }
        .auth-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
        }
        .auth-form {
            width: 50%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }
        .auth-logo {
            margin-bottom: 30px;
            text-align: center;
        }
        .auth-logo img {
            height: 60px;
        }
        .auth-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #333;
            text-align: center;
        }
        .auth-subtitle {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            margin-bottom: 15px;
        }
        .btn-primary {
            border-radius: 8px;
            padding: 12px 15px;
            background-color: #4e73df;
            border-color: #4e73df;
            font-weight: 500;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #3a5fc8;
            border-color: #3a5fc8;
        }
        .auth-footer {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
        }
        .auth-footer a {
            color: #4e73df;
            text-decoration: none;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
        .auth-image-content {
            position: absolute;
            bottom: 40px;
            left: 40px;
            color: white;
            z-index: 1;
        }
        .auth-image-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .auth-image-subtitle {
            font-size: 18px;
            max-width: 400px;
        }
        @media (max-width: 992px) {
            .auth-image {
                display: none;
            }
            .auth-form {
                width: 100%;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="auth-container">
        <div class="auth-image" style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80')">
            <div class="auth-image-content">
                <h1 class="auth-image-title">{{ config('app.name', 'Hotel Management System') }}</h1>
                <p class="auth-image-subtitle">Streamline your hotel operations with our comprehensive management system</p>
            </div>
        </div>
        <div class="auth-form">
            <div class="auth-logo">
                <h2>{{ config('app.name', 'Hotel Management System') }}</h2>
            </div>
            @yield('content')
            <div class="auth-footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Hotel Management System') }}. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('scripts')
</body>
</html>
