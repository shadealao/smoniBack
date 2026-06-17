<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Point at the SPA-aware, no-auth verify route (see routes/web.php).
        // Fortify's default verification.verify requires auth and 500s when the
        // link is opened from an email without the Sanctum session.
        $verificationUrl = URL::temporarySignedRoute(
            'email.verify.account',
            now()->addMinutes(60),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );

        return (new MailMessage)
            ->subject('Finalisez votre inscription sur SMONI')
            ->markdown('emails.verify-email', [
                'verificationUrl' => $verificationUrl,
                'user' => $notifiable
            ]);
    }
}
