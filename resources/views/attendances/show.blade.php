@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Back to List Button -->
    <div class="d-flex justify-content-start mb-4">
        <button onclick="history.back()" class="btn" style="background-color: #AF1E23; color: white;">
            <i class="bi bi-arrow-left"></i> Back to List
        </button>
    </div>

    <h4 class="mb-4">Attendance Details</h4>

    <div class="row">
        <!-- Employee Information Card -->
        <div class="col-lg-6 mb-4">
            <div class="card border-danger shadow-lg">
                <div class="card-header" style="background-color: #AF1E23; color: white;">
                    <h5 class="mb-0">Employee Information</h5>
                </div>
                <div class="card-body mt-2">
                    <dl class="row">
                        <dt class="col-sm-4 text-muted">ID:</dt>
                        <dd class="col-sm-8">{{ $attendance->employee->Emp_Code }}</dd>

                        <dt class="col-sm-4 text-muted">Name:</dt>
                        <dd class="col-sm-8">{{ $attendance->employee->Employee_Name }}</dd>

                        <dt class="col-sm-4 text-muted">Designation:</dt>
                        <dd class="col-sm-8">{{ $attendance->employee->Designation }}</dd>

                        <dt class="col-sm-4 text-muted">Department:</dt>
                        <dd class="col-sm-8">{{ $attendance->employee->Department }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Attendance Details Card -->
        <div class="col-lg-6 mb-4">
            <div class="card border-danger shadow-lg">
                <div class="card-header" style="background-color: #AF1E23; color: white;">
                    <h5 class="mb-0">Attendance Details</h5>
                </div>
                <div class="card-body mt-2">
                    <dl class="row">
                        <dt class="col-sm-4 text-muted">Date:</dt>
                        <dd class="col-sm-8">{{ $attendance->date->format('Y-m-d') }}</dd>
                    </dl>

                    <div class="row">
                        <!-- Check-in Image -->
                        @if($attendance->checkin_time)
                            <div class="col-md-6">
                                <div class="text-center">
                                    <div class="mb-3">
                                        <dt class="text-muted">Clock-In</dt>
                                        @php
                                            $checkinImagePath = 'attendance-image/' . $attendance->date->format('dmY') . '/checkin/' . $attendance->employee->Emp_Code . '_0_' . $attendance->date->format('dmY') . '.png';
                                        @endphp
                                        <img src="{{ url($checkinImagePath) }}" alt="Check-in Image" class="img-fluid shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                                        <div class="mt-2">{{ $attendance->checkin_time->format('h:i A') }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Check-out Image -->
                        @if($attendance->checkout_time)
                            <div class="col-md-6">
                                <div class="text-center">
                                    @php
                                        $checkoutImagePath = 'attendance-image/' . $attendance->date->format('dmY') . '/checkout/' . $attendance->employee->Emp_Code . '_0_' . $attendance->date->format('dmY') . '.png';
                                    @endphp
                                    <div class="mb-3">
                                        <dt class="text-muted">Clock-Out</dt>
                                        <img src="{{ url($checkoutImagePath) }}" alt="Check-out Image" class="img-fluid shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                                        <div class="mt-2">{{ $attendance->checkout_time->format('h:i A') }}</div>
                                    </div>
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
