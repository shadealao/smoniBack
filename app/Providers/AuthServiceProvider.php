<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('admin', fn (User $user) => $user->role === 'admin');
        Gate::define('instructor', fn (User $user) => $user->role === 'instructor');
        Gate::define('learner', fn (User $user) => $user->role === 'learner');

        // Admins short-circuit every Policy check. Return null (not true) means
        // "no opinion" so non-admin users still fall through to the Policy.
        Gate::before(function (User $user) {
            if ($user->role === 'admin') {
                return true;
            }
        });
    }
}
