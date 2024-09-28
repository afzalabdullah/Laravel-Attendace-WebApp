<?php
// app/Models/LeaveRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'emp_code',
        'start_date',
        'end_date',
        'reason',
        'department',
        'leave_type',
        'status',
    ];

    // In LeaveRequest model
public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id');
}

}

