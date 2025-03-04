@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Hotel Settings</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Settings</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-cogs me-1"></i>
            System Settings
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('settings.update') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="hotel_name">Hotel Name</label>
                            <input type="text" class="form-control @error('hotel_name') is-invalid @enderror"
                                id="hotel_name" name="hotel_name" value="{{ old('hotel_name', $settings['hotel_name'] ?? 'Hotel Management System') }}">
                            @error('hotel_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="hotel_address">Hotel Address</label>
                            <textarea class="form-control @error('hotel_address') is-invalid @enderror"
                                id="hotel_address" name="hotel_address" rows="3">{{ old('hotel_address', $settings['hotel_address'] ?? '123 Hotel Street, City, Country') }}</textarea>
                            @error('hotel_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="hotel_phone">Phone Number</label>
                            <input type="text" class="form-control @error('hotel_phone') is-invalid @enderror"
                                id="hotel_phone" name="hotel_phone" value="{{ old('hotel_phone', $settings['hotel_phone'] ?? '+1 234 567 8900') }}">
                            @error('hotel_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="hotel_email">Email Address</label>
                            <input type="email" class="form-control @error('hotel_email') is-invalid @enderror"
                                id="hotel_email" name="hotel_email" value="{{ old('hotel_email', $settings['hotel_email'] ?? 'info@hotel.com') }}">
                            @error('hotel_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="check_in_time">Default Check-in Time</label>
                    <input type="time" class="form-control @error('check_in_time') is-invalid @enderror"
                        id="check_in_time" name="check_in_time" value="{{ old('check_in_time', $settings['check_in_time'] ?? '14:00') }}">
                    @error('check_in_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="check_out_time">Default Check-out Time</label>
                    <input type="time" class="form-control @error('check_out_time') is-invalid @enderror"
                        id="check_out_time" name="check_out_time" value="{{ old('check_out_time', $settings['check_out_time'] ?? '11:00') }}">
                    @error('check_out_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
        </div>
    </div>
</div>
@endsection
