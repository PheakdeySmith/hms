<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user has permission to view roles
        if (!request()->user() || !request()->user()->can('view-role')) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user has permission to create roles
        if (!request()->user() || !request()->user()->can('create-role')) {
            abort(403, 'Unauthorized action.');
        }

        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user has permission to create roles
        if (!request()->user() || !request()->user()->can('create-role')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Check if user has permission to view roles
        if (!request()->user() || !request()->user()->can('view-role')) {
            abort(403, 'Unauthorized action.');
        }

        $role = Role::findOrFail($id);
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $permissions = Permission::all();

        return view('roles.show', compact('role', 'rolePermissions', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Check if user has permission to edit roles
        if (!request()->user() || !request()->user()->can('edit-role')) {
            abort(403, 'Unauthorized action.');
        }

        $role = Role::findOrFail($id);
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        $permissions = Permission::all();

        return view('roles.edit', compact('role', 'rolePermissions', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if user has permission to edit roles
        if (!request()->user() || !request()->user()->can('edit-role')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|unique:roles,name,'.$id,
            'permissions' => 'required|array',
        ]);

        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Check if user has permission to delete roles
        if (!request()->user() || !request()->user()->can('delete-role')) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent deletion of default roles
        $role = Role::findOrFail($id);
        $defaultRoles = ['super_admin', 'admin', 'manager', 'staff', 'user'];

        if (in_array($role->name, $defaultRoles)) {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete default role.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
