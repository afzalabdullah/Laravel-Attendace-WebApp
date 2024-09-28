
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
            <h6 class="font-weight-normal text-muted">All systems are running smoothly!</h6>
        </div>
        <div class="col-12 col-md-4 d-flex justify-content-md-end align-items-center">
            <!-- Additional Content (if needed) -->
        </div>
    </div>

   <!-- Admin Dashboard -->
        <div class="row">
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card border-0 rounded shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon mr-4">
                            <i class="fas fa-users fa-3x text-primary"></i>
                        </div>
                        <div class="text-content">
                            <p class="mb-2 text-muted">Total Employees</p>
                            <h3 class="mb-0 font-weight-bold">{{ $totalEmployees }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card border-0 rounded shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon mr-4">
                            <i class="fas fa-calendar-day fa-3x text-success"></i>
                        </div>
                        <div class="text-content">
                            <p class="mb-2 text-muted">Today's Present Employees</p>
                            <h3 class="mb-0 font-weight-bold">{{ $employeesPresentToday }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card border-0 rounded shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon mr-4">
                            <i class="fas fa-briefcase fa-3x text-info"></i>
                        </div>
                        <div class="text-content">
                            <p class="mb-2 text-muted">Departments</p>
                            <h3 class="mb-0 font-weight-bold">{{ $totalDepartments }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <div class="col-md-12 col-lg-6 mb-4">
                <div class="card border-0 rounded shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Department-wise Employee Distribution</h5>
                        <canvas id="departmentChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6 mb-4">
                <div class="card border-0 rounded shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Gender Distribution</h5>
                        <canvas id="genderDistributionChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6 mb-4">
                <div class="card border-0 rounded shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Employee Attendance Trend</h5>
                        <canvas id="onTimeTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6 mb-4">
                <div class="card border-0 rounded shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Employee Distribution by Region</h5>
                        <canvas id="regionChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6 mb-4">
                <div class="card border-0 rounded shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Employee Attendance Trend (Last 30 Days)</h5>
                        <canvas id="attendanceTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6 mb-4">
                <div class="card border-0 rounded shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Employee Distribution by Designation</h5>
                        <canvas id="designationChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6 mb-4">
                <div class="card border-0 rounded shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Employee Distribution by Grade</h5>
                        <canvas id="gradeChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6 mb-4">
                <div class="card border-0 rounded shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Average Length of Service by Department</h5>
                        <canvas id="serviceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

</div>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
                    return tooltipItem.label + ': ' + tooltipItem.raw;
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
                }
            }
        }
    }
};

// Apply the options to all charts
new Chart(document.getElementById('departmentChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($departmentLabels) !!},
        datasets: [{
            label: 'Department Distribution',
            data: {!! json_encode($departmentData) !!},
            backgroundColor: '#36a2eb',
            borderColor: '#36a2eb',
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y', // Makes the bars horizontal
        responsive: true,
        plugins: {
            legend: {
                display: false // Hide the legend if not needed
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw;
                    }
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true
            },
            y: {
                beginAtZero: true
            }
        }
    }
});


new Chart(document.getElementById('attendanceTrendChart').getContext('2d'), {
    type: 'bar',


    data: {
        labels: {!! json_encode($attendanceDates) !!},
        datasets: [{
            label: 'Employees Present',
            data: {!! json_encode($attendanceCounts) !!},
            borderColor: '#ff6384',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderWidth: 1
        }]
    },
    options: chartOptions
});
new Chart(document.getElementById('onTimeTrendChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($onTimeDates) !!},
            datasets: [{
                label: 'Employees On Time (9 AM - 10 AM)',
                data: {!! json_encode($onTimeCounts) !!},
                borderColor: '#36a2eb',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 1
            }]
        },
        options: chartOptions
    });

new Chart(document.getElementById('genderDistributionChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($genderLabels) !!},
        datasets: [{
            label: 'Gender Distribution',
            data: {!! json_encode($genderCounts) !!},
            backgroundColor: ['#ff6384', '#36a2eb', '#ffce56'],
            borderColor: ['#ff6384', '#36a2eb', '#ffce56'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw;
                    }
                }
            }
        },
        scales: {
            x: {
                stacked: true,
                beginAtZero: true
            },
            y: {
                stacked: true,
                beginAtZero: true
            }
        }
    }
});


new Chart(document.getElementById('regionChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($regionLabels) !!},
        datasets: [{
            label: 'Employees',
            data: {!! json_encode($regionCounts) !!},
            backgroundColor: '#ff6384',
            borderColor: '#ff6384',
            borderWidth: 1
        }]
    },
    options: chartOptions
});

new Chart(document.getElementById('designationChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($designationLabels) !!},
        datasets: [{
            label: 'Employees',
            data: {!! json_encode($designationCounts) !!},
            backgroundColor: '#36a2eb',
            borderColor: '#36a2eb',
            borderWidth: 1
        }]
    },
    options: chartOptions
});

new Chart(document.getElementById('gradeChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($gradeLabels) !!},
        datasets: [{
            label: 'Employees',
            data: {!! json_encode($gradeCounts) !!},
            backgroundColor: '#ffce56',
            borderColor: '#ffce56',
            borderWidth: 1
        }]
    },
    options: chartOptions
});

new Chart(document.getElementById('serviceChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($departmentServiceLabels) !!},
        datasets: [{
            label: 'Average Service (Years)',
            data: {!! json_encode($averageServiceData) !!},
            backgroundColor: '#4bc0c0',
            borderColor: '#4bc0c0',
            borderWidth: 1
        }]
    },
    options: chartOptions
});
</script>
@endsection
@endsection
