@extends('layouts.app')

@section('styles')
<style>
    .payment-details-card {
        border-radius: 10px;
        overflow: hidden;
    }
    .payment-header {
        background: linear-gradient(45deg, #4e73df, #36b9cc);
        color: white;
        padding: 20px;
    }
    .payment-method-icon {
        font-size: 2.5rem;
        margin-right: 1rem;
    }
    .payment-status-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 0.9rem;
        padding: 8px 15px;
    }
    .payment-info-item {
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }
    .payment-info-item:last-child {
        border-bottom: none;
    }
    .payment-actions {
        margin-top: 20px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payment Details</h1>
        <div>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Payments
            </a>
            <a href="{{ route('payments.receipt', $payment->id) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-file-invoice"></i> View Receipt
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Payment Details Card -->
            <div class="card shadow mb-4 payment-details-card">
                <div class="payment-header position-relative">
                    <div class="d-flex align-items-center">
                        @if($payment->payment_method == 'credit_card')
                            <i class="fas fa-credit-card payment-method-icon"></i>
                        @elseif($payment->payment_method == 'cash')
                            <i class="fas fa-money-bill-wave payment-method-icon"></i>
                        @elseif($payment->payment_method == 'bank_transfer')
                            <i class="fas fa-university payment-method-icon"></i>
                        @elseif($payment->payment_method == 'paypal')
                            <i class="fab fa-paypal payment-method-icon"></i>
                        @else
                            <i class="fas fa-dollar-sign payment-method-icon"></i>
                        @endif
                        <div>
                            <h4 class="mb-0">Payment #{{ $payment->receipt_number }}</h4>
                            <p class="mb-0">{{ $payment->payment_date->format('F d, Y h:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($payment->status == 'completed')
                        <span class="badge bg-success payment-status-badge">Completed</span>
                    @elseif($payment->status == 'pending')
                        <span class="badge bg-warning payment-status-badge">Pending</span>
                    @elseif($payment->status == 'failed')
                        <span class="badge bg-danger payment-status-badge">Failed</span>
                    @elseif($payment->status == 'refunded')
                        <span class="badge bg-info payment-status-badge">Refunded</span>
                    @else
                        <span class="badge bg-secondary payment-status-badge">{{ ucfirst($payment->status) }}</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Payment Information</h5>
                            <div class="payment-info-item">
                                <strong>Amount:</strong> 
                                <span class="float-end">${{ number_format($payment->amount, 2) }}</span>
                            </div>
                            <div class="payment-info-item">
                                <strong>Payment Method:</strong> 
                                <span class="float-end">
                                    @if($payment->payment_method == 'credit_card')
                                        <i class="fas fa-credit-card text-primary"></i> Credit Card
                                        @if($payment->card_last_four)
                                            (**** {{ $payment->card_last_four }})
                                        @endif
                                    @elseif($payment->payment_method == 'cash')
                                        <i class="fas fa-money-bill-wave text-success"></i> Cash
                                    @elseif($payment->payment_method == 'bank_transfer')
                                        <i class="fas fa-university text-info"></i> Bank Transfer
                                    @elseif($payment->payment_method == 'paypal')
                                        <i class="fab fa-paypal text-primary"></i> PayPal
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    @endif
                                </span>
                            </div>
                            <div class="payment-info-item">
                                <strong>Transaction ID:</strong> 
                                <span class="float-end">{{ $payment->transaction_id ?? 'N/A' }}</span>
                            </div>
                            <div class="payment-info-item">
                                <strong>Date:</strong> 
                                <span class="float-end">{{ $payment->payment_date->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Booking Information</h5>
                            <div class="payment-info-item">
                                <strong>Booking #:</strong> 
                                <span class="float-end">
                                    <a href="{{ route('bookings.show', $payment->booking->id) }}">
                                        #{{ $payment->booking->booking_number }}
                                    </a>
                                </span>
                            </div>
                            <div class="payment-info-item">
                                <strong>Guest:</strong> 
                                <span class="float-end">
                                    <a href="{{ route('guests.show', $payment->booking->guest->id) }}">
                                        {{ $payment->booking->guest->full_name }}
                                    </a>
                                </span>
                            </div>
                            <div class="payment-info-item">
                                <strong>Room:</strong> 
                                <span class="float-end">
                                    <a href="{{ route('rooms.show', $payment->booking->room->id) }}">
                                        {{ $payment->booking->room->room_number }} ({{ $payment->booking->room->roomType->name }})
                                    </a>
                                </span>
                            </div>
                            <div class="payment-info-item">
                                <strong>Stay Period:</strong> 
                                <span class="float-end">
                                    {{ $payment->booking->check_in_date->format('M d') }} - {{ $payment->booking->check_out_date->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($payment->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="font-weight-bold">Notes</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    {{ $payment->notes }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="payment-actions">
                        @if($payment->status == 'pending')
                            <form action="{{ route('payments.update', $payment->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle"></i> Mark as Completed
                                </button>
                            </form>
                            <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Payment
                            </a>
                        @endif
                        
                        @if($payment->status == 'completed')
                            <form action="{{ route('payments.update', $payment->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="refunded">
                                <button type="submit" class="btn btn-info" onclick="return confirm('Are you sure you want to mark this payment as refunded?')">
                                    <i class="fas fa-undo"></i> Mark as Refunded
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this payment? This action cannot be undone.')">
                                <i class="fas fa-trash"></i> Delete Payment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Payment Summary Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Summary</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h1 class="display-4 font-weight-bold text-primary">${{ number_format($payment->amount, 2) }}</h1>
                        <p class="text-muted">Total Amount</p>
                    </div>
                    
                    <div class="text-center mb-4">
                        <div class="mb-2">
                            @if($payment->status == 'completed')
                                <i class="fas fa-check-circle fa-3x text-success"></i>
                            @elseif($payment->status == 'pending')
                                <i class="fas fa-clock fa-3x text-warning"></i>
                            @elseif($payment->status == 'failed')
                                <i class="fas fa-times-circle fa-3x text-danger"></i>
                            @elseif($payment->status == 'refunded')
                                <i class="fas fa-undo fa-3x text-info"></i>
                            @endif
                        </div>
                        <h5>
                            @if($payment->status == 'completed')
                                Payment Completed
                            @elseif($payment->status == 'pending')
                                Payment Pending
                            @elseif($payment->status == 'failed')
                                Payment Failed
                            @elseif($payment->status == 'refunded')
                                Payment Refunded
                            @endif
                        </h5>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Receipt Number:</span>
                        <span class="font-weight-bold">{{ $payment->receipt_number }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Payment Date:</span>
                        <span>{{ $payment->payment_date->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Payment Time:</span>
                        <span>{{ $payment->payment_date->format('h:i A') }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <a href="{{ route('payments.receipt', $payment->id) }}" class="btn btn-primary btn-block" target="_blank">
                            <i class="fas fa-print"></i> Print Receipt
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Related Payments Card (if any) -->
            @if($relatedPayments && $relatedPayments->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Related Payments</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($relatedPayments as $relatedPayment)
                            <a href="{{ route('payments.show', $relatedPayment->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">#{{ $relatedPayment->receipt_number }}</h6>
                                    <small>${{ number_format($relatedPayment->amount, 2) }}</small>
                                </div>
                                <p class="mb-1">{{ $relatedPayment->payment_date->format('M d, Y') }}</p>
                                <small>
                                    @if($relatedPayment->status == 'completed')
                                        <span class="text-success">Completed</span>
                                    @elseif($relatedPayment->status == 'pending')
                                        <span class="text-warning">Pending</span>
                                    @elseif($relatedPayment->status == 'failed')
                                        <span class="text-danger">Failed</span>
                                    @elseif($relatedPayment->status == 'refunded')
                                        <span class="text-info">Refunded</span>
                                    @endif
                                </small>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 