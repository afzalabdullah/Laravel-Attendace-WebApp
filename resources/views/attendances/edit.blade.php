@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Attendance</h1>

    <form action="{{ route('attendances.update', $attendance->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $attendance->name) }}" required>
        </div>

        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" id="date" name="date" class="form-control" value="{{ old('date', $attendance->date->format('Y-m-d')) }}" required>
        </div>

        <div class="form-group">
            <label for="checkin_time">Check-in Time</label>
            <input type="time" id="checkin_time" name="checkin_time" class="form-control" value="{{ old('checkin_time', $attendance->checkin_time ? $attendance->checkin_time->format('H:i') : '') }}">
        </div>

        <div class="form-group">
            <label for="checkout_time">Check-out Time</label>
            <input type="time" id="checkout_time" name="checkout_time" class="form-control" value="{{ old('checkout_time', $attendance->checkout_time ? $attendance->checkout_time->format('H:i') : '') }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
