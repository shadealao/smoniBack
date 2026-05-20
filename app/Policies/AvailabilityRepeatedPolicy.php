<?php

namespace App\Policies;

use App\Models\AvailabilityRepeated;
use App\Models\User;

class AvailabilityRepeatedPolicy
{
    public function view(User $user, AvailabilityRepeated $availability): bool
    {
        return $user->id === $availability->monitor_id;
    }

    public function update(User $user, AvailabilityRepeated $availability): bool
    {
        return $user->id === $availability->monitor_id;
    }

    public function delete(User $user, AvailabilityRepeated $availability): bool
    {
        return $user->id === $availability->monitor_id;
    }
}
