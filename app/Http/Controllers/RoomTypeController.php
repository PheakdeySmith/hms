<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RoomType::query();

        // Filter by name if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by status if provided
        if ($request->has('is_active') && $request->is_active !== null) {
            $query->where('is_active', $request->is_active);
        }

        $roomTypes = $query->orderBy('name')->paginate(10);

        return view('room_types.index', compact('roomTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('room_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:room_types',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'has_air_conditioning' => 'boolean',
            'has_tv' => 'boolean',
            'has_refrigerator' => 'boolean',
            'has_safe' => 'boolean',
            'has_wifi' => 'boolean',
            'has_minibar' => 'boolean',
            'has_bathtub' => 'boolean',
            'amenities' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        // Convert checkbox values
        $validated['has_air_conditioning'] = $request->has('has_air_conditioning');
        $validated['has_tv'] = $request->has('has_tv');
        $validated['has_refrigerator'] = $request->has('has_refrigerator');
        $validated['has_safe'] = $request->has('has_safe');
        $validated['has_wifi'] = $request->has('has_wifi');
        $validated['has_minibar'] = $request->has('has_minibar');
        $validated['has_bathtub'] = $request->has('has_bathtub');
        $validated['is_active'] = $request->has('is_active');

        // Ensure amenities is an array
        if (!$request->has('amenities')) {
            $validated['amenities'] = [];
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('room-types', 'public');
        }

        $roomType = RoomType::create($validated);

        return redirect()->route('room-types.show', $roomType)
            ->with('success', 'Room type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $roomType = RoomType::with('rooms')->findOrFail($id);

        // Get available rooms count
        $availableRoomsCount = $roomType->rooms()->where('status', 'available')->count();

        return view('room_types.show', compact('roomType', 'availableRoomsCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $roomType = RoomType::findOrFail($id);

        return view('room_types.edit', compact('roomType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $roomType = RoomType::findOrFail($id);

        // Determine if this is a partial update (like just updating the image)
        // Check if only the image field is present and no other fields
        $isImageOnlyUpdate = $request->hasFile('image') &&
                            count($request->all()) <= 3; // _token, _method, and image

        // If it's an image-only update, only validate the image field
        if ($isImageOnlyUpdate) {
            $validated = $request->validate([
                'image' => 'required|image|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($roomType->image) {
                    Storage::disk('public')->delete($roomType->image);
                }

                $validated['image'] = $request->file('image')->store('room-types', 'public');
            }
        } else {
            // Full update with all fields
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:room_types,name,' . $id,
                'description' => 'required|string',
                'base_price' => 'required|numeric|min:0',
                'capacity' => 'required|integer|min:1',
                'has_air_conditioning' => 'boolean',
                'has_tv' => 'boolean',
                'has_refrigerator' => 'boolean',
                'has_safe' => 'boolean',
                'has_wifi' => 'boolean',
                'has_minibar' => 'boolean',
                'has_bathtub' => 'boolean',
                'amenities' => 'nullable|array',
                'image' => 'nullable|image|max:2048',
                'is_active' => 'boolean',
            ]);

            // Convert checkbox values
            $validated['has_air_conditioning'] = $request->has('has_air_conditioning');
            $validated['has_tv'] = $request->has('has_tv');
            $validated['has_refrigerator'] = $request->has('has_refrigerator');
            $validated['has_safe'] = $request->has('has_safe');
            $validated['has_wifi'] = $request->has('has_wifi');
            $validated['has_minibar'] = $request->has('has_minibar');
            $validated['has_bathtub'] = $request->has('has_bathtub');
            $validated['is_active'] = $request->has('is_active');

            // Ensure amenities is an array
            if (!$request->has('amenities')) {
                $validated['amenities'] = [];
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($roomType->image) {
                    Storage::disk('public')->delete($roomType->image);
                }

                $validated['image'] = $request->file('image')->store('room-types', 'public');
            }
        }

        $roomType->update($validated);

        return redirect()->route('room-types.show', $roomType)
            ->with('success', 'Room type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $roomType = RoomType::findOrFail($id);

        // Check if room type has rooms
        if ($roomType->rooms()->count() > 0) {
            return redirect()->route('room-types.show', $roomType)
                ->with('error', 'Cannot delete room type with existing rooms.');
        }

        // Delete image if exists
        if ($roomType->image) {
            Storage::disk('public')->delete($roomType->image);
        }

        $roomType->delete();

        return redirect()->route('room-types.index')
            ->with('success', 'Room type deleted successfully.');
    }
}
