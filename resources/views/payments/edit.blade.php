@extends('layouts.app')

@section('styles')
<style>
    .payment-method-card {
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
        border-radius: 10px;
        overflow: hidden;
    }
    .payment-method-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .payment-method-card.selected {
        border-color: var(--primary-color);
    }
    .payment-method-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    .credit-card-form {
        display: none;
    }
    .credit-card-form.active {
        display: block;
    }
    .bank-transfer-form {
        display: none;
    }
    .bank-transfer-form.active {
        display: block;
    }
    .paypal-form {
        display: none;
    }
    .paypal-form.active {
        display: block;
    }
    .cash-form {
        display: none;
    }
    .cash-form.active {
        display: block;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Payment</h1>
        <div>
            <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Payment
            </a>
            <a href="{{ route('payments.receipt', $payment->id) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-file-invoice"></i> View Receipt
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('payments.update', $payment->id) }}" method="POST" id="paymentForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="booking_id" class="form-label">Booking <span class="text-danger">*</span></label>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="font-weight-bold">Booking #{{ $payment->booking->booking_number }}</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Guest:</strong> {{ $payment->booking->guest->full_name }}</p>
                                            <p><strong>Room:</strong> {{ $payment->booking->room->room_number }} ({{ $payment->booking->room->roomType->name }})</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Check-in:</strong> {{ $payment->booking->check_in_date->format('M d, Y') }}</p>
                                            <p><strong>Check-out:</strong> {{ $payment->booking->check_out_date->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="alert alert-info mb-0">
                                        <strong>Total Amount:</strong> ${{ number_format($payment->booking->total_amount, 2) }} | 
                                        <strong>Paid Amount:</strong> ${{ number_format($payment->booking->paid_amount, 2) }} | 
                                        <strong>Remaining:</strong> ${{ number_format($payment->booking->total_amount - $payment->booking->paid_amount, 2) }}
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="booking_id" value="{{ $payment->booking_id }}">
                        </div>
                        
                        <div class="mb-4">
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $payment->amount) }}" required>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="payment-method-card card text-center p-3 {{ $payment->payment_method == 'credit_card' ? 'selected' : '' }}" data-method="credit_card">
                                        <div class="payment-method-icon">
                                            <i class="fas fa-credit-card text-primary"></i>
                                        </div>
                                        <h6>Credit Card</h6>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="payment-method-card card text-center p-3 {{ $payment->payment_method == 'cash' ? 'selected' : '' }}" data-method="cash">
                                        <div class="payment-method-icon">
                                            <i class="fas fa-money-bill-wave text-success"></i>
                                        </div>
                                        <h6>Cash</h6>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="payment-method-card card text-center p-3 {{ $payment->payment_method == 'bank_transfer' ? 'selected' : '' }}" data-method="bank_transfer">
                                        <div class="payment-method-icon">
                                            <i class="fas fa-university text-info"></i>
                                        </div>
                                        <h6>Bank Transfer</h6>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="payment-method-card card text-center p-3 {{ $payment->payment_method == 'paypal' ? 'selected' : '' }}" data-method="paypal">
                                        <div class="payment-method-icon">
                                            <i class="fab fa-paypal text-primary"></i>
                                        </div>
                                        <h6>PayPal</h6>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="payment_method" id="payment_method" value="{{ old('payment_method', $payment->payment_method) }}" required>
                            @error('payment_method')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Credit Card Form -->
                        <div class="credit-card-form payment-method-form mb-4 {{ $payment->payment_method == 'credit_card' ? 'active' : '' }}">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="font-weight-bold mb-3">Credit Card Details</h6>
                                    <div class="mb-3">
                                        <label for="card_holder" class="form-label">Card Holder Name</label>
                                        <input type="text" class="form-control" id="card_holder" name="card_holder" value="{{ old('card_holder', $payment->card_holder ?? '') }}">
                                    </div>
                                    <div class="mb-0">
                                        <label for="card_last_four" class="form-label">Last 4 Digits (for receipt)</label>
                                        <input type="text" class="form-control" id="card_last_four" name="card_last_four" maxlength="4" value="{{ old('card_last_four', $payment->card_last_four ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bank Transfer Form -->
                        <div class="bank-transfer-form payment-method-form mb-4 {{ $payment->payment_method == 'bank_transfer' ? 'active' : '' }}">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="font-weight-bold mb-3">Bank Transfer Details</h6>
                                    <div class="mb-3">
                                        <label for="bank_name" class="form-label">Bank Name</label>
                                        <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name', $payment->bank_name ?? '') }}">
                                    </div>
                                    <div class="mb-0">
                                        <label for="transaction_id" class="form-label">Transaction ID / Reference</label>
                                        <input type="text" class="form-control" id="transaction_id" name="transaction_id" value="{{ old('transaction_id', $payment->transaction_id ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- PayPal Form -->
                        <div class="paypal-form payment-method-form mb-4 {{ $payment->payment_method == 'paypal' ? 'active' : '' }}">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="font-weight-bold mb-3">PayPal Details</h6>
                                    <div class="mb-3">
                                        <label for="paypal_email" class="form-label">PayPal Email</label>
                                        <input type="email" class="form-control" id="paypal_email" name="paypal_email" value="{{ old('paypal_email', $payment->paypal_email ?? '') }}">
                                    </div>
                                    <div class="mb-0">
                                        <label for="paypal_transaction_id" class="form-label">PayPal Transaction ID</label>
                                        <input type="text" class="form-control" id="paypal_transaction_id" name="transaction_id" value="{{ old('transaction_id', $payment->transaction_id ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cash Form -->
                        <div class="cash-form payment-method-form mb-4 {{ $payment->payment_method == 'cash' ? 'active' : '' }}">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="font-weight-bold mb-3">Cash Payment Details</h6>
                                    <div class="mb-0">
                                        <label for="cash_notes" class="form-label">Cash Notes</label>
                                        <textarea class="form-control" id="cash_notes" name="cash_notes" rows="2">{{ old('cash_notes', $payment->cash_notes ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" name="payment_date" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d\TH:i')) }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="completed" {{ old('status', $payment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="pending" {{ old('status', $payment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ old('status', $payment->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ old('status', $payment->status) == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $payment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Update Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Summary</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h1 class="display-4 font-weight-bold text-primary">$<span id="summaryAmount">{{ number_format($payment->amount, 2) }}</span></h1>
                        <p class="text-muted">Total Amount</p>
                    </div>
                    
                    <hr>
                    
                    <div id="paymentSummary">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Receipt Number:</span>
                            <span class="font-weight-bold">{{ $payment->receipt_number }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Payment Method:</span>
                            <span id="summaryMethod" class="font-weight-bold">
                                @if($payment->payment_method == 'credit_card')
                                    Credit Card
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
                        <div class="d-flex justify-content-between mb-2">
                            <span>Booking:</span>
                            <span class="font-weight-bold">#{{ $payment->booking->booking_number }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Guest:</span>
                            <span class="font-weight-bold">{{ $payment->booking->guest->full_name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Date:</span>
                            <span id="summaryDate" class="font-weight-bold">{{ $payment->payment_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Editing this payment may affect the booking's balance and status.
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment History</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Payment Created</h6>
                                <small class="text-muted">{{ $payment->created_at->format('M d, Y h:i A') }}</small>
                                <p class="mb-0">Initial amount: ${{ number_format($payment->amount, 2) }}</p>
                            </div>
                        </div>
                        
                        @if($payment->updated_at->gt($payment->created_at))
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Last Updated</h6>
                                <small class="text-muted">{{ $payment->updated_at->format('M d, Y h:i A') }}</small>
                                <p class="mb-0">Status: {{ ucfirst($payment->status) }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Payment method selection
        $('.payment-method-card').click(function() {
            $('.payment-method-card').removeClass('selected');
            $(this).addClass('selected');
            
            var method = $(this).data('method');
            $('#payment_method').val(method);
            
            // Hide all method forms
            $('.payment-method-form').removeClass('active');
            
            // Show selected method form
            $('.' + method + '-form').addClass('active');
            
            // Update summary
            var methodText = $(this).find('h6').text();
            $('#summaryMethod').text(methodText);
        });
        
        // Amount change
        $('#amount').on('input', function() {
            updateSummary();
        });
        
        // Date change
        $('#payment_date').on('change', function() {
            var date = new Date($(this).val());
            var formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            $('#summaryDate').text(formattedDate);
        });
        
        // Update summary
        function updateSummary() {
            var amount = parseFloat($('#amount').val()) || 0;
            $('#summaryAmount').text(amount.toFixed(2));
        }
        
        // Form validation
        $('#paymentForm').on('submit', function(e) {
            if (!$('#payment_method').val()) {
                e.preventDefault();
                alert('Please select a payment method');
                return false;
            }
            
            return true;
        });
    });
</script>
@endsection 