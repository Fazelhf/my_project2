<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Prediction;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PredictionControllerTest extends TestCase
{
    use RefreshDatabase;

    // ─── Games index ──────────────────────────────────────────────────────────

    public function test_games_index_requires_auth(): void
    {
        $this->get(route('games.index'))->assertRedirect(route('login'));
    }

    public function test_games_index_loads_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('games.index'))
            ->assertOk();
    }

    // ─── Store prediction ─────────────────────────────────────────────────────

    public function test_user_can_submit_prediction(): void
    {
        $user = User::factory()->create();
        $game = Game::factory()->create([
            'scheduled_at' => now()->addHour(),
            'status'       => 'upcoming',
        ]);

        $this->actingAs($user)
            ->post(route('games.predict', $game), [
                'home_score' => 2,
                'away_score' => 1,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('predictions', [
            'user_id'    => $user->id,
            'game_id'    => $game->id,
            'home_score' => 2,
            'away_score' => 1,
        ]);
    }

    public function test_user_cannot_predict_locked_game(): void
    {
        $user = User::factory()->create();
        $game = Game::factory()->create([
            'scheduled_at' => now()->subHour(),
            'status'       => 'upcoming',
        ]);

        $this->actingAs($user)
            ->post(route('games.predict', $game), [
                'home_score' => 1,
                'away_score' => 0,
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('predictions', ['game_id' => $game->id]);
    }

    public function test_user_cannot_predict_finished_game(): void
    {
        $user = User::factory()->create();
        $game = Game::factory()->finished(2, 1)->create();

        $this->actingAs($user)
            ->post(route('games.predict', $game), [
                'home_score' => 1,
                'away_score' => 0,
            ])
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_prediction_score_must_be_non_negative(): void
    {
        $user = User::factory()->create();
        $game = Game::factory()->create(['scheduled_at' => now()->addHour()]);

        $this->actingAs($user)
            ->post(route('games.predict', $game), [
                'home_score' => -1,
                'away_score' => 0,
            ])
            ->assertSessionHasErrors('home_score');
    }

    public function test_user_cannot_submit_duplicate_prediction(): void
    {
        $user = User::factory()->create();
        $game = Game::factory()->create(['scheduled_at' => now()->addHour()]);

        Prediction::factory()->create([
            'user_id' => $user->id,
            'game_id' => $game->id,
        ]);

        $this->actingAs($user)
            ->post(route('games.predict', $game), [
                'home_score' => 2,
                'away_score' => 0,
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertSame(1, Prediction::where('game_id', $game->id)->count());
    }

    // ─── Update prediction ────────────────────────────────────────────────────

    public function test_user_can_update_prediction_before_kickoff(): void
    {
        $user = User::factory()->create();
        $game = Game::factory()->create(['scheduled_at' => now()->addHour()]);

        $pred = Prediction::factory()->create([
            'user_id'    => $user->id,
            'game_id'    => $game->id,
            'home_score' => 1,
            'away_score' => 0,
        ]);

        $this->actingAs($user)
            ->put(route('games.predict.update', $game), [
                'home_score' => 3,
                'away_score' => 2,
            ])
            ->assertRedirect();

        $this->assertSame(3, $pred->fresh()->home_score);
        $this->assertSame(2, $pred->fresh()->away_score);
    }

    public function test_user_cannot_update_prediction_after_kickoff(): void
    {
        $user = User::factory()->create();
        $game = Game::factory()->create(['scheduled_at' => now()->subHour()]);

        $pred = Prediction::factory()->create([
            'user_id'    => $user->id,
            'game_id'    => $game->id,
            'home_score' => 1,
            'away_score' => 0,
        ]);

        $this->actingAs($user)
            ->put(route('games.predict.update', $game), [
                'home_score' => 3,
                'away_score' => 2,
            ])
            ->assertSessionHas('error');

        $this->assertSame(1, $pred->fresh()->home_score);
    }

    public function test_user_cannot_update_another_users_prediction(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $game  = Game::factory()->create(['scheduled_at' => now()->addHour()]);

        $pred = Prediction::factory()->create([
            'user_id'    => $user1->id,
            'game_id'    => $game->id,
            'home_score' => 1,
            'away_score' => 0,
        ]);

        $this->actingAs($user2)
            ->put(route('games.predict.update', $game), [
                'home_score' => 3,
                'away_score' => 2,
            ])
            ->assertSessionHas('error');

        // user2 has no prediction, update does nothing harmful
        $this->assertSame(1, $pred->fresh()->home_score);
    }
}
