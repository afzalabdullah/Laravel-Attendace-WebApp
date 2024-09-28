@extends('layouts.app')

@section('content')
<style>
    .report-table {
        font-size: 0.9rem;
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .report-table th, .report-table td {
        padding: 0.5rem;
        border: 1px solid #dee2e6;
    }

    .report-table th {
        background-color: #f8f9fa;
    }

    .btn-download {
        background-color: #17a2b8;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.25rem;
        cursor: pointer;
        margin-top: 1rem;
        display: block;
    }

    .btn-download:hover {
        background-color: #138496;
    }

    .status-absent {
        color: red;
        font-weight: bold;
    }

    .status-leave {
        color: green;
        font-weight: bold;
    }

    .status-normal {
        font-weight: normal;
    }
</style>

<div class="container mt-4">
    <h3>Individual Report</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <table class="report-table">
        <thead>
            <tr>
                <th>Emp Code</th>
                <th>Name</th>
                <th>Department</th>
                <th>Date</th>
                <th>Check-in Time</th>
                <th>Check-out Time</th>
                <th>Duty Hours</th>
                <th>Leave Type</th> <!-- Add a column for Leave Type -->
            </tr>
        </thead>
        <tbody>
            @forelse ($reportData as $data)
                <tr>
                    <td>{{ $empCode }}</td>
                    <td>{{ $data['name'] }}</td>
                    <td>{{ $data['department'] }}</td>
                    <td>{{ $data['date'] }}</td>
                    <td>{{ $data['checkin_time'] ?? '' }}</td>
                    <td>{{ $data['checkout_time'] ?? '' }}</td>
                    <td class="{{ $data['status'] === 'on Leave Today' ? 'status-leave' : ($data['status'] === 'Absent' ? 'status-absent' : 'status-normal') }}">
                        {{ $data['duty_hours'] }}
                        @if ($data['status'])
                            <span class="{{ $data['status'] === 'on Leave Today' ? 'status-leave' : 'status-absent' }}">{{ $data['status'] }}</span>
                        @endif
                    </td>
                    <td>{{ $data['leave_type'] ?? '' }}</td> <!-- Display Leave Type -->
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <form action="{{ url('/report/download-individual-excel') }}" method="POST" style="margin-top: 1rem;">
        @csrf
        <input type="hidden" name="emp_code" value="{{ $empCode }}">
        <input type="hidden" name="start_date" value="{{ $startDate }}">
        <input type="hidden" name="end_date" value="{{ $endDate }}">
        <button type="submit" class="btn-download">Download Excel</button>
    </form>
</div>
@endsection
