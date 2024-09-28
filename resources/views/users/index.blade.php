@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>User Records</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Users</h5>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('users.create') }}" class="btn btn-primary btn-custom">Add New User</a>
                            @endif
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Table with striped rows -->
                        <table class="table datatable">
                            <thead class="table-header">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    @if(Auth::user()->isAdmin() || (Auth::user()->isHod() && Auth::user()->department === $user->department))
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->department }}</td>
                                            <td>
                                                <a href="{{ route('users.show', $user->id) }}" class="action-btn btn btn-success mr-2">
                                                    <i class="bi bi-eye"></i> <span>View</span>
                                                </a>
                                                <a href="{{ route('users.edit', $user->id) }}" class="action-btn btn btn-warning mr-2 text-white">
                                                    <i class="bi bi-pencil-square"></i> <span>Edit</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('script')
        @if(session()->has('success'))
            <script>
                swal("Success!", "{{ session('success') }}", "success");
            </script>
        @endif

        <script>
            function deleteUser(id) {
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this user!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location.href = '/users/delete/' + id;
                    }
                });
            }
        </script>
    @endpush

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
        </style>
    @endpush
@endsection
