<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class CheckInOutController extends Controller
{
    /**
     * Display a listing of check-ins and check-outs.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['guest', 'room', 'room.roomType']);

        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $query->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('check_in_date', [$startDate, $endDate])
                  ->orWhereBetween('check_out_date', [$startDate, $endDate]);
            });
        } else {
            // Default to current week
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();

            $query->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('check_in_date', [$startDate, $endDate])
                  ->orWhereBetween('check_out_date', [$startDate, $endDate]);
            });
        }

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('check_in_date')->paginate(15);

        // Get counts for today
        $today = Carbon::today();
        $checkInsToday = Booking::whereDate('check_in_date', $today)->count();
        $checkOutsToday = Booking::whereDate('check_out_date', $today)->count();

        return view('check_in_out.index', compact('bookings', 'checkInsToday', 'checkOutsToday', 'startDate', 'endDate'));
    }

    /**
     * Display check-ins and check-outs for today.
     */
    public function today()
    {
        $today = Carbon::today();

        // Get today's check-ins
        $checkIns = Booking::with(['guest', 'room', 'room.roomType'])
            ->whereDate('check_in_date', $today)
            ->orderBy('check_in_date')
            ->get();

        // Get today's check-outs
        $checkOuts = Booking::with(['guest', 'room', 'room.roomType'])
            ->whereDate('check_out_date', $today)
            ->orderBy('check_out_date')
            ->get();

        // Get counts
        $pendingCheckIns = $checkIns->where('status', 'confirmed')->count();
        $completedCheckIns = $checkIns->where('status', 'checked_in')->count();
        $pendingCheckOuts = $checkOuts->where('status', 'checked_in')->count();
        $completedCheckOuts = $checkOuts->where('status', 'checked_out')->count();

        return view('check_in_out.today', compact(
            'checkIns',
            'checkOuts',
            'today',
            'pendingCheckIns',
            'completedCheckIns',
            'pendingCheckOuts',
            'completedCheckOuts'
        ));
    }
}
