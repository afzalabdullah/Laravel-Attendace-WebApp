@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="pagetitle mb-4">
            <h1>Leave Request Details</h1>
        </div><!-- End Page Title -->

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Leave Request Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <p><strong>Employee:</strong></p>
                        <p>{{ $leaveRequest->employee->Employee_Name }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Employee ID:</strong></p>
                        <p>{{ $leaveRequest->employee->Emp_Code }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Start Date:</strong></p>
                        <p>{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('F j, Y') }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>End Date:</strong></p>
                        <p>{{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('F j, Y') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <p><strong>Reason:</strong></p>
                        <p>{{ $leaveRequest->reason }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <p><strong>Status:</strong></p>
                        <span class="badge {{ $leaveRequest->status == 'approved' ? 'bg-success' : ($leaveRequest->status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                            {{ ucfirst($leaveRequest->status) }}
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('leave_requests.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
