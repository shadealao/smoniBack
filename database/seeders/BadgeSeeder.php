<?php

namespace Database\Seeders;

use App\Models\ModuleStep;
use App\Models\ListBadge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $stepModules = ModuleStep::all();

        foreach ($stepModules as $stepModule) {
            $moduleStep= ListBadge::firstOrCreate(
                [
                    'name' => $stepModule->name,
                ],
                [
                'name' => $stepModule->name,
                'logo' => 'aaa'
            ]);
        }

    }
}
