@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Attendance</h1>

    <form action="{{ route('attendances.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" id="date" name="date" class="form-control" value="{{ old('date') }}" required>
        </div>

        <div class="form-group">
            <label for="checkin_time">Check-in Time</label>
            <input type="time" id="checkin_time" name="checkin_time" class="form-control" value="{{ old('checkin_time') }}">
        </div>

        <div class="form-group">
            <label for="checkout_time">Check-out Time</label>
            <input type="time" id="checkout_time" name="checkout_time" class="form-control" value="{{ old('checkout_time') }}">
        </div>

        <button type="submit" class="btn btn-primary">Add Attendance</button>
    </form>
</div>
@endsection
