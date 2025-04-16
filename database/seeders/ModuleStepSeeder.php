<?php

namespace Database\Seeders;

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
        DB::table('module_steps')->insert([
            [
                'module_id' => 1,
                'name' => 'Introduction au Code',
                'description' => 'Première étape théorique',
                'duration_minutes' => 60,
                'step_type' => 'theory',
                'display_order' => 1,
                'required_for_completion' => true,
            ],
            [
                'module_id' => 1,
                'name' => 'Créneaux',
                'description' => 'Manœuvres de stationnement',
                'duration_minutes' => 45,
                'step_type' => 'practice',
                'display_order' => 2,
                'required_for_completion' => true,
            ],
        ]);
    }
}
