<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Service\MailService;


abstract class Controller
{
    public function sendmailer($sender_id, $receiver_id, $header, $subject, $content, $type){
        
        $receiver = User::find($receiver_id);

        Notification::create([
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id, 
            'title' => $header,
            'data' => $content, 
            'type' => $type,
        ]);
        
        $mail = new Mail;
        $mailer = new MailService($mail);

        $mailer->messageMail( null,$receiver->email, $header, $content, $subject);

        return true;
        // Utilisation
        // $this->sendmailer($sender_id, $receiver_id, $header, $subject, $content, $type);
    }
}
