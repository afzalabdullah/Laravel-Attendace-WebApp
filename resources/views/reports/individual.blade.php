@extends('layouts.app')

@section('content')
<style>
    .report-form {
        font-size: 0.9rem;
    }

    .report-form label {
        display: block;
        margin-bottom: 0.5rem;
    }

    .report-form input[type="text"],
    .report-form input[type="date"] {
        width: 100%;
        padding: 0.5rem;
        margin-bottom: 1rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .report-form button {
        background-color: #17a2b8;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.25rem;
        cursor: pointer;
    }

    .report-form button:hover {
        background-color: #138496;
    }

    .alert {
        margin-bottom: 1rem;
    }
</style>

<div class="container mt-4">
    @if(Auth::user()->isEmployee())
    <h3>Generate Report</h3>
    @else
    <h3>Generate Individual Report</h3>
    @endif
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

    <form action="{{ url('/report/individual') }}" method="POST" class="report-form">
        @csrf
        @if(Auth::user()->isEmployee())
            <!-- Employee-specific fields or restrictions -->
            <input type="hidden" id="emp_code" name="emp_code" value="{{ Auth::user()->emp_code }}">
        @else
            <div class="form-group">
                <label for="emp_code">Employee Code:</label>
                <input type="text" id="emp_code" name="emp_code" required>
            </div>
        @endif

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
