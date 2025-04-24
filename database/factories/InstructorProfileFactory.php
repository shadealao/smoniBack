<?php

namespace Database\Factories;

use App\Models\InstructorProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InstructorProfile>
 */
class InstructorProfileFactory extends Factory
{
    protected $model = InstructorProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'postal_code' => $this->faker->postcode,
            'bio' => $this->faker->paragraph,
            'certification_issue_date' => $this->faker->date('Y-m-d'),
            'specialty' => $this->faker->randomElement(['Driving', 'Motorcycle', 'Heavy Vehicle']),
            'certification_number' => $this->faker->numberBetween(100000, 999999),
        ];

    }
}
