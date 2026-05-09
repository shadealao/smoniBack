<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'currency' => 'EUR',
            'payment_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'payment_method' => $this->faker->randomElement(['credit_card', 'bank_transfer', 'cash']),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded']),
            'invoice_number' => $this->faker->unique()->uuid,
            'related_appointment_id' => null, // Optional
        ];
    }
}
