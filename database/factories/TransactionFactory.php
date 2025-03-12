<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkIn = $this->faker->dateTimeThisMonth();

        return [
            //
            'check_in' => $checkIn,
            'check_out' => Carbon::createFromDate($checkIn)->addDays($this->faker->numberBetween(1, 4)),
            'status' => $this->faker->randomElement(['pending', 'success', 'cancel']),
        ];
    }
}