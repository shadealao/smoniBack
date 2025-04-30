<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Withdraw>
 */
class WithdrawFactory extends Factory
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
            'ammount' => $this->faker->numberBetween(100, 10000), // Amount in cents (e.g., 100 = 1.00 EUR)
            'duration' => $this->faker->numberBetween(1, 30), // Duration in days
            'payed' => $this->faker->boolean(20), // 20% chance of being paid
            'currency' => 'EUR',
            'invoice_code' => $this->faker->uuid,
            'invoice_file' => 'invoices/' . $this->faker->uuid . '.pdf',
        ];
    }
}
