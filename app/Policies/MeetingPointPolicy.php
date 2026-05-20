<?php

namespace App\Policies;

use App\Models\MeetingPoint;
use App\Models\User;

class MeetingPointPolicy
{
    public function view(User $user, MeetingPoint $meetingPoint): bool
    {
        return $user->id === $meetingPoint->instructor_id;
    }

    public function update(User $user, MeetingPoint $meetingPoint): bool
    {
        return $user->id === $meetingPoint->instructor_id;
    }

    public function delete(User $user, MeetingPoint $meetingPoint): bool
    {
        return $user->id === $meetingPoint->instructor_id;
    }
}
