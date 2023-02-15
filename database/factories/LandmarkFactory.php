<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Landmark>
 */
class LandmarkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'country' => fake()->countryCode(),
            'type' => fake()->word(),
            'name' => fake()->words(2, true),
            'city' => fake()->word(),
            'comment' => fake()->boolean(50) ? fake()->sentence() : null,
        ];
    }
}
