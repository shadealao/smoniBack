<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AvailabilityRepeated;

class AvailabilityRepeatedSeeder extends Seeder
{
    public function run(): void
    {
        AvailabilityRepeated::create([
            'monitor_id' => 3,
            'meeting_point_id' => 1,
            'vehicle_id' => 1,
            'day_of_week' => 'lundi',
            'time' => json_encode([
                ['start' => '08:00', 'end' => '09:00'],
                ['start' => '14:00', 'end' => '15:00'],
            ]),
            'status' => true,
        ]);
        AvailabilityRepeated::create([
            'monitor_id' => 3,
            'meeting_point_id' => 1,
            'vehicle_id' => 1,
            'day_of_week' => 'mardi',
            'time' => json_encode([
                ['start' => '09:00', 'end' => '11:00'],
            ]),
            'status' => true,
        ]);
    }
}
