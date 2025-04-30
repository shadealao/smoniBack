<?php

namespace Database\Factories;

use App\Models\ModuleStep;
use App\Models\TrainingModule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class LearnerProgresFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'learner_id' => User::factory()->state(['role' => 'learner']),
            'module_id' => TrainingModule::factory(),
            'current_step_id' => ModuleStep::factory(),
            'status' => $this->faker->randomElement(['not_started', 'in_progress', 'completed']),
            'started_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'completed_at' => $this->faker->boolean(30) ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
            'instructor_notes' => $this->faker->paragraph,
        ];
    }
}
