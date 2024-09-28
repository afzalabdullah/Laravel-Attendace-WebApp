<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use App\Exports\IndividualAttendanceExport;
use App\Exports\DepartmentReportExport;
use App\Exports\IndividualReportExport;
use Auth;
class ReportController extends Controller
{
    public function departmentReportForm()
{
    // Get the currently authenticated user
    $user = auth()->user();

    // Filter departments based on user role
    if ($user->isAdmin() || $user->isHR()) {
        // Admins and HR can see all departments
        $departments = Employee::distinct()->pluck('Department');
    } elseif ($user->isHod()) {
        // HODs can see only their own department
        $departments = Employee::where('Department', $user->department)
                               ->distinct()
                               ->pluck('Department');
    } else {
        // Default to an empty collection if the role doesn't match
        $departments = collect();
    }

    // Return the view with departments
    return view('reports.department', compact('departments'));
}

    public function individualReportForm ()
    {
        return view('reports.individual');
    }


    public function generateDepartmentReport(Request $request)
    {
        $department = $request->input('department');
        $startDate = \Carbon\Carbon::parse($request->input('start_date'));
        $endDate = \Carbon\Carbon::parse($request->input('end_date'));

        if (!$department) {
            return back()->withErrors(['department' => 'Department is required.']);
        }

        // Fetch employee codes for the given department
        $employeeCodes = \DB::table('employees')
            ->where('Department', $department)
            ->pluck('Emp_Code')
            ->toArray();

        if (empty($employeeCodes)) {
            return back()->withErrors(['department' => 'No employees found in this department.']);
        }

        // Fetch attendance records for the employees in the department
        $attendances = Attendance::whereIn('name', $employeeCodes)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy(function($item) {
                return $item->name . '-' . \Carbon\Carbon::parse($item->date)->format('Y-m-d');
            });

        // Fetch leave records for the employees in the department
        $leaves = LeaveRequest::whereIn('emp_code', $employeeCodes)
            ->where('status', 'approved')
            ->where(function($query) use ($startDate, $endDate) {
                $query->where(function($query) use ($startDate, $endDate) {
                    // Leaves that start before the end date and end after the start date
                    $query->where('start_date', '<=', $endDate->format('Y-m-d'))
                        ->where('end_date', '>=', $startDate->format('Y-m-d'));
                });
            })
            ->get()
            ->keyBy(function($item) {
                return $item->emp_code . '-' . \Carbon\Carbon::parse($item->start_date)->format('Y-m-d');
            });

        // Generate all dates between start and end date for each employee
        $reportData = [];

        foreach ($employeeCodes as $empCode) {
            $employee = \DB::table('employees')->where('Emp_Code', $empCode)->first();
            $employeeName = $employee ? $employee->Employee_Name : 'Unknown';

            $currentDate = $startDate->copy();
            $endDate = $endDate->copy();

            while ($currentDate->lte($endDate)) {
                $date = $currentDate->format('Y-m-d');
                $key = $empCode . '-' . $date;

                $attendance = $attendances->get($key);
                $leave = $leaves->get($empCode . '-' . $date);

                $checkin = $attendance && $attendance->checkin_time ? \Carbon\Carbon::parse($attendance->checkin_time) : null;
                $checkout = $attendance && $attendance->checkout_time ? \Carbon\Carbon::parse($attendance->checkout_time) : null;

                $dutyHours = '0 Hours 0 Minutes';
                $status = 'Absent';
                $leaveType = '';

                if ($leave) {
                    $status = 'On Leave';
                    $dutyHours = 'Leave Approved';
                    $leaveType = $leave->leave_type; // Include leave type
                } elseif ($checkin && $checkout) {
                    if ($checkout->greaterThan($checkin)) {
                        $totalMinutes = $checkout->diffInMinutes($checkin);
                        $hours = floor($totalMinutes / 60);
                        $minutes = $totalMinutes % 60;
                        $dutyHours = "{$hours} Hours {$minutes} Minutes";
                        $status = 'Present';
                    } else {
                        $status = 'Absent';
                    }
                } else {
                    if ($checkin || $checkout) {
                        $status = 'Absent';
                    }
                }

                $reportData[] = [
                    'emp_code' => $empCode,
                    'name' => $employeeName,
                    'department' => $department,
                    'date' => $currentDate->format('d-m-Y'),
                    'checkin_time' => $checkin ? $checkin->format('H:i:s') : '',
                    'checkout_time' => $checkout ? $checkout->format('H:i:s') : '',
                    'duty_hours' => $dutyHours,
                    'status' => $status,
                    'leave_type' => $leaveType, // Add leave type to report data
                ];

                $currentDate->addDay();
            }
        }

        $reportData = collect($reportData)->sortBy(['emp_code', 'date'])->values();

        return view('reports.show_department_report', compact('reportData', 'department', 'startDate', 'endDate'));
    }
    public function generateIndividualReport(Request $request)
    {
        $user = Auth::user();
        $empCode = $request->input('emp_code');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($user->isEmployee()) {
            $empCode = explode('@', $user->email)[0];
        }

        if (!$empCode) {
            return back()->withErrors(['emp_code' => 'Employee Code is required.']);
        }

        // Fetch employee details
        $employee = \DB::table('employees')->where('Emp_Code', $empCode)->first();
        $employeeName = $employee ? $employee->Employee_Name : 'Unknown';
        $department = $employee ? $employee->Department : 'Unknown';

        // Fetch attendance records
        $attendances = Attendance::where('name', $empCode)
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->keyBy('date'); // Key by date for easier merging

        // Fetch leave records
        $leaves = LeaveRequest::where('emp_code', $empCode)
            ->where('status', 'approved')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                    });
            })
            ->get();

        // Convert leave requests to a more usable format
        $leaveDays = [];
        foreach ($leaves as $leave) {
            $current = \Carbon\Carbon::parse($leave->start_date);
            $end = \Carbon\Carbon::parse($leave->end_date);

            while ($current->lte($end)) {
                $leaveDays[$current->format('Y-m-d')] = [
                    'status' => 'on Leave Today',
                    'leave_type' => $leave->leave_type // Store leave type
                ];
                $current->addDay();
            }
        }

        // Generate all dates between start and end date
        $currentDate = \Carbon\Carbon::parse($startDate);
        $endDate = \Carbon\Carbon::parse($endDate);
        $allDates = [];

        while ($currentDate->lte($endDate)) {
            $date = $currentDate->format('Y-m-d');
            $allDates[$date] = [
                'date' => $currentDate->format('d-m-Y'),
                'checkin_time' => null,
                'checkout_time' => null,
                'duty_hours' => '',
                'status' => $leaveDays[$date]['status'] ?? 'Absent',
                'leave_type' => $leaveDays[$date]['leave_type'] ?? '', // Include leave type
                'name' => $employeeName,
                'department' => $department,
            ];
            $currentDate->addDay();
        }

        // Merge attendance data into the allDates array
        foreach ($attendances as $attendance) {
            $date = \Carbon\Carbon::parse($attendance->date)->format('Y-m-d');
            if (isset($allDates[$date])) {
                $checkin = $attendance->checkin_time ? \Carbon\Carbon::parse($attendance->checkin_time) : null;
                $checkout = $attendance->checkout_time ? \Carbon\Carbon::parse($attendance->checkout_time) : null;

                if ($checkin && $checkout && $checkout->greaterThan($checkin)) {
                    $totalMinutes = $checkout->diffInMinutes($checkin);
                    $hours = floor($totalMinutes / 60);
                    $minutes = $totalMinutes % 60;
                    $allDates[$date]['duty_hours'] = "{$hours} Hours {$minutes} Minutes";
                    $allDates[$date]['checkin_time'] = $checkin->format('H:i:s');
                    $allDates[$date]['checkout_time'] = $checkout->format('H:i:s');
                    // Clear leave status if attendance exists
                    $allDates[$date]['status'] = '';
                    $allDates[$date]['leave_type'] = ''; // Clear leave type if attendance exists
                } elseif ($checkin && !$checkout) {
                    // Only check-in exists
                    $allDates[$date]['checkin_time'] = $checkin->format('H:i:s');
                    $allDates[$date]['status'] = 'Absent';
                } elseif (!$checkin && $checkout) {
                    // Only check-out exists
                    $allDates[$date]['checkout_time'] = $checkout->format('H:i:s');
                    $allDates[$date]['status'] = 'Absent';
                }
            }
        }

        $reportData = collect($allDates)->sortKeys()->values();

        return view('reports.show_individual_report', compact('reportData', 'empCode', 'startDate', 'endDate', 'department', 'employeeName'));
    }
    public function downloadExcel(Request $request)
{
    $department = $request->input('department');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Fetch employee codes for the given department
    $employeeCodes = \DB::table('employees')
        ->where('Department', $department)
        ->pluck('Emp_Code')
        ->toArray();

    if (empty($employeeCodes)) {
        return back()->withErrors(['department' => 'No employees found in this department.']);
    }

    // Fetch attendance records for the employees in the department
    $attendances = Attendance::whereIn('name', $employeeCodes)
        ->whereBetween('date', [$startDate, $endDate])
        ->get()
        ->keyBy(function($item) {
            return $item->name . '-' . \Carbon\Carbon::parse($item->date)->format('Y-m-d');
        });

    // Fetch leave records for the employees in the department
    $leaves = LeaveRequest::whereIn('emp_code', $employeeCodes)
        ->where('status', 'approved')
        ->where(function($query) use ($startDate, $endDate) {
            $query->where(function($query) use ($startDate, $endDate) {
                // Leaves that start before the end date and end after the start date
                $query->where('start_date', '<=', $endDate)
                    ->where('end_date', '>=', $startDate);
            });
        })
        ->get()
        ->keyBy(function($item) {
            return $item->emp_code . '-' . \Carbon\Carbon::parse($item->start_date)->format('Y-m-d');
        });

    // Generate all dates between start and end date for each employee
    $reportData = [];

    foreach ($employeeCodes as $empCode) {
        $employee = \DB::table('employees')->where('Emp_Code', $empCode)->first();
        $employeeName = $employee ? $employee->Employee_Name : 'Unknown';

        $currentDate = \Carbon\Carbon::parse($startDate);
        $endDate = \Carbon\Carbon::parse($endDate);

        while ($currentDate->lte($endDate)) {
            $date = $currentDate->format('Y-m-d');
            $key = $empCode . '-' . $date;

            $attendance = $attendances->get($key);
            $leave = $leaves->get($empCode . '-' . $date);

            $checkin = $attendance && $attendance->checkin_time ? \Carbon\Carbon::parse($attendance->checkin_time) : null;
            $checkout = $attendance && $attendance->checkout_time ? \Carbon\Carbon::parse($attendance->checkout_time) : null;

            $dutyHours = '0 Hours 0 Minutes';
            $status = 'Absent';
            $leaveType = '';

            if ($leave) {
                $status = 'On Leave';
                $dutyHours = 'Leave Approved';
                $leaveType = $leave->leave_type;
            } elseif ($checkin && $checkout) {
                if ($checkout->greaterThan($checkin)) {
                    $totalMinutes = $checkout->diffInMinutes($checkin);
                    $hours = floor($totalMinutes / 60);
                    $minutes = $totalMinutes % 60;
                    $dutyHours = "{$hours} Hours {$minutes} Minutes";
                    $status = 'Present';
                } else {
                    $status = 'Absent';
                }
            } else {
                if ($checkin || $checkout) {
                    $status = 'Absent';
                }
            }

            $reportData[] = [
                'emp_code' => $empCode,
                'name' => $employeeName,
                'department' => $department,
                'date' => $currentDate->format('d-m-Y'),
                'checkin_time' => $checkin ? $checkin->format('H:i:s') : '',
                'checkout_time' => $checkout ? $checkout->format('H:i:s') : '',
                'duty_hours' => $dutyHours,
                'status' => $status,
                'leave_type' => $leaveType,
            ];

            $currentDate->addDay();
        }
    }
    // Format the filename
    $filename = "{$department}_Report_{$startDate}_to_{$endDate}.xlsx";
    $reportData = collect($reportData)->sortBy(['emp_code', 'date'])->values();
    return Excel::download(new DepartmentReportExport($reportData), $filename);
}

public function downloadIndividualExcel(Request $request)
{
    $empCode = $request->input('emp_code');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Fetch employee details
    $employee = \DB::table('employees')->where('Emp_Code', $empCode)->first();

    if (!$employee) {
        return back()->withErrors(['emp_code' => 'Employee not found.']);
    }

    $employeeName = $employee->Employee_Name;
    $department = $employee->Department;

    $attendances = Attendance::where('name', $empCode)
        ->whereBetween('date', [$startDate, $endDate])
        ->get()
        ->keyBy('date');

    $leaves = LeaveRequest::where('emp_code', $empCode)
        ->where('status', 'approved')
        ->where(function($query) use ($startDate, $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate])
                ->orWhere(function($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                });
        })
        ->get();

    $leaveDays = [];
    foreach ($leaves as $leave) {
        $current = \Carbon\Carbon::parse($leave->start_date);
        $end = \Carbon\Carbon::parse($leave->end_date);

        while ($current->lte($end)) {
            $leaveDays[$current->format('Y-m-d')] = [
                'status' => 'On Leave Today',
                'leave_type' => $leave->leave_type
            ];
            $current->addDay();
        }
    }

    $currentDate = \Carbon\Carbon::parse($startDate);
    $endDate = \Carbon\Carbon::parse($endDate);
    $allDates = [];

    while ($currentDate->lte($endDate)) {
        $date = $currentDate->format('Y-m-d');
        $allDates[$date] = [
            'emp_code' => $empCode,  // Include emp_code
            'name' => $employeeName,
            'department' => $department,
            'date' => $currentDate->format('d-m-Y'),
            'checkin_time' => null,
            'checkout_time' => null,
            'duty_hours' => '',
            'status' => $leaveDays[$date]['status'] ?? 'Absent',
            'leave_type' => $leaveDays[$date]['leave_type'] ?? '',
        ];
        $currentDate->addDay();
    }

    foreach ($attendances as $attendance) {
        $date = \Carbon\Carbon::parse($attendance->date)->format('Y-m-d');
        if (isset($allDates[$date])) {
            $checkin = $attendance->checkin_time ? \Carbon\Carbon::parse($attendance->checkin_time) : null;
            $checkout = $attendance->checkout_time ? \Carbon\Carbon::parse($attendance->checkout_time) : null;

            if ($checkin && $checkout && $checkout->greaterThan($checkin)) {
                $totalMinutes = $checkout->diffInMinutes($checkin);
                $hours = floor($totalMinutes / 60);
                $minutes = $totalMinutes % 60;
                $allDates[$date]['duty_hours'] = "{$hours} Hours {$minutes} Minutes";
                $allDates[$date]['checkin_time'] = $checkin->format('H:i:s');
                $allDates[$date]['checkout_time'] = $checkout->format('H:i:s');
                $allDates[$date]['status'] = '';
                $allDates[$date]['leave_type'] = '';
            } elseif ($checkin && !$checkout) {
                $allDates[$date]['checkin_time'] = $checkin->format('H:i:s');
                $allDates[$date]['status'] = 'Absent';
            } elseif (!$checkin && $checkout) {
                $allDates[$date]['checkout_time'] = $checkout->format('H:i:s');
                $allDates[$date]['status'] = 'Absent';
            }
        }
    }

    $reportData = collect($allDates)->sortKeys()->values();

    // Format the filename
    $filename = "{$empCode}_Report_{$startDate}_to_{$endDate}.xlsx";

    return Excel::download(new IndividualReportExport($reportData), $filename);
}



}
