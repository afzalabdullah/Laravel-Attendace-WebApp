<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Define the possible roles
    const ROLE_ADMIN = 'admin';
    const ROLE_HOD = 'hod';
    const ROLE_HR = 'hr';
    const ROLE_EMPLOYEE = 'employee';

    protected $fillable = [
        'name',
        'email',
        'password',
        'department',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relationship with Employee model based on the department
     * Assuming HODs or Admins are associated with the department.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'Department', 'department');
    }

    /**
     * Check if the user is an Admin
     */
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if the user is a HOD
     */
    public function isHod()
    {
        return $this->role === self::ROLE_HOD;
    }

    /**
     * Check if the user is an HR
     */
    public function isHR()
    {
        return $this->role === self::ROLE_HR;
    }

    /**
     * Check if the user is an Employee
     */
    public function isEmployee()
    {
        return $this->role === self::ROLE_EMPLOYEE;
    }

    /**
     * Get the attendances that this user (Admin, HOD, HR, or Employee) can access.
     * Admins can access all records, HODs can access only their department,
     * Employees can access only their own attendance records.
     */
    public function getAccessibleAttendances()
    {
        if ($this->isAdmin() || $this->isHR()) {
            // Admins and HRs can access all attendance records
            return Attendance::with('employee');
        }

        if ($this->isHod()) {
            // HODs can access only their department's attendance records
            return Attendance::with('employee')
                ->whereHas('employee', function ($query) {
                    $query->where('Department', $this->department);
                });
        }

        if ($this->isEmployee()) {
            // Employees can access only their own attendance records
            return Attendance::with('employee')
                ->whereHas('employee', function ($query) {
                    $query->where('Emp_Code', $this->email_prefix()); // Adjust this if needed
                });
        }

        // Default to no access if no role matches
        return collect();
    }

    protected function email_prefix()
    {
        // Extract employee code from email
        return explode('@', $this->email)[0];
    }
    public function employee()
{
    return $this->hasOne(Employee::class);
}


}
