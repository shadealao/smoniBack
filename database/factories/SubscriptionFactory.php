<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start_date = $this->faker->dateTimeBetween('-1 month', 'now');
        return [
            'learner_id' => User::factory()->state(['role' => 'learner']),
            'plan_id' => Service::factory(),
            'start_date' => $start_date,
            'end_date' => $this->faker->dateTimeBetween($start_date, '+1 year'),
            'type_service' => $this->faker->randomElement(['on_site', 'online', 'hybrid']),
            'status' => $this->faker->randomElement(['active', 'expired', 'cancelled']),
            'auto_renewal' => $this->faker->boolean(50),
            'payment_id' => Payment::factory(),
        ];
    }
}
