@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Permission Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Permissions</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-key me-1"></i>
            Permissions
            @can('create-permission')
            <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> Create New Permission
            </a>
            @endcan
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Group</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions->sortBy('name') as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>
                            @php
                                $group = explode('-', $permission->name)[0];
                            @endphp
                            <span class="badge bg-info">{{ ucfirst($group) }}</span>
                        </td>
                        <td>{{ $permission->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                @can('edit-permission')
                                <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan

                                @can('delete-permission')
                                @php
                                    $defaultPermissions = [
                                        'view-dashboard', 'view-role', 'create-role', 'edit-role', 'delete-role',
                                        'view-permission', 'create-permission', 'edit-permission', 'delete-permission',
                                        'assign-permission', 'view-user', 'create-user', 'edit-user', 'delete-user'
                                    ];
                                @endphp

                                @if(!in_array($permission->name, $defaultPermissions))
                                <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this permission?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
