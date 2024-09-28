@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Employee Details</h4>

                    <div class="mb-3">
                        <label for="Emp_Code" class="form-label">Employee Code</label>
                        <p id="Emp_Code" class="form-control-plaintext">{{ $employee->Emp_Code }}</p>
                    </div>

                    <div class="mb-3">
                        <label for="Employee_Name" class="form-label">Name</label>
                        <p id="Employee_Name" class="form-control-plaintext">{{ $employee->Employee_Name }}</p>
                    </div>

                    <div class="mb-3">
                        <label for="Department" class="form-label">Department</label>
                        <p id="Department" class="form-control-plaintext">{{ $employee->Department }}</p>
                    </div>

                    <div class="mb-3">
                        <label for="Designation" class="form-label">Designation</label>
                        <p id="Designation" class="form-control-plaintext">{{ $employee->Designation }}</p>
                    </div>

                    <a href="{{ route('employees.index') }}" class="btn btn-primary" style="background-color: #AF1E23; border-color: #AF1E23;">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
