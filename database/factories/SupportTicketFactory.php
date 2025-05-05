<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupportTicket>
 */
class SupportTicketFactory extends Factory
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
            'subject' => $this->faker->sentence(5),
            'message' => $this->faker->paragraph(3),
            'status' => $this->faker->randomElement(['open', 'in_progress', 'resolved']),
            'assigned_to' => $this->faker->boolean(50) ? User::factory()->state(['role' => 'admin']) : null,
            'resolved_at' => $this->faker->boolean(30) ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
        ];
    }
}
