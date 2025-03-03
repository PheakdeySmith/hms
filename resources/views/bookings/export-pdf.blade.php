<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            color: #4e73df;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4e73df;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            color: white;
        }
        .badge-confirmed {
            background-color: #f6c23e;
            color: #000;
        }
        .badge-checked_in {
            background-color: #1cc88a;
        }
        .badge-checked_out {
            background-color: #858796;
        }
        .badge-cancelled {
            background-color: #e74a3b;
        }
    </style>
</head>
<body>
    <h1>Bookings Report</h1>
    
    <p>Generated on: {{ date('F d, Y H:i:s') }}</p>
    <p>Total Bookings: {{ $bookings->count() }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Reference</th>
                <th>Guest</th>
                <th>Room</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Status</th>
                <th>Guests</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr>
                <td>{{ $booking->booking_reference }}</td>
                <td>{{ $booking->guest->first_name }} {{ $booking->guest->last_name }}</td>
                <td>Room {{ $booking->room->room_number }} ({{ $booking->room->roomType->name }})</td>
                <td>{{ $booking->check_in_date->format('M d, Y') }}</td>
                <td>{{ $booking->check_out_date->format('M d, Y') }}</td>
                <td>
                    @if($booking->status == 'confirmed')
                        <span class="badge badge-confirmed">Confirmed</span>
                    @elseif($booking->status == 'checked_in')
                        <span class="badge badge-checked_in">Checked In</span>
                    @elseif($booking->status == 'checked_out')
                        <span class="badge badge-checked_out">Checked Out</span>
                    @elseif($booking->status == 'cancelled')
                        <span class="badge badge-cancelled">Cancelled</span>
                    @endif
                </td>
                <td>{{ $booking->number_of_guests }}</td>
                <td>${{ number_format($booking->total_price, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">No bookings found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Hotel Management System &copy; {{ date('Y') }} - All Rights Reserved</p>
    </div>
</body>
</html> 