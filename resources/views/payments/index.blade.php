@extends('layouts.app')

@section('styles')
<style>
    .payment-card {
        transition: transform 0.3s;
    }
    .payment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .payment-status {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    .payment-method-icon {
        font-size: 1.5rem;
        margin-right: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payments</h1>
        <div>
            <a href="{{ route('payments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> New Payment
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Filter Payments</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="filterDropdown">
                    <a class="dropdown-item" href="{{ route('payments.index') }}">Clear Filters</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('payments.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="status">Payment Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="payment_method">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="">All Methods</option>
                            <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="paypal" {{ request('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="date_from">Date From</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="date_to">Date To</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payment Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Payments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPayments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalRevenue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Completed Payments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedPayments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Payments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingPayments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">All Payments</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Export Options:</div>
                    <a class="dropdown-item" href="{{ route('payments.export', ['format' => 'csv', 'status' => request('status'), 'payment_method' => request('payment_method'), 'date_from' => request('date_from'), 'date_to' => request('date_to')]) }}">
                        <i class="fas fa-file-csv fa-sm fa-fw mr-2 text-gray-400"></i> Export CSV
                    </a>
                    <a class="dropdown-item" href="{{ route('payments.export', ['format' => 'pdf', 'status' => request('status'), 'payment_method' => request('payment_method'), 'date_from' => request('date_from'), 'date_to' => request('date_to')]) }}">
                        <i class="fas fa-file-pdf fa-sm fa-fw mr-2 text-gray-400"></i> Export PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="paymentsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Receipt #</th>
                                <th>Guest</th>
                                <th>Booking #</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->receipt_number }}</td>
                                    <td>
                                        <a href="{{ route('guests.show', $payment->booking->guest->id) }}">
                                            {{ $payment->booking->guest->full_name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('bookings.show', $payment->booking->id) }}">
                                            #{{ $payment->booking->booking_number }}
                                        </a>
                                    </td>
                                    <td>${{ number_format($payment->amount, 2) }}</td>
                                    <td>
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
                                    </td>
                                    <td>
                                        @if($payment->status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($payment->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($payment->status == 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @elseif($payment->status == 'refunded')
                                            <span class="badge bg-info">Refunded</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->payment_date->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('payments.receipt', $payment->id) }}" class="btn btn-sm btn-info" target="_blank">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                            @if($payment->status == 'pending')
                                                <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <img src="{{ asset('images/no-data.svg') }}" alt="No Payments" class="img-fluid mb-3" style="max-height: 200px;">
                    <h4 class="text-muted">No payments found</h4>
                    <p>There are no payments matching your criteria.</p>
                    <a href="{{ route('payments.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle"></i> Create New Payment
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize datatable
        $('#paymentsTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": false
        });
    });
</script>
@endsection 