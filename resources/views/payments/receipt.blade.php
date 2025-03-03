<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt #{{ $payment->receipt_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f8f9fc;
        }
        .receipt-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .receipt-header {
            background: linear-gradient(45deg, #4e73df, #36b9cc);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .receipt-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .receipt-header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .receipt-body {
            padding: 20px;
        }
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .receipt-info-column {
            flex: 1;
        }
        .receipt-info-item {
            margin-bottom: 10px;
        }
        .receipt-info-item strong {
            display: block;
            font-size: 12px;
            color: #777;
            text-transform: uppercase;
        }
        .receipt-info-item span {
            display: block;
            font-size: 16px;
        }
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .receipt-table th {
            background-color: #f8f9fc;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            color: #777;
            text-transform: uppercase;
        }
        .receipt-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .receipt-table .amount {
            text-align: right;
            font-weight: bold;
        }
        .receipt-total {
            text-align: right;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
        .receipt-total-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 5px;
        }
        .receipt-total-label {
            width: 150px;
            text-align: right;
            padding-right: 20px;
            font-size: 14px;
        }
        .receipt-total-value {
            width: 100px;
            text-align: right;
            font-size: 14px;
        }
        .receipt-total-final {
            font-size: 18px;
            font-weight: bold;
        }
        .receipt-footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #777;
        }
        .receipt-footer p {
            margin: 5px 0;
        }
        .receipt-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .receipt-status.completed {
            background-color: #1cc88a;
            color: white;
        }
        .receipt-status.pending {
            background-color: #f6c23e;
            color: white;
        }
        .receipt-status.failed {
            background-color: #e74a3b;
            color: white;
        }
        .receipt-status.refunded {
            background-color: #36b9cc;
            color: white;
        }
        .receipt-barcode {
            text-align: center;
            margin: 20px 0;
        }
        .receipt-barcode img {
            max-width: 250px;
        }
        .receipt-notes {
            background-color: #f8f9fc;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 14px;
        }
        .receipt-notes h4 {
            margin-top: 0;
            font-size: 16px;
        }
        .print-button {
            display: block;
            text-align: center;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4e73df;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            width: 200px;
        }
        .print-button:hover {
            background-color: #3a56b7;
        }
        @media print {
            body {
                background-color: white;
            }
            .receipt-container {
                box-shadow: none;
                margin: 0;
                max-width: 100%;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>Payment Receipt</h1>
            <p>{{ config('app.name', 'Hotel Management System') }}</p>
        </div>
        
        <div class="receipt-body">
            <div class="receipt-info">
                <div class="receipt-info-column">
                    <div class="receipt-info-item">
                        <strong>Receipt Number</strong>
                        <span>{{ $payment->receipt_number }}</span>
                    </div>
                    <div class="receipt-info-item">
                        <strong>Payment Date</strong>
                        <span>{{ $payment->payment_date->format('F d, Y h:i A') }}</span>
                    </div>
                    <div class="receipt-info-item">
                        <strong>Payment Method</strong>
                        <span>
                            @if($payment->payment_method == 'credit_card')
                                Credit Card {{ $payment->card_last_four ? '(**** ' . $payment->card_last_four . ')' : '' }}
                            @elseif($payment->payment_method == 'cash')
                                Cash
                            @elseif($payment->payment_method == 'bank_transfer')
                                Bank Transfer
                            @elseif($payment->payment_method == 'paypal')
                                PayPal
                            @else
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            @endif
                        </span>
                    </div>
                    <div class="receipt-info-item">
                        <strong>Status</strong>
                        <span>
                            <span class="receipt-status {{ $payment->status }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </span>
                    </div>
                </div>
                
                <div class="receipt-info-column">
                    <div class="receipt-info-item">
                        <strong>Guest</strong>
                        <span>{{ $payment->booking->guest->full_name }}</span>
                    </div>
                    <div class="receipt-info-item">
                        <strong>Booking Number</strong>
                        <span>#{{ $payment->booking->booking_number }}</span>
                    </div>
                    <div class="receipt-info-item">
                        <strong>Room</strong>
                        <span>{{ $payment->booking->room->room_number }} ({{ $payment->booking->room->roomType->name }})</span>
                    </div>
                    <div class="receipt-info-item">
                        <strong>Stay Period</strong>
                        <span>{{ $payment->booking->check_in_date->format('M d, Y') }} - {{ $payment->booking->check_out_date->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            
            <table class="receipt-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Details</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Room Charge</td>
                        <td>
                            {{ $payment->booking->room->roomType->name }} Room 
                            ({{ $payment->booking->check_in_date->format('M d') }} - {{ $payment->booking->check_out_date->format('M d') }})
                            <br>
                            <small>{{ $payment->booking->check_in_date->diffInDays($payment->booking->check_out_date) }} nights</small>
                        </td>
                        <td class="amount">${{ number_format($payment->booking->room_charge, 2) }}</td>
                    </tr>
                    
                    @if($payment->booking->additional_charges > 0)
                    <tr>
                        <td>Additional Charges</td>
                        <td>
                            Extra services and amenities
                            @if($payment->booking->additional_charges_notes)
                                <br>
                                <small>{{ $payment->booking->additional_charges_notes }}</small>
                            @endif
                        </td>
                        <td class="amount">${{ number_format($payment->booking->additional_charges, 2) }}</td>
                    </tr>
                    @endif
                    
                    @if($payment->booking->discount > 0)
                    <tr>
                        <td>Discount</td>
                        <td>
                            Applied discount
                            @if($payment->booking->discount_notes)
                                <br>
                                <small>{{ $payment->booking->discount_notes }}</small>
                            @endif
                        </td>
                        <td class="amount">-${{ number_format($payment->booking->discount, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            
            <div class="receipt-total">
                <div class="receipt-total-row">
                    <div class="receipt-total-label">Booking Total:</div>
                    <div class="receipt-total-value">${{ number_format($payment->booking->total_amount, 2) }}</div>
                </div>
                <div class="receipt-total-row">
                    <div class="receipt-total-label">Previously Paid:</div>
                    <div class="receipt-total-value">${{ number_format($payment->booking->paid_amount - $payment->amount, 2) }}</div>
                </div>
                <div class="receipt-total-row">
                    <div class="receipt-total-label">Remaining Balance:</div>
                    <div class="receipt-total-value">${{ number_format($payment->booking->total_amount - $payment->booking->paid_amount, 2) }}</div>
                </div>
                <div class="receipt-total-row receipt-total-final">
                    <div class="receipt-total-label">This Payment:</div>
                    <div class="receipt-total-value">${{ number_format($payment->amount, 2) }}</div>
                </div>
            </div>
            
            @if($payment->notes)
            <div class="receipt-notes">
                <h4>Notes</h4>
                <p>{{ $payment->notes }}</p>
            </div>
            @endif
            
            <div class="receipt-barcode">
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($payment->receipt_number, 'C39+', 1, 40) }}" alt="Barcode">
                <p>{{ $payment->receipt_number }}</p>
            </div>
        </div>
        
        <div class="receipt-footer">
            <p>Thank you for choosing {{ config('app.name', 'Hotel Management System') }}!</p>
            <p>For any inquiries regarding this payment, please contact our front desk.</p>
            <p>This receipt was generated on {{ now()->format('F d, Y h:i A') }}</p>
        </div>
    </div>
    
    <button class="print-button" onclick="window.print()">Print Receipt</button>
</body>
</html> 