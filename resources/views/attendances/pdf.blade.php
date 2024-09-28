<!DOCTYPE html>
<html>
<head>
    <title>Attendance Records</title>
    <style>
        /* Add some styling here if needed */
    </style>
</head>
<body>
    <h1>Attendance Records</h1>
    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Check-in Time</th>
                <th>Check-out Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->employee->Emp_Code }}</td>
                    <td>{{ $attendance->employee->Employee_Name }}</td>
                    <td>{{ $attendance->date->format('Y-m-d') }}</td>
                    <td>{{ $attendance->checkin_time ? $attendance->checkin_time->format('h:i A') : 'N/A' }}</td>
                    <td>{{ $attendance->checkout_time ? $attendance->checkout_time->format('h:i A') : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
