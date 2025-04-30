<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LearningHistory>
 */
class LearningHistoryFactory extends Factory
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
            'appointment_id' => Appointment::factory(),
            'duration' => $this->faker->numberBetween(30, 120),
            'intervention_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'invoiced' => $this->faker->boolean(50),
            'status' => $this->faker->boolean(80),
        ];
    }
}
