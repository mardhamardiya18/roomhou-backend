<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'title' => ucwords($this->faker->words(3, true)),
            'description' => $this->faker->paragraph(5),
            'address' => $this->faker->address,
            'sqft' => $this->faker->randomNumber(2, true),
            'wifi_speed' => $this->faker->randomNumber(2, true),
            'max_person' => $this->faker->numberBetween(1, 5),
            'price_per_day' => $this->faker->numberBetween(1, 10),
            'full_support_available' => $this->faker->boolean,
            'gym_area_available' => $this->faker->boolean,
            'mini_cafe_available' => $this->faker->boolean,
            'cinema_available' => $this->faker->boolean,
            'attachments' => [],
        ];
    }
}