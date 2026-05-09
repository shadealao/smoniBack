<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamRegistration>
 */
class ExamRegistrationFactory extends Factory
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
            'monitor_id' => User::factory()->state(['role' => 'instructor']),
            'registration_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->randomElement(['registered', 'passed', 'failed', 'absent']),
            'result_score' => $this->faker->boolean(50) ? $this->faker->randomFloat(2, 0, 100) : null,
            'instructor_comments' => $this->faker->boolean(50) ? $this->faker->paragraph : null,
        ];
    }
}
