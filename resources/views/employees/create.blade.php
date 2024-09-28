@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Create Employee</p>

                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="Emp_Code">Employee Code</label>
                            <input type="text" name="Emp_Code" id="Emp_Code" class="form-control @error('Emp_Code') is-invalid @enderror" value="{{ old('Emp_Code') }}" required>
                            @error('Emp_Code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="Employee_Name">Name</label>
                            <input type="text" name="Employee_Name" id="Employee_Name" class="form-control @error('Employee_Name') is-invalid @enderror" value="{{ old('Employee_Name') }}" required>
                            @error('Employee_Name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="Department">Department</label>
                            <select name="Department" id="Department" class="form-control @error('Department') is-invalid @enderror" required>
                                <option value="" disabled selected>Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department }}" {{ old('Department') == $department ? 'selected' : '' }}>
                                        {{ $department }}
                                    </option>
                                @endforeach
                            </select>
                            @error('Department')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="Designation">Designation</label>
                            <input type="text" name="Designation" id="Designation" class="form-control @error('Designation') is-invalid @enderror" value="{{ old('Designation') }}" required>
                            @error('Designation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
