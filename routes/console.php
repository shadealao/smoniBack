<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:facture')
    ->everyMinute() // ou la fréquence désirée
    ->onOneServer() // pour éviter les exécutions multiples en cluster
    ->timezone('Europe/Paris') // votre fuseau horaire
    ->withoutOverlapping(); // empêche les chevauchements

Schedule::command('app:fetch-subscribe')->daily();

