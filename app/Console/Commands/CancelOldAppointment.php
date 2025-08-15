<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Subscription;

class CancelOldAppointment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-old-appointment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $appointments = Appointment::where('status','scheduled')->with('learner','instructor')->orderBy('date','desc')->get();

        foreach ($appointments as $appointment) {
            $now = new DateTime();
            $date = new DateTime($appointment->date);

            if($date < $now){
                $availability = $appointment->availability;
                $vehicle = $availability->vehicle;

                $subscriptions = Subscription::where('learner_id', $appointment->learner_id)
                    ->where('gearbox', $vehicle->gearbox_type)
                    ->where('status', 'active')
                    ->orderBy('created_at','desc')
                    ->first();
                
                if($subscriptions) {
                    $subscriptions->hour+= 1;
                    $subscriptions->save();
                }
                $sender = new Controller();
                $sender->sendmailer( $appointment->instructor_id, 'Annullation Rendez-vous', 'Annullation Rendez-vous', 'La résevation pour un cours qui aura lieu '.$appointment->date.' de '.$appointment->start_time.' à '.$appointment->end_time.' a été annulé à cause du delais passer', 'appointment');

                $sender->sendmailer( $appointment->learner_id, 'Annullation Rendez-vous', 'Annullation Rendez-vous', 'La résevation pour un cours qui aura lieu '.$appointment->date.' de '.$appointment->start_time.' à '.$appointment->end_time.' a été annulé à cause du delais passer', 'appointment');

                $appointment->update([
                    // 'availability_id' => null,
                    'status' => 'cancelled',
                    'cancellation_reason' => "Délai d'attente passé",
                    'canceled_by_monitor' => true
                ]);
            }
        }

    }
}
