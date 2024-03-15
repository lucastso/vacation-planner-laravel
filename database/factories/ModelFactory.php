<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ModelFactory extends Factory
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
        ];
    }

    public function holiday()
{
    return $this->state(function (array $attributes) {
        return [
            "title" => $this->faker->sentence,
            "description" => $this->faker->paragraph,
            "date" => $this->faker->date,
            "location" => $this->faker->city,
            "participants" => $this->faker->randomDigit(),
        ];
    });
}
}
