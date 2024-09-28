<?php

// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Fetch unique departments from Employee table
        $departments = Employee::distinct()->pluck('Department');

        // Pass departments to the view
        return view('users.create', compact('departments'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department' => 'required|string|max:255',
            'role' => 'required|string|in:admin,hod,hr,employee', // Validate role
        ]);

        // Hash the password before storing
        $validatedData['password'] = Hash::make($request->password);

        // Create the user
        User::create($validatedData);

        // Redirect with success message
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Fetch the user and unique departments from the Employee table
        $user = User::findOrFail($id);
        $departments = Employee::distinct()->pluck('Department');

        // Pass the user and departments to the view
        return view('users.edit', compact('user', 'departments'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'department' => 'required|string|max:255',
            'role' => 'required|string|in:admin,hod,hr,employee', // Validate role
        ]);

        // Find the user
        $user = User::findOrFail($id);

        // Update the user's data
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->department = $validatedData['department'];
        $user->role = $validatedData['role']; // Update role
        $user->save();

        // Redirect with success message
        return redirect()->route('admin.index')->with('success', 'User updated successfully.');
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentUser = Auth::user();

        if ($currentUser->role === 'admin') {
            // If the user is an admin, show all users
            $users = User::all();
        } else if ($currentUser->role === 'hod') {
            // If the user is an HOD, show only the HOD's department data
            $users = User::where('department', $currentUser->department)
                         ->where('role', 'hod')
                         ->get();
        } else {
            // Handle other roles or unauthorized access
            abort(403, 'Unauthorized action.');
        }

        return view('users.index', compact('users'));
    }
    public function show($id)
{
    // Fetch the user by ID
    $user = User::findOrFail($id);

    // Pass the user to the view
    return view('users.show', compact('user'));
}
}
