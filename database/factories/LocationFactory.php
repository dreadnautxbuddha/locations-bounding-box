<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @package Database\Factories
 *
 * @author  Peter Cortez <innov.petercortez@gmail.com>
 */
class LocationFactory extends Factory
{
    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->address(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ];
    }
}
