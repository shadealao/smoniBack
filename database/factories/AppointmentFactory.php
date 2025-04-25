<?php

namespace Database\Factories;

use App\Models\Availability;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $availability = Availability::factory()->create(['instructor_id' => $instructor->id]);
        $vehicle = Vehicle::factory()->create(['instructor_id' => $instructor->id]);

        return [
            'learner_id' => $learner->id,
            'instructor_id' => $instructor->id,
            'availability_id' => $availability->id,
            'vehicle_id' => $vehicle->id,
            'date' => $availability->date,
            'start_time' => $availability->start_time,
            'end_time' => $availability->end_time,
            'duration' => \Carbon\Carbon::parse($availability->end_time)->diffInMinutes($availability->start_time),
            'status' => 'scheduled',
            'cancellation_reason' => null,
            'price' => $this->faker->randomFloat(2, 20, 100),
            'lesson_notes' => null,
            'presence_student' => false,
            'presence_monitor' => false,
            'finished' => false,
            'tag' => $this->faker->word,
        ];
    }
}
