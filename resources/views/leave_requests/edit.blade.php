<!-- resources/views/leave_requests/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Edit Leave Request</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Form for editing leave request -->
                        <form action="{{ route('leave_requests.update', $leaveRequest) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $leaveRequest->start_date }}" required>
                            </div>
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $leaveRequest->end_date }}" required>
                            </div>
                            <div class="form-group">
                                <label for="reason">Reason</label>
                                <textarea name="reason" id="reason" class="form-control" rows="4" required>{{ $leaveRequest->reason }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="pending" {{ $leaveRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $leaveRequest->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $leaveRequest->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary btn-custom">Update</button>
                                <a href="{{ route('leave_requests.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .form-group {
                margin-bottom: 1rem;
            }
            .form-control {
                height: auto;
                padding: 0.5rem 0.75rem;
                border-radius: 0.25rem;
                border: 1px solid #ced4da;
            }
            .btn-custom {
                height: 40px;
                min-width: 120px;
                /* Adjust padding to fit the text properly */
            }
        </style>
    @endpush
@endsection
