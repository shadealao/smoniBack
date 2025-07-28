<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FetchSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-subscribe';

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
        Log::info("Strat Action");

        $today = Carbon::today();
        $fifteenDaysFromNow = $today->copy()->addDays(15);

        $expiringSubscriptions = Subscription::whereDate('end_date', $fifteenDaysFromNow)
            ->with('learner','service')
            ->get();
        foreach ($expiringSubscriptions as $subscribe) {
            $sender = new Controller();
            $content = 'Votre abonnement '.$subscribe->service->title.' expirera dans environ 15 jours.';
            $sender->sendmailer($subscribe->learner_id, 'Expiration prochaine de votre abonnement', "Expiration prochaine de votre abonnement", $content, 'subcribe');
        }

        $expiringSubscriptions = Subscription::whereDate('end_date','<', $today)
            ->update([
                "status" => "expired",
            ]);

        Log::info("End Action");

    }
}
