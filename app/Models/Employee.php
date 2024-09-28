<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Employee extends Model
{
    protected $fillable = [
        'Emp_Code', 'Employee_Title', 'Employee_Name', 'Department', 
        'Designation', 'Grade', 'Region', 'Location', 'Gender', 'Date_of_Joining'
    ];

    // Define the relationship with Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'name', 'Emp_Code');
    }
}
