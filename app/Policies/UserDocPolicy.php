<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserDoc;

class UserDocPolicy
{
    public function view(User $user, UserDoc $userDoc): bool
    {
        return $user->id === $userDoc->user_id;
    }

    public function update(User $user, UserDoc $userDoc): bool
    {
        return $user->id === $userDoc->user_id;
    }

    public function delete(User $user, UserDoc $userDoc): bool
    {
        return $user->id === $userDoc->user_id;
    }
}
