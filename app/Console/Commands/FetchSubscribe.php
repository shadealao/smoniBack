<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\ServiceController;
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

        $make = new ServiceController();
        $make->fetchSubscribe();
        
        Log::info("End Action");

    }
}
