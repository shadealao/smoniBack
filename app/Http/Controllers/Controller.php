<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function sendmail(Request $request, Appointment $appointment, MailService $mailer){
        
        $header = "Rappel sur Smoni";
        $subject = "Smoni vous rappel votr réservation";

        if($appointment->learner_id){

            $content = $appointment->learner;
            $mailer->reminderMail( null,$appointment->learner->email, $header, $content, $subject,);
        }

        if($appointment->instructor){

            $content = $appointment->instructor;
            $mailer->reminderMail( null,$appointment->instructor->email, $header, $content, $subject,);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email envoyé',
        ], 200);
    }
}
