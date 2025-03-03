<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Payment;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with key metrics.
     */
    public function index()
    {
        // Get today's date
        $today = Carbon::today();
        
        // Get counts for key metrics
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $maintenanceRooms = Room::where('status', 'maintenance')->count();
        $reservedRooms = Room::where('status', 'reserved')->count();
        $totalGuests = Guest::count();
        
        // Get today's check-ins and check-outs
        $todayCheckIns = Booking::whereDate('check_in_date', $today)
            ->where('status', 'confirmed')
            ->count();
            
        $todayCheckOuts = Booking::whereDate('check_out_date', $today)
            ->where('status', 'checked_in')
            ->count();
            
        // Get revenue metrics
        $todayRevenue = Payment::whereDate('payment_date', $today)
            ->where('status', 'completed')
            ->sum('amount');
            
        $monthlyRevenue = Payment::whereMonth('payment_date', $today->month)
            ->whereYear('payment_date', $today->year)
            ->where('status', 'completed')
            ->sum('amount');
            
        // Get monthly revenues for the chart
        $monthlyRevenues = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyRevenues[$month] = Payment::whereMonth('payment_date', $month)
                ->whereYear('payment_date', $today->year)
                ->where('status', 'completed')
                ->sum('amount');
        }
            
        // Get recent bookings
        $recentBookings = Booking::with(['guest', 'room'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get upcoming check-ins
        $upcomingCheckIns = Booking::with(['guest', 'room'])
            ->where('check_in_date', '>=', $today)
            ->where('status', 'confirmed')
            ->orderBy('check_in_date')
            ->take(5)
            ->get();
            
        return view('dashboard', compact(
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms',
            'reservedRooms',
            'totalGuests',
            'todayCheckIns',
            'todayCheckOuts',
            'todayRevenue',
            'monthlyRevenue',
            'monthlyRevenues',
            'recentBookings',
            'upcomingCheckIns'
        ));
    }
}
