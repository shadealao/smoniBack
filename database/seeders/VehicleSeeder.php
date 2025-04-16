<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vehicles')->insert([
            [
                'instructor_id' => 2,
                'brand' => 'Renault',
                'model' => 'Clio',
                'year' => 2021,
                'fuel_type' => 'essence',
                'gearbox_type' => 'manual',
                'plate_number' => 'AB-123-CD',
                'status' => 'available',
                'color' => 'gris',
                'insurance_expiry' => now()->addYear(),
                'technical_inspection_date' => now()->subMonths(2),
                'photo_url' => 'https://example.com/clio.jpg'
            ],
        ]);
    }
}
