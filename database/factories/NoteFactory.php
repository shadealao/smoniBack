<?php

namespace Database\Factories;

use App\Models\ModuleStep;
use App\Models\TrainingModule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'monitor_id' => User::factory()->state(['role' => 'instructor']),
            'student_id' => User::factory()->state(['role' => 'learner']),
            'module_id' => TrainingModule::factory(),
            'module_step_id' => ModuleStep::factory(),
            'comment' => $this->faker->paragraph,
            'date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
