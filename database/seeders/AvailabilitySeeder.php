<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('availabilities')->insert([
            [
                'instructor_id' => 2,
                'meeting_point_id' => 1,
                'vehicle_id' => 1,
                'day_of_week' => 'mercredi',
                'date' => now()->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'status' => true,
            ],
        ]);
    }
}
