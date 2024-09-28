<?php
// app/Policies/LeaveRequestPolicy.php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveRequestPolicy
{
    use HandlesAuthorization;

    public function update(User $user, LeaveRequest $leaveRequest)
    {
        // Allow HOD to update any leave request, or employees to update their own
        return $user->isHOD() || $user->id === $leaveRequest->employee_id;
    }
}
