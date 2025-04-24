<?php

namespace Database\Factories;

use App\Models\LearnerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LearnerProfile>
 */
class LearnerProfileFactory extends Factory
{
    protected $model = LearnerProfile::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'birthdate' => $this->faker->date('Y-m-d', '2000-01-01'),
            'address' => $this->faker->address,
            'postal_code' => $this->faker->postcode,
            'cin_number' => $this->faker->numerify('CIN######'),
            'cin_issue_date' => $this->faker->date('Y-m-d'),
            'cin_issue_place' => $this->faker->city,
            'permit_number' => $this->faker->numerify('PERMIT######'),
            'permit_issue_date' => $this->faker->date('Y-m-d'),
            'permit_category' => $this->faker->randomElement(['A', 'B', 'C']),
        ];
    }
}
