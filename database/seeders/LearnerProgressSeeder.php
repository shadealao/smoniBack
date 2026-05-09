<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LearnerProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('learner_progres')->insert([
            [
                'learner_id' => 3,
                'module_id' => 1,
                'current_step_id' => 1,
                'status' => 'in_progress',
                'started_at' => now()->subDays(2),
                'completed_at' => null,
                'instructor_notes' => 'Bon démarrage.',
            ],
        ]);
    }
}
