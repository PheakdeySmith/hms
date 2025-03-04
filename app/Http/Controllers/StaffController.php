<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
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
        try {
            // Check if user has permission to view staff
            if (!request()->user() || !request()->user()->can('view-staff')) {
                abort(403, 'Unauthorized action.');
            }

            // Get all users with staff role - using a more direct approach
            $staff = User::role('staff')->get();

            return view('staff.index', compact('staff'));
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in StaffController@index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Return with error message
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user has permission to create staff
        if (!request()->user() || !request()->user()->can('create-staff')) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::all();
        return view('staff.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Check if user has permission to create staff
            if (!request()->user() || !request()->user()->can('create-staff')) {
                abort(403, 'Unauthorized action.');
            }

            // Validate request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Password::defaults()],
                'role' => 'required|exists:roles,id',
                'phone' => 'nullable|string|max:20',
                'position' => 'required|string|max:100',
                'department' => 'required|string|max:100',
                'hire_date' => 'required|date',
            ]);

            // Debug the request data


            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'position' => $request->position,
                'department' => $request->department,
                'hire_date' => $request->hire_date,
            ]);

            // Assign the staff role
            $role = Role::findById($request->role);
            $user->assignRole($role);

            return redirect()->route('staff.index')->with('success', 'Staff member created successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in StaffController@store: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Return with error message
            return back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Check if user has permission to view staff
            if (!request()->user() || !request()->user()->can('view-staff')) {
                abort(403, 'Unauthorized action.');
            }

            $staff = User::findOrFail($id);

            // Make sure the user is a staff member
            if (!$staff->hasRole('staff') && !$staff->hasRole('admin')) {
                abort(404, 'Staff not found.');
            }

            // Uncomment this line to debug the staff data
            // dd($staff->toArray());

            return view('staff.show', compact('staff'));
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in StaffController@show: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Return with error message
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Check if user has permission to edit staff
        if (!request()->user() || !request()->user()->can('edit-staff')) {
            abort(403, 'Unauthorized action.');
        }

        $staff = User::findOrFail($id);

        // Make sure the user is a staff member
        if (!$staff->hasRole('staff') && !$staff->hasRole('admin')) {
            abort(404, 'Staff not found.');
        }

        $roles = Role::all();
        return view('staff.edit', compact('staff', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Check if user has permission to edit staff
            if (!request()->user() || !request()->user()->can('edit-staff')) {
                abort(403, 'Unauthorized action.');
            }

            $staff = User::findOrFail($id);

            // Make sure the user is a staff member
            if (!$staff->hasRole('staff') && !$staff->hasRole('admin')) {
                abort(404, 'Staff not found.');
            }

            // Validate request
            $validationRules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $staff->id,
                'role' => 'required|exists:roles,id',
                'phone' => 'nullable|string|max:20',
                'position' => 'required|string|max:100',
                'department' => 'required|string|max:100',
                'hire_date' => 'required|date',
            ];

            // If password is provided, validate it
            if ($request->filled('password')) {
                $validationRules['password'] = ['required', 'confirmed', Password::defaults()];
            }

            $validated = $request->validate($validationRules);

            // Uncomment this line to debug the request data
            // dd($request->all(), $staff->toArray());

            // Update staff information
            $staff->name = $request->name;
            $staff->email = $request->email;
            $staff->phone = $request->phone;
            $staff->position = $request->position;
            $staff->department = $request->department;
            $staff->hire_date = $request->hire_date;

            // Update password if provided
            if ($request->filled('password')) {
                $staff->password = Hash::make($request->password);
            }

            $staff->save();

            // Update role if it has changed
            $role = Role::findById($request->role);
            if (!$staff->hasRole($role)) {
                $staff->syncRoles([$role]);
            }

            return redirect()->route('staff.show', $staff->id)->with('success', 'Staff member updated successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in StaffController@update: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Return with error message
            return back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Check if user has permission to delete staff
        if (!request()->user() || !request()->user()->can('delete-staff')) {
            abort(403, 'Unauthorized action.');
        }

        $staff = User::findOrFail($id);

        // Check if user has staff role
        if (!$staff->hasRole('staff')) {
            abort(404, 'Staff member not found.');
        }

        $staff->delete();

        return redirect()->route('staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }
}
