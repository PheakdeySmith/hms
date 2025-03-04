@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Permission Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></li>
        <li class="breadcrumb-item active">View</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-key me-1"></i>
            Permission: {{ $permission->name }}
        </div>
        <div class="card-body">
            <div class="mb-3">
                <h5>Permission Information</h5>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 200px;">Name</th>
                        <td>{{ $permission->name }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $permission->created_at->format('F d, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $permission->updated_at->format('F d, Y h:i A') }}</td>
                    </tr>
                </table>
            </div>

            <div class="mb-3">
                <h5>Roles with this Permission</h5>
                @if($permission->roles->count() > 0)
                    <ul class="list-group">
                        @foreach($permission->roles as $role)
                            <li class="list-group-item">
                                <a href="{{ route('roles.show', $role->id) }}">{{ $role->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No roles have this permission.</p>
                @endif
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Back to Permissions</a>
                @can('edit-permission')
                <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-primary">Edit Permission</a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
