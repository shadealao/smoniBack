<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\WithdrawController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Facture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:facture';

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
        Log::info("Cron Generate");
        
        $generate = new WithdrawController();
        $generate->generate();
        Log::info("Cron Generate");

        // Contract1
        $make = new ServiceController();
        $make->contrat();

    }
}
