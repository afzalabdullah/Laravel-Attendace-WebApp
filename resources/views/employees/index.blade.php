@extends('layouts.app')

@section('content')
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

    <div class="container container-custom mt-4">
        <div class="row mb-4">
            @if(Auth::user()->isAdmin())
                <div class="col-md-12">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <!-- Add New Employee Button -->
                        <a href="{{ route('employees.create') }}" class="btn btn-custom-outline me-2">Add New Employee</a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Employees Records Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Employees Records</h5>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <table class="table datatable">
                                <thead>
                                    <tr >
                                        <th>Emp Code</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Designation</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employees as $employee)
                                        <tr>
                                            <td>{{ $employee->Emp_Code }}</td>
                                            <td>{{ $employee->Employee_Name }}</td>
                                            <td>{{ $employee->Department }}</td>
                                            <td>{{ $employee->Designation }}</td>
                                            <td>
    <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-custom btn-sm" title="View">
        <i class="bi bi-eye"></i> 
    </a>
    @if(Auth::user()->isAdmin())
        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary btn-sm ms-2" title="Edit">
            <i class="bi bi-pencil-square"></i> 
        </a>
        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline delete-form" title="Delete">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm ms-2">
                <i class="bi bi-trash"></i> 
            </button>
        </form>
    @endif
</td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ Auth::user()->isAdmin() ? 5 : 4 }}" class="text-center">No employees found</td>
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
                $('#employees-table').DataTable({
                    "pageLength": 10,
                    "lengthChange": false
                });

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
