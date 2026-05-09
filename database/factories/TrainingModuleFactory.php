<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingModule>
 */
class TrainingModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'duration_hours' => $this->faker->numberBetween(1, 20),
            'required_for_license' => $this->faker->boolean(30),
            'display_order' => $this->faker->numberBetween(1, 100),
            'file' => 'modules/' . $this->faker->uuid . '.pdf',
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
