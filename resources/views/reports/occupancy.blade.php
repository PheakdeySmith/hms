@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Occupancy Report</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Reports
            </a>
            <a href="{{ route('reports.export', 'occupancy') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Export Report
            </a>
        </div>
    </div>

    <!-- Date Range Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter by Date Range</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.occupancy') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Occupancy Chart Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Occupancy Rate by Room Type</h6>
        </div>
        <div class="card-body">
            <div class="chart-area">
                <canvas id="occupancyChart" style="min-height: 300px;"></canvas>
            </div>
            <hr>
            <div class="text-center small mt-3">
                <span class="mr-2">
                    <i class="fas fa-circle text-primary"></i> Overall Occupancy
                </span>
                @foreach($roomTypes as $index => $roomType)
                <span class="mr-2">
                    <i class="fas fa-circle" style="color: {{ \App\Helpers\ChartHelper::getChartColor($index + 1) }};"></i> {{ $roomType->name }}
                </span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Occupancy Summary Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Occupancy Summary</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Room Type</th>
                            <th>Total Rooms</th>
                            <th>Average Occupancy</th>
                            <th>Peak Occupancy</th>
                            <th>Lowest Occupancy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roomTypes as $roomType)
                        <tr>
                            <td>{{ $roomType->name }}</td>
                            <td>{{ $roomType->rooms_count }}</td>
                            <td>
                                @php
                                    $avgOccupancy = count($occupancyData[$roomType->id]['data']) > 0
                                        ? array_sum($occupancyData[$roomType->id]['data']) / count($occupancyData[$roomType->id]['data'])
                                        : 0;
                                @endphp
                                {{ number_format($avgOccupancy, 1) }}%
                            </td>
                            <td>
                                @php
                                    $peakOccupancy = count($occupancyData[$roomType->id]['data']) > 0
                                        ? max($occupancyData[$roomType->id]['data'])
                                        : 0;
                                @endphp
                                {{ number_format($peakOccupancy, 1) }}%
                            </td>
                            <td>
                                @php
                                    $lowestOccupancy = count($occupancyData[$roomType->id]['data']) > 0
                                        ? min($occupancyData[$roomType->id]['data'])
                                        : 0;
                                @endphp
                                {{ number_format($lowestOccupancy, 1) }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Function to get chart colors
    function getChartColor(index) {
        const colors = [
            '#4e73df', // Primary
            '#1cc88a', // Success
            '#36b9cc', // Info
            '#f6c23e', // Warning
            '#e74a3b', // Danger
            '#6f42c1', // Purple
            '#fd7e14', // Orange
            '#20c9a6', // Teal
            '#5a5c69', // Gray
            '#858796'  // Secondary
        ];
        return colors[index % colors.length];
    }

    // Chart data
    const dates = @json($dates);
    const occupancyData = @json($occupancyData);

    // Prepare datasets
    const datasets = [];
    let index = 0;

    for (const [roomTypeId, data] of Object.entries(occupancyData)) {
        datasets.push({
            label: data.name,
            data: data.data,
            backgroundColor: 'transparent',
            borderColor: getChartColor(index + 1),
            pointBackgroundColor: getChartColor(index + 1),
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: getChartColor(index + 1),
            borderWidth: 2
        });
        index++;
    }

    // Calculate overall occupancy
    const overallOccupancy = dates.map((date, i) => {
        let totalOccupied = 0;
        let totalRooms = 0;

        for (const [roomTypeId, data] of Object.entries(occupancyData)) {
            const roomType = @json($roomTypes).find(rt => rt.id == roomTypeId);
            const occupancyRate = data.data[i];
            const roomCount = roomType.rooms_count;

            totalOccupied += (occupancyRate / 100) * roomCount;
            totalRooms += roomCount;
        }

        return totalRooms > 0 ? (totalOccupied / totalRooms) * 100 : 0;
    });

    // Add overall occupancy dataset
    datasets.unshift({
        label: 'Overall Occupancy',
        data: overallOccupancy,
        backgroundColor: 'transparent',
        borderColor: getChartColor(0),
        pointBackgroundColor: getChartColor(0),
        pointBorderColor: '#fff',
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: getChartColor(0),
        borderWidth: 3
    });

    // Create chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('occupancyChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        title: {
                            display: true,
                            text: 'Occupancy Rate (%)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw.toFixed(1) + '%';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
