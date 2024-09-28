<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display the dashboard based on user role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $isEmployee = $user->isEmployee(); // Assuming you have this method to check if the user is an employee

        if ($isEmployee) {
            return $this->employeeDashboard();
        } else {
            return $this->adminDashboard();
        }
    }

    /**
     * Show the employee dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    private function employeeDashboard()
{
    $user = Auth::user();
    $empCode = explode('@', $user->email)[0];
    $today = now()->toDateString();
    $startDate = now()->subDays(30)->toDateString();

    // Fetch attendance data for the last 30 days for the employee
    $attendanceData = \App\Models\Attendance::select(
        DB::raw('date(date) as date'),
        DB::raw('min(checkin_time) as min_checkin_time'),
        DB::raw('max(checkout_time) as max_checkout_time')
    )
    ->where('name', $empCode)
    ->whereBetween('date', [$startDate, $today])
    ->groupBy('date')
    ->orderBy('date')
    ->get();

    // Fetch today's attendance data separately
    $todaysAttendance = \App\Models\Attendance::select(
        DB::raw('min(checkin_time) as min_checkin_time'),
        DB::raw('max(checkout_time) as max_checkout_time')
    )
    ->where('name', $empCode)
    ->whereDate('date', today())
    ->first();

    // Format the attendance data for the view
    $attendanceDates = $attendanceData->pluck('date')->map(function ($date) {
        return Carbon::parse($date)->format('d-m-Y');
    })->toArray();

    $checkInTimes = $attendanceData->pluck('min_checkin_time')->map(function ($time) {
        return $time ? Carbon::parse($time)->format('H:i') : null;
    })->toArray();

    $checkOutTimes = $attendanceData->pluck('max_checkout_time')->map(function ($time) {
        return $time ? Carbon::parse($time)->format('H:i') : null;
    })->toArray();

    // Prepare data as minutes for chart display
    $checkInTimesInMinutes = $attendanceData->pluck('min_checkin_time')->map(function ($time) {
        if ($time) {
            $parts = explode(':', $time);
            return intval($parts[0]) * 60 + intval($parts[1]);
        }
        return 0;
    })->toArray();

    $checkOutTimesInMinutes = $attendanceData->pluck('max_checkout_time')->map(function ($time) {
        if ($time) {
            $parts = explode(':', $time);
            return intval($parts[0]) * 60 + intval($parts[1]);
        }
        return 0;
    })->toArray();

    // Calculate total check-in and check-out times for today
    $totalCheckInTime = $attendanceData->sum(function ($entry) {
        return $entry->min_checkin_time ? Carbon::parse($entry->min_checkin_time)->timestamp : 0;
    });

    $totalCheckOutTime = $attendanceData->sum(function ($entry) {
        return $entry->max_checkout_time ? Carbon::parse($entry->max_checkout_time)->timestamp : 0;
    });

    // Add today's check-in time if available
    if ($todaysAttendance && $todaysAttendance->min_checkin_time) {
        $totalCheckInTime += Carbon::parse($todaysAttendance->min_checkin_time)->timestamp;
    }

    // Convert total seconds to hours and minutes
    $totalCheckInTimeFormatted = gmdate('H:i', $totalCheckInTime);
    $totalCheckOutTimeFormatted = gmdate('H:i', $totalCheckOutTime);

    // Get today's check-in time
    $todayCheckInTime = $todaysAttendance ? Carbon::parse($todaysAttendance->min_checkin_time)->format('h:i A') : 'N/A';

    // Pass data to the view
    return view('employee.dashboard', [
        'attendanceDates' => $attendanceDates,
        'checkInTimes' => $checkInTimes,
        'checkOutTimes' => $checkOutTimes,
        'checkInTimesInMinutes' => $checkInTimesInMinutes,
        'checkOutTimesInMinutes' => $checkOutTimesInMinutes,
        'totalCheckInTime' => $totalCheckInTimeFormatted,
        'totalCheckOutTime' => $totalCheckOutTimeFormatted,
        'todayCheckInTime' => $todayCheckInTime
    ]);
}

    private function adminDashboard()
    {
        $user = Auth::user();
        $department = $user->department;

        $yesterday = now()->subDay()->toDateString();


        // Data preparation
        $onTimeTrend = $this->getOnTimeTrend($yesterday);
        $departmentData = $this->getDepartmentData();
        if(Auth::user()->isAdmin() ||Auth::user()->isHR()) {
            $employeesPresentToday = \App\Models\Attendance::whereDate('date', $yesterday)->count();
            $totalEmployees = \App\Models\Employee::count();
        }else{
            // Fetch employees present today in the department of the logged-in HOD
            $employeesPresentToday = \DB::table('attendance')
            ->join('employees', 'attendance.name', '=', 'employees.Emp_Code') // Adjust the join condition if necessary
            ->whereDate('attendance.date', $yesterday)
            ->where('employees.Department', $department)
            ->distinct('attendance.name') // Ensure unique employees are counted
            ->count('attendance.name');
            $totalEmployees = \DB::table('employees')
            ->where('Department', $department)
            ->count();
        }



        $totalDepartments = \App\Models\Employee::distinct('department')->count('department');

        $attendanceTrend = $this->getAttendanceTrend($yesterday);
        $genderData = $this->getGenderData();
        $regionData = $this->getRegionData();
        $designationData = $this->getDesignationData();
        $gradeData = $this->getGradeData();
        $averageServiceData = $this->getAverageServiceData();
        $employeesByDepartmentCounts = $this->getEmployeesByDepartmentCounts();

        // Pass data to the view
        return view('admin.index', [
            'employeesPresentToday' => $employeesPresentToday,
            'totalEmployees' => $totalEmployees,
            'totalDepartments' => $totalDepartments,
            'departmentLabels' => $departmentData['labels'],
            'departmentData' => $departmentData['data'],
            'attendanceDates' => $attendanceTrend['dates'],
            'attendanceCounts' => $attendanceTrend['counts'],
            'genderLabels' => $genderData['labels'],
            'genderCounts' => $genderData['counts'],
            'regionLabels' => $regionData['labels'],
            'regionCounts' => $regionData['counts'],
            'designationLabels' => $designationData['labels'],
            'designationCounts' => $designationData['counts'],
            'gradeLabels' => $gradeData['labels'],
            'gradeCounts' => $gradeData['counts'],
            'departmentServiceLabels' => $averageServiceData['labels'],
            'averageServiceData' => $averageServiceData['data'],
            'onTimeDates' => $onTimeTrend['dates'],
            'onTimeCounts' => $onTimeTrend['counts'],
            'employeesByDepartmentCounts' => $employeesByDepartmentCounts
        ]);
    }

    /**
     * Get the on-time attendance trend for the last 30 days.
     *
     * @param string $endDate
     * @return array
     */
    private function getOnTimeTrend($endDate)
    {
        $startDate = now()->subDays(30); // 30 days before today

        // Fetch on-time attendance data
        $data = \App\Models\Attendance::select(DB::raw('date(date) as date'), DB::raw('count(*) as count'))
            ->whereBetween('date', [$startDate, $endDate])
            ->whereTime('checkin_time', '>=', '09:00:00')
            ->whereTime('checkin_time', '<=', '10:00:00')
            ->groupBy('date')
            ->orderBy('date') // Ensure dates are sorted chronologically
            ->get();

        // Convert data to arrays
        $dates = $data->pluck('date')->map(function($date) {
            return Carbon::parse($date)->format('d-m-Y');
        })->toArray();

        $counts = $data->pluck('count')->toArray();

        return [
            'dates' => $dates,
            'counts' => $counts
        ];
    }

    /**
     * Get department distribution data.
     *
     * @return array
     */
    private function getDepartmentData()
    {
        $data = DB::table('employees')
            ->select('department', DB::raw('count(*) as count'))
            ->groupBy('department')
            ->get();

        $labels = $data->pluck('department')->toArray();
        $counts = $data->pluck('count')->toArray();

        return [
            'labels' => $labels,
            'data' => $counts
        ];
    }

    /**
     * Get attendance trend data for the last 30 days.
     *
     * @param string $endDate
     * @return array
     */
    private function getAttendanceTrend($endDate)
    {
        $startDate = now()->subDays(30); // 30 days before today

        // Fetch attendance data
        $data = \App\Models\Attendance::select(DB::raw('date(date) as date'), DB::raw('count(*) as count'))
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date') // Ensure dates are sorted chronologically
            ->get();

        // Convert data to arrays and sort dates
        $dates = $data->pluck('date')->map(function($date) {
            return Carbon::parse($date)->format('d-m-Y');
        })->toArray();

        $counts = $data->pluck('count')->toArray();

        // Ensure counts are aligned with sorted dates
        return [
            'dates' => $dates,
            'counts' => $counts
        ];
    }

    /**
     * Get gender distribution data.
     *
     * @return array
     */
    private function getGenderData()
    {
        $data = DB::table('employees')
            ->select('gender', DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->get();

        $labels = $data->pluck('gender')->toArray();
        $counts = $data->pluck('count')->toArray();

        return [
            'labels' => $labels,
            'counts' => $counts
        ];
    }

    /**
     * Get region distribution data.
     *
     * @return array
     */
    private function getRegionData()
    {
        $data = DB::table('employees')
            ->select('region', DB::raw('count(*) as count'))
            ->groupBy('region')
            ->get();

        $labels = $data->pluck('region')->toArray();
        $counts = $data->pluck('count')->toArray();

        return [
            'labels' => $labels,
            'counts' => $counts
        ];
    }

    /**
     * Get designation distribution data.
     *
     * @return array
     */
    private function getDesignationData()
    {
        $data = DB::table('employees')
            ->select('designation', DB::raw('count(*) as count'))
            ->groupBy('designation')
            ->get();

        $labels = $data->pluck('designation')->toArray();
        $counts = $data->pluck('count')->toArray();

        return [
            'labels' => $labels,
            'counts' => $counts
        ];
    }

    /**
     * Get grade distribution data.
     *
     * @return array
     */
    private function getGradeData()
    {
        $data = DB::table('employees')
            ->select('grade', DB::raw('count(*) as count'))
            ->groupBy('grade')
            ->get();

        $labels = $data->pluck('grade')->toArray();
        $counts = $data->pluck('count')->toArray();

        return [
            'labels' => $labels,
            'counts' => $counts
        ];
    }

    /**
     * Calculate average length of service by department.
     *
     * @return array
     */
    private function getAverageServiceData()
    {
        $data = DB::table('employees')
            ->select('department', DB::raw('AVG(TIMESTAMPDIFF(YEAR, Date_of_Joining, NOW())) as avg_service'))
            ->groupBy('department')
            ->get();

        $labels = $data->pluck('department')->toArray();
        $averageServiceData = $data->pluck('avg_service')->toArray();

        return [
            'labels' => $labels,
            'data' => $averageServiceData
        ];
    }

    /**
     * Get the count of employees by department.
     *
     * @return array
     */
    private function getEmployeesByDepartmentCounts()
    {
        $data = DB::table('employees')
            ->select('department', DB::raw('count(*) as count'))
            ->groupBy('department')
            ->get();

        return $data->pluck('count')->toArray();
    }
}
