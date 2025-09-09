<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Product> */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->words(2, true);
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1,9999),
            'description' => fake()->sentence(10),
            'price' => fake()->randomFloat(2, 1500, 9000),
            'image_url' => 'https://picsum.photos/seed/'.fake()->uuid().'/600/400',
            'stock' => fake()->randomElement([null, 5, 10, 20]),
            'is_made_to_order' => fake()->boolean(30),
            'lead_time_hours' => fake()->randomElement([24, 48, 72]),
            'active' => true,
        ];
    }
}

