<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendOtpCode extends Notification
{
    use Queueable;

    protected $otpCode;

    public function __construct($otpCode)
    {
        $this->otpCode = $otpCode;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Votre code de réinitialisation de mot de passe')
                    ->line('Vous avez demandé à réinitialiser votre mot de passe.')
                    ->line('Votre code OTP est : **' . $this->otpCode . '**')
                    ->line('Ce code est valable pendant 10 minutes.')
                    ->line('Si vous n\'avez pas fait cette demande, veuillez ignorer cet email.');
    }
}