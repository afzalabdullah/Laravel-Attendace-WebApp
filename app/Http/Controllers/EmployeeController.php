<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this line

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retrieve the currently authenticated user
        $user = Auth::user();

        if ($user->role === 'hod') {
            // If the user is an HOD, filter employees based on the user's department
            $employees = Employee::where('Department', $user->department)->get();
        } else {
            // Otherwise, show all employees
            $employees = Employee::all();
        }

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Retrieve the currently authenticated user
        $user = Auth::user();

        if ($user->role === 'hod') {
            // If the user is an HOD, show only the user's department
            $departments = [$user->department];
        } else {
            // Fetch unique departments from Employee table
            $departments = Employee::distinct()->pluck('Department');
        }

        // Pass departments to the view
        return view('employees.create', compact('departments'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Emp_Code' => 'required|unique:employees',
            'Employee_Title' => 'required|max:4',
            'Employee_Name' => 'required|max:33',
            'Department' => 'required|max:34',
            'Designation' => 'required|max:52',
            'Grade' => 'required|max:7',
            'Region' => 'required|max:7',
            'Location' => 'required|max:31',
            'Gender' => 'required|max:6',
            'Date_of_Joining' => 'required|date',
        ]);

        Employee::create($validatedData);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            'Emp_Code' => 'required|unique:employees,Emp_Code,' . $employee->id,
            'Employee_Title' => 'required|max:4',
            'Employee_Name' => 'required|max:33',
            'Department' => 'required|max:34',
            'Designation' => 'required|max:52',
            'Grade' => 'required|max:7',
            'Region' => 'required|max:7',
            'Location' => 'required|max:31',
            'Gender' => 'required|max:6',
            'Date_of_Joining' => 'required|date',
        ]);

        $employee->update($validatedData);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
