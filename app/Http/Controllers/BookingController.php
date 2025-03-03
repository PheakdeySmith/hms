<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['guest', 'room', 'room.roomType']);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from) {
            $query->where('check_in_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('check_out_date', '<=', $request->date_to);
        }
        
        // Filter by guest name if provided
        if ($request->has('guest_name') && $request->guest_name) {
            $guestName = $request->guest_name;
            $query->whereHas('guest', function($q) use ($guestName) {
                $q->where('first_name', 'like', "%{$guestName}%")
                  ->orWhere('last_name', 'like', "%{$guestName}%");
            });
        }
        
        $bookings = $query->orderBy('check_in_date', 'desc')->paginate(10);
        
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guests = Guest::orderBy('last_name')->get();
        $roomTypes = RoomType::with('rooms')->get();
        $availableRooms = Room::where('status', 'available')->get();
        
        return view('bookings.create', compact('guests', 'roomTypes', 'availableRooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'special_requests' => 'nullable|string',
            'booking_source' => 'required|string',
        ]);
        
        // Generate a unique booking reference
        $validated['booking_reference'] = 'BK-' . strtoupper(Str::random(8));
        $validated['status'] = 'confirmed';
        
        // Create the booking
        $booking = Booking::create($validated);
        
        // Update room status to reserved
        $room = Room::find($request->room_id);
        $room->status = 'reserved';
        $room->save();
        
        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = Booking::with(['guest', 'room', 'room.roomType', 'payments'])
            ->findOrFail($id);
            
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $booking = Booking::with(['guest', 'room'])->findOrFail($id);
        $guests = Guest::orderBy('last_name')->get();
        $roomTypes = RoomType::with('rooms')->get();
        $availableRooms = Room::where('status', 'available')
            ->orWhere('id', $booking->room_id)
            ->get();
            
        return view('bookings.edit', compact('booking', 'guests', 'roomTypes', 'availableRooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);
        
        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'special_requests' => 'nullable|string',
            'booking_source' => 'required|string',
        ]);
        
        // If room is changed, update room statuses
        if ($booking->room_id != $request->room_id) {
            // Set old room to available if it was reserved for this booking
            $oldRoom = Room::find($booking->room_id);
            if ($oldRoom->status == 'reserved') {
                $oldRoom->status = 'available';
                $oldRoom->save();
            }
            
            // Set new room to reserved
            $newRoom = Room::find($request->room_id);
            if ($newRoom->status == 'available') {
                $newRoom->status = 'reserved';
                $newRoom->save();
            }
        }
        
        $booking->update($validated);
        
        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        
        // If the booking is confirmed or reserved, set the room back to available
        if (in_array($booking->status, ['confirmed', 'reserved'])) {
            $room = Room::find($booking->room_id);
            $room->status = 'available';
            $room->save();
        }
        
        $booking->delete();
        
        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }
    
    /**
     * Show the check-in form for the booking.
     */
    public function checkIn(string $id)
    {
        $booking = Booking::with(['guest', 'room', 'room.roomType', 'payments'])
            ->findOrFail($id);
            
        // Ensure booking is in confirmed status
        if ($booking->status !== 'confirmed') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'This booking cannot be checked in.');
        }
        
        return view('bookings.check-in', compact('booking'));
    }
    
    /**
     * Process the check-in for the booking.
     */
    public function processCheckIn(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Ensure booking is in confirmed status
        if ($booking->status !== 'confirmed') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'This booking cannot be checked in.');
        }
        
        // Update booking status and actual check-in time
        $booking->status = 'checked_in';
        $booking->actual_check_in = Carbon::now();
        $booking->save();
        
        // Update room status to occupied
        $room = Room::find($booking->room_id);
        $room->status = 'occupied';
        $room->save();
        
        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Guest checked in successfully.');
    }
    
    /**
     * Show the check-out form for the booking.
     */
    public function checkOut(string $id)
    {
        $booking = Booking::with(['guest', 'room', 'room.roomType', 'payments'])
            ->findOrFail($id);
            
        // Ensure booking is in checked_in status
        if ($booking->status !== 'checked_in') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'This booking cannot be checked out.');
        }
        
        return view('bookings.check-out', compact('booking'));
    }
    
    /**
     * Process the check-out for the booking.
     */
    public function processCheckOut(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Ensure booking is in checked_in status
        if ($booking->status !== 'checked_in') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'This booking cannot be checked out.');
        }
        
        // Update booking status and actual check-out time
        $booking->status = 'checked_out';
        $booking->actual_check_out = Carbon::now();
        $booking->save();
        
        // Update room status to available
        $room = Room::find($booking->room_id);
        $room->status = 'available';
        $room->save();
        
        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Guest checked out successfully.');
    }
    
    /**
     * Cancel the booking.
     */
    public function cancel(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Ensure booking is in a status that can be cancelled
        if (!in_array($booking->status, ['confirmed', 'reserved'])) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'This booking cannot be cancelled.');
        }
        
        // Update booking status
        $booking->status = 'cancelled';
        $booking->save();
        
        // Update room status to available
        $room = Room::find($booking->room_id);
        $room->status = 'available';
        $room->save();
        
        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking cancelled successfully.');
    }
    
    /**
     * Export bookings data in CSV or PDF format.
     */
    public function export(Request $request)
    {
        $format = $request->format ?? 'csv';
        
        $query = Booking::with(['guest', 'room', 'room.roomType']);
        
        // Apply filters if they exist in the session
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->where('check_in_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('check_out_date', '<=', $request->date_to);
        }
        
        if ($request->has('guest_name') && $request->guest_name) {
            $guestName = $request->guest_name;
            $query->whereHas('guest', function($q) use ($guestName) {
                $q->where('first_name', 'like', "%{$guestName}%")
                  ->orWhere('last_name', 'like', "%{$guestName}%");
            });
        }
        
        $bookings = $query->orderBy('check_in_date', 'desc')->get();
        
        if ($format === 'csv') {
            $filename = 'bookings_export_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];
            
            $callback = function() use ($bookings) {
                $file = fopen('php://output', 'w');
                
                // Add CSV headers
                fputcsv($file, [
                    'Reference', 'Guest Name', 'Room', 'Check-in Date', 'Check-out Date',
                    'Status', 'Number of Guests', 'Total Price', 'Booking Source'
                ]);
                
                // Add booking data
                foreach ($bookings as $booking) {
                    fputcsv($file, [
                        $booking->booking_reference,
                        $booking->guest->first_name . ' ' . $booking->guest->last_name,
                        'Room ' . $booking->room->room_number . ' (' . $booking->room->roomType->name . ')',
                        $booking->check_in_date->format('Y-m-d'),
                        $booking->check_out_date->format('Y-m-d'),
                        ucfirst($booking->status),
                        $booking->number_of_guests,
                        number_format($booking->total_price, 2),
                        ucfirst(str_replace('_', ' ', $booking->booking_source))
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        } elseif ($format === 'pdf') {
            // For PDF export, you would typically use a package like dompdf, barryvdh/laravel-dompdf, etc.
            // This is a simplified example that returns a view that can be printed as PDF
            return view('bookings.export-pdf', compact('bookings'));
        }
        
        return redirect()->route('bookings.index')
            ->with('error', 'Invalid export format specified.');
    }
}
