@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Leave Requests</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Leave Requests</h5>
                            @if (Auth::user()->isHOD() ||Auth::user()->isEmployee() )
                                <a href="{{ route('leave_requests.create') }}" class="btn btn-primary btn-custom">Request Leave</a>
                            @endif
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Table with striped rows -->
                        <table class="table datatable">
                            <thead class="table-header">
                                <tr>
                                    <th>Employee</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Leave Type</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaveRequests as $leaveRequest)
                                    <tr>
                                        <td>{{ $leaveRequest->employee->Employee_Name }}</td>
                                        <td>{{ $leaveRequest->start_date }}</td>
                                        <td>{{ $leaveRequest->end_date }}</td>
                                        <td>{{ $leaveRequest->leave_type }}</td>
                                        <td>{{ $leaveRequest->reason }}</td>
                                        <td>
                                            @if (Auth::user()->isHOD())
                                                <!-- Status Dropdown for HOD -->
                                                <form action="{{ route('leave_requests.updateStatus', $leaveRequest) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                                        <option value="pending" {{ $leaveRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="approved" {{ $leaveRequest->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                        <option value="rejected" {{ $leaveRequest->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                </form>
                                            @else
                                                {{ $leaveRequest->status }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (Auth::user()->isHOD())

                                                <form action="{{ route('leave_requests.destroy', $leaveRequest) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn btn btn-danger">
                                                        <i class="bi bi-trash"></i> <span>Delete</span>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('leave_requests.show', $leaveRequest) }}" class="action-btn btn btn-info">
                                                <i class="bi bi-eye"></i> <span>View</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .table-header th {
                background-color: #007bff; /* Blue color for header */
                color: #fff; /* White text color */
                text-align: center;
            }
            .table tbody tr:nth-child(even) {
                background-color: #f2f2f2; /* Light grey for alternate rows */
            }
            .table tbody tr:hover {
                background-color: #e9ecef; /* Slightly darker grey on hover */
            }
            .table th, .table td {
                text-align: center; /* Center-align text in table cells */
                vertical-align: middle;
            }
            .btn-custom {
                height: 40px; /* Set the desired height */
                min-width: 120px; /* Set the desired minimum width */
                /* Adjust padding to fit the text properly */
            }
            .action-btn {
                display: inline-flex;
                align-items: center;
                padding: 0.375rem 0.75rem;
                font-size: 1rem;
                border-radius: 0.25rem;
            }
            .action-btn i {
                margin-right: 0.5rem;
            }
        </style>
    @endpush

    @push('script')
        @if (session()->has('success'))
            <script>
                swal("Success!", "{{ session('success') }}", "success");
            </script>
        @endif
    @endpush
@endsection
