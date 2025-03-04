<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if user has permission to view permissions
        if (!request()->user() || !request()->user()->can('view-permission')) {
            abort(403, 'Unauthorized action.');
        }

        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Check if user has permission to create permissions
        if (!request()->user() || !request()->user()->can('create-permission')) {
            abort(403, 'Unauthorized action.');
        }

        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check if user has permission to create permissions
        if (!$request->user() || !$request->user()->can('create-permission')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        // Check if user has permission to view permissions
        if (!request()->user() || !request()->user()->can('view-permission')) {
            abort(403, 'Unauthorized action.');
        }

        $permission = Permission::findOrFail($id);
        $roles = $permission->roles;

        return view('permissions.show', compact('permission', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        // Check if user has permission to edit permissions
        if (!request()->user() || !request()->user()->can('edit-permission')) {
            abort(403, 'Unauthorized action.');
        }

        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        // Check if user has permission to edit permissions
        if (!$request->user() || !$request->user()->can('edit-permission')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id
        ]);

        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->save();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        // Check if user has permission to delete permissions
        if (!request()->user() || !request()->user()->can('delete-permission')) {
            abort(403, 'Unauthorized action.');
        }

        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
