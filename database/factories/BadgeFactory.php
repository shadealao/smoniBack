<?php

namespace Database\Factories;

use App\Models\TrainingModule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Badge>
 */
class BadgeFactory extends Factory
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
            'awarded_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'validation_instructor_id' => User::factory()->state(['role' => 'instructor']),
            'certification_url' => $this->faker->url,
        ];
    }
}
