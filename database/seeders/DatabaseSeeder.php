<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,
            UserSeeder::class,
            VehicleSeeder::class,
            ModuleSeeder::class,
            BadgeSeeder::class,
            
            // AvailabilityRepeatedSeeder::class,
            // MeetingPointSeeder::class,
            // AvailabilitySeeder::class,
            // AppointmentSeeder::class,
        ]);
    }
}
