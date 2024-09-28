<?php


namespace App\Http\Controllers;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\PDF;
use App\Exports\AttendancesExport;
use Maatwebsite\Excel\Facades\Excel;




class AttendanceController extends Controller
{

    public function index(Request $request)
    {
        // Retrieve the currently authenticated user
        $user = Auth::user();

        // Retrieve date and name filters from the request
        $date = $request->input('date', now()->toDateString()); // Default to today's date if not provided
        $name = $request->input('name', ''); // Default to empty string if not provided

        // Get accessible attendances based on the user's role
        $attendances = $user->getAccessibleAttendances();

        // Apply the date filter
        if (!empty($date)) {
            $attendances = $attendances->whereDate('date', $date);
        }

        // Apply the name filter
        if (!empty($name)) {
            $attendances = $attendances->whereHas('employee', function($query) use ($name) {
                $query->where('Employee_Name', 'like', '%' . $name . '%')
                      ->orWhere('Emp_Code', 'like', '%' . $name . '%');
            });
        }

        // Retrieve the filtered results
        $attendances = $attendances->with('employee')->get();

        return view('attendances.index', compact('attendances'));
    }




    public function create()
    {
        return view('attendances.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'checkin_time' => 'nullable|date_format:H:i',
            'checkout_time' => 'nullable|date_format:H:i',
        ]);

        Attendance::create([
            'name' => $request->name,
            'date' => $request->date,
            'checkin_time' => $request->checkin_time,
            'checkout_time' => $request->checkout_time,
        ]);

        return redirect()->route('attendances.index')
            ->with('success', 'Attendance record created successfully.');
    }


    public function show(Attendance $attendance)
    {
        return view('attendances.show', compact('attendance'));
    }


    public function edit(Attendance $attendance)
    {
        return view('attendances.edit', compact('attendance'));
    }


    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'checkin_time' => 'nullable|date_format:H:i',
            'checkout_time' => 'nullable|date_format:H:i',
        ]);

        $attendance->update($request->only([
            'name',
            'date',
            'checkin_time',
            'checkout_time'
        ]));

        return redirect()->route('attendances.index')
            ->with('success', 'Attendance record updated successfully.');
    }


    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendances.index')
            ->with('success', 'Attendance record deleted successfully.');
    }

}
