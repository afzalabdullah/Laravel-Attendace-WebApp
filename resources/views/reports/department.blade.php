@extends('layouts.app')

@section('content')
<style>
    .report-form {
        font-size: 0.9rem; /* Adjust font size to match other styles */
    }

    .report-form label {
        display: block;
        margin-bottom: 0.5rem;
    }

    .report-form select,
    .report-form input[type="date"] {
        width: 100%;
        padding: 0.5rem;
        margin-bottom: 1rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .report-form button {
        background-color: #17a2b8; /* Primary button color */
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.25rem;
        cursor: pointer;
    }

    .report-form button:hover {
        background-color: #138496; /* Darker shade on hover */
    }

    .alert {
        margin-bottom: 1rem;
    }
</style>

<div class="container mt-4">
    <h3>Generate Department Report</h3>

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

    <form action="{{ url('/report/department') }}" method="POST" class="report-form">
        @csrf
        <div class="form-group">
            <label for="department">Department:</label>
            <select id="department" name="department" required>
                <option value="">Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>
        </div>

        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>
        </div>

        <button type="submit">Generate Report</button>
    </form>
</div>
@endsection
