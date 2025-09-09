<?php

namespace Database\Factories;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Staff> */
class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'staff']),
            'display_name' => fake()->firstName(),
            'bio' => fake()->sentence(),
            'active' => true,
        ];
    }
}

