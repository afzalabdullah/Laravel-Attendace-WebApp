<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Attendance extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'attendance';

    // The attributes that are mass assignable
    protected $fillable = [
        'name',
        'date',
        'checkin_time',
        'checkout_time',
    ];

    // Cast attributes to Carbon instances
    protected $casts = [
        'date' => 'date',
        'checkin_time' => 'datetime:H:i',
        'checkout_time' => 'datetime:H:i',
    ];

    // Define the relationship with Employee model
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'name', 'Emp_Code');
    }

    // If you don't want the timestamps (created_at and updated_at)
    public $timestamps = true;

    /**
     * Scope to get attendances that an Admin can access (all attendances).
     */
    public function scopeForAdmin($query)
    {
        return $query->with('employee');
    }

    /**
     * Scope to get attendances that a HOD can access (only their department's attendances).
     */
    public function scopeForHod($query, $department)
    {
        return $query->with('employee')
            ->whereHas('employee', function ($query) use ($department) {
                $query->where('Department', $department);
            });
    }

    /**
     * Get attendances based on the user's role.
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAttendancesForUser(User $user)
    {
        if ($user->isAdmin()) {
            return self::forAdmin()->get();
        }

        if ($user->isHod()) {
            return self::forHod($user->department)->get();
        }

        // Default to no access if no role matches
        return collect();
    }
}
