<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankAccount>
 */
class BankAccountFactory extends Factory
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
            'iban' => 'FR76' . $this->faker->numerify('################'),
            'bic' => $this->faker->swiftBicNumber,
            'bank_name' => $this->faker->company . ' Bank',
            'status' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }
}
