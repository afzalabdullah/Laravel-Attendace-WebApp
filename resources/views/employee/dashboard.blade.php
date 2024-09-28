@extends('layouts.app')

<style>
    .card-body canvas {
        max-width: 100% !important;
        height: auto !important;
    }
    .card {
        transition: transform 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .icon {
        margin-right: 1.5rem; /* Adjusted margin for spacing */
    }
    .text-content {
        flex: 1;
    }
    .text-content p {
        font-size: 0.875rem; /* Smaller text for labels */
    }
    .text-content h3 {
        font-size: 1.75rem; /* Adjusted font size for main text */
    }
    @media (max-width: 768px) {
        .card-body {
            padding: 0.5rem;
        }
    }
</style>

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12 col-md-8">
            <h3 class="font-weight-bold">Welcome, {{ Auth::user()->name }}!</h3>
            <h6 class="font-weight-normal text-muted">Here's your attendance data!</h6>
        </div>
        <div class="col-12 col-md-4 d-flex justify-content-md-end align-items-center">
            <!-- Additional Content (if needed) -->
        </div>
    </div>

 

    <!-- Charts Section -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 rounded shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Check-In Time Trend (Last 30 Days)</h5>
                    <canvas id="checkInChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card border-0 rounded shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Check-Out Time Trend (Last 30 Days)</h5>
                    <canvas id="checkOutChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Helper function to convert minutes to AM/PM format
    function formatTimeInAMPM(minutes) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        const period = hours >= 12 ? 'PM' : 'AM';
        const hourIn12Format = hours % 12 || 12; // Convert 24-hour to 12-hour format
        return `${hourIn12Format}:${mins.toString().padStart(2, '0')} ${period}`;
    }

    // Chart options for all charts
    const chartOptions = {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.dataset.label + ': ' + formatTimeInAMPM(tooltipItem.raw);
                    }
                },
                bodyFont: {
                    size: 10
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    font: {
                        size: 10
                    }
                }
            },
            y: {
                ticks: {
                    font: {
                        size: 10
                    },
                    callback: function(value) {
                        return formatTimeInAMPM(value);
                    }
                }
            }
        }
    };

    // Check-In Chart
    new Chart(document.getElementById('checkInChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($attendanceDates) !!},
            datasets: [{
                label: 'Check-In Time',
                data: {!! json_encode($checkInTimesInMinutes) !!},
                backgroundColor: '#36a2eb',
                borderColor: '#36a2eb',
                borderWidth: 1
            }]
        },
        options: {
            ...chartOptions,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            }
        }
    });

    // Check-Out Chart
    new Chart(document.getElementById('checkOutChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($attendanceDates) !!},
            datasets: [{
                label: 'Check-Out Time',
                data: {!! json_encode($checkOutTimesInMinutes) !!},
                backgroundColor: '#ff6384',
                borderColor: '#ff6384',
                borderWidth: 1
            }]
        },
        options: {
            ...chartOptions,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            }
        }
    });
</script>
@endsection

@endsection
