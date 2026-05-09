<?php

namespace Database\Seeders;

use App\Models\TrainingModule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $c1 = TrainingModule::where('code', 'C1')->first();
        $c2 = TrainingModule::where('code', 'C2')->first();

        $c1Steps = [
            [
                'code' => 'C1a',
                'name' => 'Connaître les principaux organes et commandes du véhicule',
                'description' => 'Effectuer des vérifications intérieures et extérieures',
                'details' => "Connaître les principaux organes du véhicule : moteur, transmission, freinage, direction, suspension, feux.\nConnaître les commandes du véhicule : volant, pédales, levier de vitesses, frein à main.",
                'duration_minutes' => 60,
                'display_order' => 1,
                'required_for_completion' => true,
                'validation_criteria' => [
                    'knowledge' => ['organes', 'commandes'],
                    'skills' => ['verifications_interieures', 'verifications_exterieures']
                ]
            ],
            // Ajoutez toutes les étapes C1b à C1i de la même manière
        ];

        $c2Steps = [
            [
                'code' => 'C2a',
                'name' => 'Rechercher la signalisation, les indices utiles et en tenir compte',
                'description' => 'Détecter et interpréter la signalisation',
                'details' => "Détecter la signalisation horizontale et verticale.\nReconnaître et interpréter la signalisation verticale et horizontale.",
                'duration_minutes' => 45,
                'display_order' => 1,
                'required_for_completion' => true,
                'validation_criteria' => [
                    'knowledge' => ['signalisation_verticale', 'signalisation_horizontale'],
                    'skills' => ['detection', 'interpretation']
                ]
            ],
            // Ajoutez toutes les étapes C2b à C2g de la même manière
        ];

        foreach ($c1Steps as $step) {
            $c1->steps()->create($step);
        }

        foreach ($c2Steps as $step) {
            $c2->steps()->create($step);
        }
    }
}
