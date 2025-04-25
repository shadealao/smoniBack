<?php

namespace Database\Factories;

use App\Models\MeetingPoint;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Availability>
 */
class AvailabilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $date = $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d');
        $dayOfWeek = strtolower(Carbon::parse($date)->locale('fr')->dayName);
        $startTime = $this->faker->time('H:i', '12:00');
        $endTime = Carbon::parse($startTime)->addHours(2)->format('H:i');

        return [
            'instructor_id' => $instructor->id,
            'meeting_point_id' => MeetingPoint::factory()->create(['instructor_id' => $instructor->id])->id,
            'vehicle_id' => Vehicle::factory()->create(['instructor_id' => $instructor->id])->id,
            'day_of_week' => $dayOfWeek,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }
}
