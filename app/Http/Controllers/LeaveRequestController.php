<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Employee; // Ensure this is correctly imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
         // Extract emp_code from the email
         $empCode = explode('@', $user->email)[0];

         // Fetch the employee record based on emp_code
         $employee = Employee::where('Emp_Code', $empCode)->first();

        // If the user is an HOD, show all leave requests for the department
        if ($user->isHOD()) {
            $leaveRequests = LeaveRequest::where('department', $user->department)
                ->orderBy('created_at', 'desc') // Order by creation date, newest first
                ->get();
        } elseif ($user->isAdmin() || $user->isHR()) {
            $leaveRequests = LeaveRequest::orderBy('created_at', 'desc') // Order by creation date, newest first
                ->get();
        } else {
            // If the user is not an HOD, show only their leave requests
            $leaveRequests = LeaveRequest::where('employee_id', $employee->id)
                ->orderBy('created_at', 'desc') // Order by creation date, newest first
                ->get();
        }

        return view('leave_requests.index', compact('leaveRequests'));
    }
    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $leaveRequest->status = $request->status;
        $leaveRequest->save();

        return redirect()->route('leave_requests.index')->with('success', 'Leave request status updated successfully.');
    }


    public function create()
    {
        return view('leave_requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'department' => 'required|string',
            'leave_type' => 'required|in:sick,casual,annual' // Validate the leave type
        ]);

        $user = Auth::user();

        // Extract emp_code from the email
        $empCode = explode('@', $user->email)[0];

        // Fetch the employee record based on emp_code
        $employee = Employee::where('Emp_Code', $empCode)->first();

        if (!$employee) {
            return redirect()->back()->withErrors('Employee record not found.');
        }

        LeaveRequest::create([
            'employee_id' => $employee->id, // Use the employee's ID
            'emp_code' => $empCode, // Store the extracted employee code
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'department' => $request->department,
            'leave_type' => $request->leave_type, // Store the leave type
        ]);

        return redirect()->route('leave_requests.index')->with('success', 'Leave request submitted successfully.');
    }


    public function show(LeaveRequest $leaveRequest)
    {
        return view('leave_requests.show', compact('leaveRequest'));
    }

    public function edit(LeaveRequest $leaveRequest)
    {
        return view('leave_requests.edit', compact('leaveRequest'));
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $leaveRequest->update(['status' => $request->status]);

        return redirect()->route('leave_requests.index')->with('success', 'Leave request status updated.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();
        return redirect()->route('leave_requests.index')->with('success', 'Leave request deleted.');
    }
}
