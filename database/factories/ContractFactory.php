<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subscription_id' => Subscription::factory(),
            'student_id' => User::factory()->state(['role' => 'learner']),
            'file_original' => 'contracts/original_' . $this->faker->uuid . '.pdf',
            'file_signed' => 'contracts/signed_' . $this->faker->uuid . '.pdf',
            'tag' => $this->faker->randomElement(['initial', 'renewal', 'amendment']),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
