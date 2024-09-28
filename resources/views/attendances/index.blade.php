@extends('layouts.app')

@section('content')
    <style>
        .btn-custom {
            background-color: #AF1E23;
            color: #fff;
            border: none;
            border-radius: 0.25rem;
        }

        .btn-custom-outline {
            border-color: #AF1E23;
            color: #AF1E23;
            border-radius: 0.25rem;
        }

        .btn-custom-outline:hover {
            background-color: #AF1E23;
            color: #fff;
        }

        .bg-custom-header {
            background-color: #AF1E23;
            color: #fff;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .alert-success {
            border: 1px solid #d4edda;
            background-color: #d4edda;
            color: #155724;
        }
    </style>

    <div class="container container-custom mt-4">
        <div class="row mb-4">
            <!-- Search and Export Controls -->
            <div class="col-md-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('attendances.index') }}" class="row g-3 align-items-center">
    <!-- Date Field -->
                <div class="col-md-4">
                    <input type="date" id="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>

    <!-- Employee Name Field -->
    <div class="col-md-4">
        <input type="text" id="name" name="name" class="form-control" placeholder="Enter Name" value="{{ request('name') }}">
    </div>

    <!-- Search Button -->
    <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-custom-outline me-2">Search</button>
    </div>
</form>


                    
                </div>
            </div>
        </div>

        <!-- Attendance Records Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Attendance Records</h5>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <style>
    .datatable {
        font-size: 0.9rem; /* Adjust the font size here */
    }

    .datatable th, .datatable td {
        padding: 0.5rem; /* Adjust padding to match smaller font size */
    }

    .btn-custom {
        background-color: #17a2b8; /* Change to your desired color */
        color: white;
    }

    .btn-custom:hover {
        background-color: #138496; /* Darker shade on hover */
    }

    .btn-primary {
        background-color: #007bff; /* Standard primary button color */
    }

    .btn-primary:hover {
        background-color: #0056b3; /* Darker shade on hover */
    }

    .btn-danger {
        background-color: #dc3545; /* Standard danger button color */
    }

    .btn-danger:hover {
        background-color: #bd2130; /* Darker shade on hover */
    }
</style>

<table class="table datatable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Date</th>
            <th>Clock-In</th>
            <th>Clock-Out</th>
            <th>Duty Hours</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($attendances as $attendance)
            <tr>
                <td>{{ $attendance->employee->Emp_Code ?? 'N/A' }}</td>
                <td>{{ $attendance->employee->Employee_Name ?? 'N/A' }}</td>
                <td>{{ $attendance->date->format('Y-m-d') ?? 'N/A' }}</td>
                <td>{{ $attendance->checkin_time ? $attendance->checkin_time->format('h:i A') : 'N/A' }}</td>
                <td>{{ $attendance->checkout_time ? $attendance->checkout_time->format('h:i A') : 'N/A' }}</td>
                <td>
                    @if($attendance->checkin_time && $attendance->checkout_time)
                        @php
                            $checkin = \Carbon\Carbon::parse($attendance->checkin_time);
                            $checkout = \Carbon\Carbon::parse($attendance->checkout_time);
                            $dutyHours = $checkout->diffInHours($checkin) . 'h ' . $checkout->diffInMinutes($checkin) % 60 . 'm';
                        @endphp
                        {{ $dutyHours }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    <a href="{{ route('attendances.show', $attendance->id) }}" class="btn btn-custom btn-sm">View</a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-primary btn-sm ms-2">Edit</a>
                        <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm ms-2">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ Auth::user()->isAdmin() ? 7 : 6 }}" class="text-center">Attendance not found</td>
            </tr>
        @endforelse
    </tbody>
</table>


                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
             

                document.querySelectorAll('.delete-form').forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection
