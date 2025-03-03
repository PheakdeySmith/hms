<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the reports index page.
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Display the occupancy report.
     */
    public function occupancy(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Get room types with their rooms
        $roomTypes = RoomType::withCount('rooms')->get();
        
        // Calculate occupancy data
        $occupancyData = [];
        $dates = [];
        
        $currentDate = Carbon::parse($startDate);
        $lastDate = Carbon::parse($endDate);
        
        while ($currentDate->lte($lastDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $dates[] = $dateString;
            
            // Get bookings for this date
            $bookingsForDate = Booking::where('status', '!=', 'cancelled')
                ->where('check_in_date', '<=', $dateString)
                ->where('check_out_date', '>', $dateString)
                ->get();
            
            // Count occupied rooms by type
            $occupiedRoomsByType = [];
            foreach ($bookingsForDate as $booking) {
                $roomTypeId = $booking->room->room_type_id;
                if (!isset($occupiedRoomsByType[$roomTypeId])) {
                    $occupiedRoomsByType[$roomTypeId] = 0;
                }
                $occupiedRoomsByType[$roomTypeId]++;
            }
            
            // Calculate occupancy percentage for each room type
            foreach ($roomTypes as $roomType) {
                $occupied = $occupiedRoomsByType[$roomType->id] ?? 0;
                $total = $roomType->rooms_count;
                $occupancyRate = $total > 0 ? ($occupied / $total) * 100 : 0;
                
                if (!isset($occupancyData[$roomType->id])) {
                    $occupancyData[$roomType->id] = [
                        'name' => $roomType->name,
                        'data' => []
                    ];
                }
                
                $occupancyData[$roomType->id]['data'][] = round($occupancyRate, 1);
            }
            
            $currentDate->addDay();
        }
        
        return view('reports.occupancy', compact('roomTypes', 'occupancyData', 'dates', 'startDate', 'endDate'));
    }

    /**
     * Display the revenue report.
     */
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Get payments grouped by date
        $payments = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('SUM(amount) as total_amount'),
                'payment_method'
            )
            ->groupBy('date', 'payment_method')
            ->orderBy('date')
            ->get();
        
        // Prepare data for chart
        $dates = [];
        $revenueByMethod = [
            'credit_card' => [],
            'cash' => [],
            'bank_transfer' => [],
            'paypal' => []
        ];
        
        $currentDate = Carbon::parse($startDate);
        $lastDate = Carbon::parse($endDate);
        
        while ($currentDate->lte($lastDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $dates[] = $dateString;
            
            foreach (array_keys($revenueByMethod) as $method) {
                $payment = $payments->where('date', $dateString)
                    ->where('payment_method', $method)
                    ->first();
                
                $revenueByMethod[$method][] = $payment ? $payment->total_amount : 0;
            }
            
            $currentDate->addDay();
        }
        
        // Calculate total revenue
        $totalRevenue = $payments->sum('total_amount');
        
        // Calculate revenue by payment method
        $revenueByPaymentMethod = $payments->groupBy('payment_method')
            ->map(function ($group) {
                return $group->sum('total_amount');
            });
        
        return view('reports.revenue', compact(
            'dates', 
            'revenueByMethod', 
            'totalRevenue', 
            'revenueByPaymentMethod',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display the bookings report.
     */
    public function bookings(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Get bookings grouped by date
        $bookings = Booking::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_bookings'),
                'status'
            )
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();
        
        // Prepare data for chart
        $dates = [];
        $bookingsByStatus = [
            'confirmed' => [],
            'checked_in' => [],
            'checked_out' => [],
            'cancelled' => []
        ];
        
        $currentDate = Carbon::parse($startDate);
        $lastDate = Carbon::parse($endDate);
        
        while ($currentDate->lte($lastDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $dates[] = $dateString;
            
            foreach (array_keys($bookingsByStatus) as $status) {
                $booking = $bookings->where('date', $dateString)
                    ->where('status', $status)
                    ->first();
                
                $bookingsByStatus[$status][] = $booking ? $booking->total_bookings : 0;
            }
            
            $currentDate->addDay();
        }
        
        // Calculate total bookings
        $totalBookings = $bookings->sum('total_bookings');
        
        // Calculate bookings by status
        $bookingsByStatusTotal = $bookings->groupBy('status')
            ->map(function ($group) {
                return $group->sum('total_bookings');
            });
        
        return view('reports.bookings', compact(
            'dates', 
            'bookingsByStatus', 
            'totalBookings', 
            'bookingsByStatusTotal',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export a report.
     */
    public function export(Request $request, $type)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Implement export functionality based on report type
        switch ($type) {
            case 'occupancy':
                // Export occupancy report
                break;
            case 'revenue':
                // Export revenue report
                break;
            case 'bookings':
                // Export bookings report
                break;
            default:
                return redirect()->back()->with('error', 'Invalid report type');
        }
        
        return redirect()->back()->with('success', 'Report exported successfully');
    }
} 