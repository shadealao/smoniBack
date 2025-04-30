<?php

namespace Database\Factories;

use App\Models\TrainingModule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModuleStep>
 */
class ModuleStepFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'module_id' => TrainingModule::factory(),
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->paragraph,
            'duration_minutes' => $this->faker->numberBetween(30, 120),
            'step_type' => $this->faker->randomElement(['theory', 'practice', 'assessment']),
            'display_order' => $this->faker->numberBetween(1, 50),
            'required_for_completion' => $this->faker->boolean(50),
        ];
    }
}
