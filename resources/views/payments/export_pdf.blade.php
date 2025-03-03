<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #4e73df;
            margin-bottom: 20px;
        }
        .report-info {
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #4e73df;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fc;
        }
        .status-completed {
            color: #1cc88a;
            font-weight: bold;
        }
        .status-pending {
            color: #f6c23e;
            font-weight: bold;
        }
        .status-failed {
            color: #e74a3b;
            font-weight: bold;
        }
        .status-refunded {
            color: #36b9cc;
            font-weight: bold;
        }
        .summary {
            margin-top: 30px;
            border-top: 2px solid #4e73df;
            padding-top: 20px;
        }
        .summary h2 {
            color: #4e73df;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .summary-table {
            width: 50%;
            margin-left: auto;
        }
        .summary-table td {
            border: none;
            padding: 5px 10px;
        }
        .summary-table .label {
            text-align: right;
            font-weight: bold;
        }
        .summary-table .total {
            font-size: 16px;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Payments Report</h1>
    
    <div class="report-info">
        <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
        <p>Total Payments: {{ $payments->count() }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Receipt #</th>
                <th>Booking #</th>
                <th>Guest</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->receipt_number }}</td>
                    <td>#{{ $payment->booking->booking_number }}</td>
                    <td>{{ $payment->booking->guest->full_name }}</td>
                    <td>${{ number_format($payment->amount, 2) }}</td>
                    <td>
                        @if($payment->payment_method == 'credit_card')
                            Credit Card
                            @if($payment->card_last_four)
                                (**** {{ $payment->card_last_four }})
                            @endif
                        @elseif($payment->payment_method == 'cash')
                            Cash
                        @elseif($payment->payment_method == 'bank_transfer')
                            Bank Transfer
                        @elseif($payment->payment_method == 'paypal')
                            PayPal
                        @else
                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                        @endif
                    </td>
                    <td class="status-{{ $payment->status }}">
                        {{ ucfirst($payment->status) }}
                    </td>
                    <td>{{ $payment->payment_date->format('M d, Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="summary">
        <h2>Payment Summary</h2>
        
        <table class="summary-table">
            <tr>
                <td class="label">Total Amount:</td>
                <td>${{ number_format($payments->sum('amount'), 2) }}</td>
            </tr>
            <tr>
                <td class="label">Completed Payments:</td>
                <td>{{ $payments->where('status', 'completed')->count() }}</td>
            </tr>
            <tr>
                <td class="label">Pending Payments:</td>
                <td>{{ $payments->where('status', 'pending')->count() }}</td>
            </tr>
            <tr>
                <td class="label">Failed Payments:</td>
                <td>{{ $payments->where('status', 'failed')->count() }}</td>
            </tr>
            <tr>
                <td class="label">Refunded Payments:</td>
                <td>{{ $payments->where('status', 'refunded')->count() }}</td>
            </tr>
            <tr>
                <td class="label total">Total Revenue:</td>
                <td class="total">${{ number_format($payments->where('status', 'completed')->sum('amount') - $payments->where('status', 'refunded')->sum('amount'), 2) }}</td>
            </tr>
        </table>
    </div>
    
    <div class="footer">
        <p>{{ config('app.name', 'Hotel Management System') }} - Payments Report</p>
        <p>This is a system-generated report. For any inquiries, please contact the administrator.</p>
    </div>
</body>
</html> 