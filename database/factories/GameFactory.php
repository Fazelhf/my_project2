<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'home_team_id'    => Team::factory(),
            'away_team_id'    => Team::factory(),
            'stage'           => 'group',
            'group_name'      => 'A',
            'match_number'    => fake()->unique()->numberBetween(1, 64),
            'scheduled_at'    => now()->addDays(fake()->numberBetween(1, 30)),
            'venue'           => fake()->city(),
            'status'          => 'upcoming',
            'is_disciplinary' => false,
        ];
    }

    public function finished(int $home = 2, int $away = 1): static
    {
        return $this->state([
            'status'     => 'finished',
            'home_score' => $home,
            'away_score' => $away,
        ]);
    }

    public function disciplinary(): static
    {
        return $this->state(['is_disciplinary' => true]);
    }

    public function knockout(): static
    {
        return $this->state(['stage' => 'quarter_final', 'group_name' => null]);
    }
}
