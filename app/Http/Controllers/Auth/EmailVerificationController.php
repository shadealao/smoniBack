<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * SPA-aware email verification.
 *
 * The verification link is opened directly from the user's inbox in a browser
 * that does NOT carry the SPA's Sanctum session (cross-origin). Fortify's
 * default verify route requires `auth` and 500s in that case. This endpoint
 * relies solely on Laravel's `signed` URL (applied in the route) as proof —
 * the signature cannot be forged and expires — so no session is needed. It
 * always redirects back to the SPA with a status flag instead of returning
 * JSON or an HTML error.
 */
class EmailVerificationController extends Controller
{
    public function verify(Request $request, int $id, string $hash): RedirectResponse
    {
        $front = rtrim((string) env('FRONTEND_URL', config('app.url')), '/');
        $user = User::find($id);

        if (! $user || ! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return redirect($front.'/connexion?email_verified=0');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return redirect($front.'/connexion?email_verified=1');
    }
}
