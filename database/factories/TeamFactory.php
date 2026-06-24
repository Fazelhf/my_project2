<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'       => fake()->country(),
            'name_fa'    => fake()->word(),
            'code'       => strtoupper(fake()->unique()->lexify('???')),
            'group_name' => fake()->randomElement(['A','B','C','D','E','F','G','H']),
            'flag_url'   => null,
        ];
    }
}
