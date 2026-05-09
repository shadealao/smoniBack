<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeetingPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('meeting_points')->insert([
            [
                'instructor_id' => 2,
                'label' => 'Point A',
                'address' => 'Rue de Paris: 75001',
                'city' => 'Paris',
                'longitude' => 2.3522,
                'latitude' => 48.8566,
                'is_active' => true,
            ],
        ]);
    }
}
