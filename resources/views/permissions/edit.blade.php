@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Permission</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-key me-1"></i>
            Edit Permission: {{ $permission->name }}
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $defaultPermissions = [
                    'view-dashboard', 'view-role', 'create-role', 'edit-role', 'delete-role',
                    'view-permission', 'create-permission', 'edit-permission', 'delete-permission',
                    'assign-permission', 'view-user', 'create-user', 'edit-user', 'delete-user'
                ];
                $isDefaultPermission = in_array($permission->name, $defaultPermissions);
            @endphp

            <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Permission Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $permission->name) }}" required {{ $isDefaultPermission ? 'readonly' : '' }}>
                    <div class="form-text">
                        Use format: <code>module-action</code> (e.g., view-user, create-booking, etc.)
                        @if($isDefaultPermission)
                            <br><strong>Note:</strong> Default permissions cannot be renamed.
                        @endif
                    </div>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Permission</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
