<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
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
            'brand' => $this->faker->randomElement(['Toyota', 'Honda', 'Ford', 'Volkswagen']),
            'model' => $this->faker->word,
            'year' => $this->faker->year,
            'plate_number' => $this->faker->unique()->regexify('[A-Z]{2}-[0-9]{3}-[A-Z]{2}'),
            'fuel_type' => $this->faker->randomElement(['essence', 'diesel', 'électrique', 'hybride']),
            'insurance_expiry' => $this->faker->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
            'technical_inspection_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'photo_url' => $this->faker->imageUrl(),
            'color' => $this->faker->colorName,
            'gearbox_type' => $this->faker->randomElement(['manual', 'automatic']),
            'status' => $this->faker->randomElement(['available', 'maintenance', 'out_of_service']),
        ];
    }
}
