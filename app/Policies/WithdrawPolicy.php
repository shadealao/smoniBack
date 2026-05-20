<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Withdraw;

class WithdrawPolicy
{
    public function view(User $user, Withdraw $withdraw): bool
    {
        return $user->id === $withdraw->monitor_id;
    }

    public function update(User $user, Withdraw $withdraw): bool
    {
        return $user->id === $withdraw->monitor_id;
    }

    public function delete(User $user, Withdraw $withdraw): bool
    {
        return $user->id === $withdraw->monitor_id;
    }
}
