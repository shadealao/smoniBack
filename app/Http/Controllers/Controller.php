<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Service\MailService;


abstract class Controller
{
    public function sendmailer($receiver_id, $header, $subject, $content, $type){
        
        $receiver = User::find($receiver_id);
        $sender = User::where('role','admin')->first();
        Notification::create([
            'sender_id' => $sender->id,
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

    public function checkPoint($point){
        if ($points > 12) {
            return 20; // "> 12 points" -> 20 heures
        } elseif ($points >= 11 && $points <= 12) {
            return 21; // "+ 12 points", "+ 11 points" -> 21 heures
        } elseif ($points >= 9 && $points <= 10) {
            return 22; // "+ 10 points", "+ 9 points" -> 22 heures
        } elseif ($points >= 7 && $points <= 8) {
            return 23; // "+ 8 points", "+ 7 points" -> 23 heures
        } elseif ($points >= 5 && $points <= 6) {
            return 24; // "+ 6 points", "+ 5 points" -> 24 heures
        } elseif ($points >= 3 && $points <= 4) {
            return 25; // "+ 4 points", "+ 3 points" -> 25 heures
        } elseif ($points >= 1 && $points <= 2) {
            return 26; // "+ 2 points", "+ 1 point" -> 26 heures
        } elseif ($points >= -1 && $points <= 0) {
            return 27; // "+ 0 point", "- 1 point" -> 27 heures
        } elseif ($points >= -3 && $points <= -2) {
            return 28; // "- 2 points", "- 3 points" -> 28 heures
        } elseif ($points >= -5 && $points <= -4) {
            return 29; // "- 4 points", "- 5 points" -> 29 heures
        } elseif ($points >= -7 && $points <= -6) {
            return 30; // "- 6 points", "- 7 points" -> 30 heures
        } elseif ($points >= -9 && $points <= -8) {
            return 31; // "- 8 points", "- 9 points" -> 31 heures
        } elseif ($points >= -11 && $points <= -10) {
            return 32; // "- 10 points", "- 11 points" -> 32 heures
        } elseif ($points >= -13 && $points <= -12) {
            return 33; // "- 12 points", "- 13 points" -> 33 heures
        } elseif ($points >= -15 && $points <= -14) {
            return 34; // "- 14 points", "- 15 points" -> 34 heures
        } elseif ($points >= -17 && $points <= -16) {
            return 35; // "- 16 points", "- 17 points" -> 35 heures
        } elseif ($points >= -19 && $points <= -18) {
            return 36; // "- 18 points", "- 19 points" -> 36 heures
        } elseif ($points >= -21 && $points <= -20) {
            return 37; // "- 20 points", "- 21 points" -> 37 heures
        } elseif ($points >= -23 && $points <= -22) {
            return 38; // "- 22 points", "- 23 points" -> 38 heures
        } elseif ($points == -24) {
            return 39; // "- 24 points" -> 39 heures
        } else {
            return 0; 
        }
    }
}
