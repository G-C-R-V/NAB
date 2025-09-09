<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Schedule> */
class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'staff_id' => Staff::factory(),
            'weekday' => fake()->numberBetween(1,5),
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
        ];
    }
}

