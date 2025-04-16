<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('training_modules')->insert([
            [
                'name' => 'Module Code',
                'description' => 'Apprentissage du code de la route',
                'duration_hours' => 20,
                'required_for_license' => true,
                'display_order' => 1,
                'file' => 'code.pdf',
                'is_active' => true,
            ],
            [
                'name' => 'Autoroute',
                'description' => "Savoir rouler sur l'autoroute",
                'duration_hours' => 3,
                'required_for_license' => false,
                'display_order' => 2,
                'file' => 'autoroute.pdf',
                'is_active' => true,
            ],
        ]);
    }
}
