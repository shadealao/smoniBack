<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MeetingPoint>
 */
class MeetingPointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'instructor_id' => User::factory()->create(['role' => 'instructor'])->id,
            'label' => $this->faker->sentence(3),
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'postal_code' => $this->faker->postcode,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }
}
