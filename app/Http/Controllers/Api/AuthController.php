<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearnerProfile;
use Illuminate\Http\Request;

/**
 * Slim auth controller: register/login/logout/email-verification and
 * password reset are owned by Laravel Fortify now (see
 * App\Providers\FortifyServiceProvider + config/fortify.php).
 *
 * Only domain-specific endpoints that don't fit Fortify remain here:
 *   - checkAsk: learner test gating after first login
 *   - mailContact: public contact form
 */
class AuthController extends Controller
{
    public function checkAsk(Request $request)
    {
        $validated = $request->validate([
            'point' => 'required',
        ]);

        $hour = $this->checkPoint($request->point);

        $learner = LearnerProfile::where('user_id', auth()->user()->id)->first();

        $learner->update([
            'test_passed' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Félicitation',
            'data' => $hour,
        ], 200);
    }

    public function mailContact(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'object' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $this->sendmailercontact(
            'Formulaire de contact',
            $validated['object'],
            'Message de : '.$validated['firstname'].' '.$validated['lastname'].' ('.$validated['email'].')  '.$validated['phone'].'.  '.$validated['message'],
            $validated['object']
        );

        return response()->json([
            'success' => true,
            'message' => 'Votre message a été envoyé avec succès. Nous vous répondrons bientôt.',
        ], 200);
    }
}
