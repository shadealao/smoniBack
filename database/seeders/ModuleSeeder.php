<?php

namespace Database\Seeders;

use App\Models\TrainingModule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'code' => 'C1',
                'name' => 'Maîtriser le maniement du véhicule dans un trafic faible ou nul',
                'description' => 'Compétence de base pour la manipulation du véhicule',
                'duration_hours' => 20,
                'required_for_license' => true,
                'display_order' => 1,
            ],
            [
                'code' => 'C2',
                'name' => 'Appréhender la route et circuler dans des conditions normales',
                'description' => 'Compétence pour la circulation en conditions normales',
                'duration_hours' => 15,
                'required_for_license' => true,
                'display_order' => 2,
            ],
            // Ajoutez d'autres modules au besoin
        ];

        foreach ($modules as $module) {
            TrainingModule::create($module);
        }
    }
}
