<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Room::with('roomType');
        
        // Filter by room number if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('room_number', 'like', "%{$search}%");
        }
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by room type if provided
        if ($request->has('room_type_id') && $request->room_type_id) {
            $query->where('room_type_id', $request->room_type_id);
        }
        
        // Filter by floor if provided
        if ($request->has('floor') && $request->floor) {
            $query->where('floor', $request->floor);
        }
        
        $rooms = $query->orderBy('room_number')->paginate(10);
        $roomTypes = RoomType::all();
        
        // Get unique floors for filter dropdown
        $floors = Room::select('floor')->distinct()->orderBy('floor')->pluck('floor');
        
        return view('rooms.index', compact('rooms', 'roomTypes', 'floors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roomTypes = RoomType::where('is_active', true)->get();
        return view('rooms.create', compact('roomTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:10|unique:rooms',
            'room_type_id' => 'required|exists:room_types,id',
            'floor' => 'required|string|max:10',
            'status' => 'required|in:available,occupied,maintenance,cleaning',
            'notes' => 'nullable|string',
            'is_smoking' => 'boolean',
            'is_accessible' => 'boolean',
        ]);
        
        // Convert checkbox values
        $validated['is_smoking'] = $request->has('is_smoking');
        $validated['is_accessible'] = $request->has('is_accessible');
        
        $room = Room::create($validated);
        
        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room = Room::with(['roomType', 'bookings.guest'])->findOrFail($id);
        
        // Get upcoming bookings for this room
        $upcomingBookings = $room->bookings()
            ->where('check_in_date', '>=', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('check_in_date')
            ->take(5)
            ->get();
        
        // Get recent bookings for this room
        $recentBookings = $room->bookings()
            ->where('check_out_date', '<', now())
            ->orderBy('check_out_date', 'desc')
            ->take(5)
            ->get();
        
        return view('rooms.show', compact('room', 'upcomingBookings', 'recentBookings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = Room::findOrFail($id);
        $roomTypes = RoomType::where('is_active', true)->get();
        
        return view('rooms.edit', compact('room', 'roomTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $room = Room::findOrFail($id);
        
        $validated = $request->validate([
            'room_number' => 'required|string|max:10|unique:rooms,room_number,' . $id,
            'room_type_id' => 'required|exists:room_types,id',
            'floor' => 'required|string|max:10',
            'status' => 'required|in:available,occupied,maintenance,cleaning',
            'notes' => 'nullable|string',
            'is_smoking' => 'boolean',
            'is_accessible' => 'boolean',
        ]);
        
        // Convert checkbox values
        $validated['is_smoking'] = $request->has('is_smoking');
        $validated['is_accessible'] = $request->has('is_accessible');
        
        $room->update($validated);
        
        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);
        
        // Check if room has bookings
        if ($room->bookings()->count() > 0) {
            return redirect()->route('rooms.show', $room)
                ->with('error', 'Cannot delete room with existing bookings.');
        }
        
        $room->delete();
        
        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully.');
    }
}
