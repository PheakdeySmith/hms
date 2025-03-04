@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Role Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
        <li class="breadcrumb-item active">View</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-tag me-1"></i>
            Role: {{ $role->name }}
        </div>
        <div class="card-body">
            <div class="mb-3">
                <h5>Role Information</h5>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 200px;">Name</th>
                        <td>{{ $role->name }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $role->created_at->format('F d, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $role->updated_at->format('F d, Y h:i A') }}</td>
                    </tr>
                </table>
            </div>

            <div class="mb-3">
                <h5>Permissions</h5>
                <div class="row">
                    @foreach($permissions->groupBy(function($item) {
                        return explode('-', $item->name)[0];
                    }) as $group => $items)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <strong>{{ ucfirst($group) }}</strong>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach($items as $permission)
                                            <li class="list-group-item {{ in_array($permission->name, $rolePermissions) ? 'list-group-item-success' : '' }}">
                                                @if(in_array($permission->name, $rolePermissions))
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                @else
                                                    <i class="fas fa-times-circle text-muted me-2"></i>
                                                @endif
                                                {{ ucfirst(str_replace('-', ' ', str_replace($group . '-', '', $permission->name))) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Back to Roles</a>
                @can('edit-role')
                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary">Edit Role</a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
