<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Guest::query();
        
        // Filter by name if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $guests = $query->orderBy('last_name')->paginate(10);
        
        return view('guests.index', compact('guests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('guests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'identification_type' => 'nullable|string|max:50',
            'identification_number' => 'nullable|string|max:50',
            'special_requests' => 'nullable|string',
        ]);
        
        $guest = \App\Models\Guest::create($validated);
        
        return redirect()->route('guests.show', $guest)
            ->with('success', 'Guest created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $guest = \App\Models\Guest::with('bookings.room.roomType')->findOrFail($id);
        
        return view('guests.show', compact('guest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $guest = \App\Models\Guest::findOrFail($id);
        
        return view('guests.edit', compact('guest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $guest = \App\Models\Guest::findOrFail($id);
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'identification_type' => 'nullable|string|max:50',
            'identification_number' => 'nullable|string|max:50',
            'special_requests' => 'nullable|string',
        ]);
        
        $guest->update($validated);
        
        return redirect()->route('guests.show', $guest)
            ->with('success', 'Guest updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $guest = \App\Models\Guest::findOrFail($id);
        
        // Check if guest has bookings
        if ($guest->bookings()->count() > 0) {
            return redirect()->route('guests.show', $guest)
                ->with('error', 'Cannot delete guest with existing bookings.');
        }
        
        $guest->delete();
        
        return redirect()->route('guests.index')
            ->with('success', 'Guest deleted successfully.');
    }
}
