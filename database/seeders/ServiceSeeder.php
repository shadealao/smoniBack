<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('category_services')->insert([
            ['label' => 'Permis B'],
            ['label' => 'Code de la route'],
        ]);

        DB::table('sub_category_services')->insert([
            ['label' => 'Formation accélérée'],
            ['label' => 'Conduite accompagnée'],
        ]);

        DB::table('services')->insert([
            [
                'category_service_id' => 1,
                'sub_category_service_id' => 1,
                'title' => 'Pack Permis B Express',
                'price' => 1200
            ],
            [
                'category_service_id' => 2,
                'sub_category_service_id' => 2,
                'title' => 'Code en ligne',
                'price' => 150
            ],
        ]);

        DB::table('service_items')->insert([
            ['service_id' => 1, 'label' => '20 heures de conduite', 'status' => true],
            ['service_id' => 1, 'label' => 'Accès plateforme en ligne', 'status' => true],
        ]);
    }
}
