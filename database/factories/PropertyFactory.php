<?php

namespace Database\Factories;

use App\Models\Entity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'entity_id' => Entity::inRandomOrder()->first()->id,
            'nickname' => $this->faker->name,
            'purchase_value' => $this->faker->numberBetween(100000, 1000000),
            'current_value' => $this->faker->numberBetween(100000, 1000000),
        ];
    }
}
