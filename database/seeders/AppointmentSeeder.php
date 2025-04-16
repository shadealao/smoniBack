<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('appointments')->insert([
            [
                'learner_id' => 3,
                'instructor_id' => 2,
                'availability_id' => 1,
                'vehicle_id' => 1,
                'date' => now()->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'duration' => 60,
                'status' => 'scheduled',
                'cancellation_reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'price' => 25.00,
                'lesson_notes' => json_encode(['observation' => 'Bonne concentration']),
                'presence_student' => true,
                'presence_monitor' => true,
                'finished' => false,
                'tag' => null,
            ],
        ]);
    }
}
