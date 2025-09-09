<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Service> */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Corte', 'Color', 'Barba']).' '.fake()->word(),
            'duration_minutes' => fake()->randomElement([30,45,60]),
            'price' => fake()->randomFloat(2, 1000, 10000),
            'active' => true,
        ];
    }
}

