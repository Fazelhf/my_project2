<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Prediction;
use App\Models\Team;
use App\Models\User;
use App\Services\PredictionScoringService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PredictionScoringTest extends TestCase
{
    use RefreshDatabase;

    private PredictionScoringService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PredictionScoringService::class);
    }

    // ─── calculatePoints unit-level tests via Prediction model ───────────────

    public function test_exact_score_gives_10_points(): void
    {
        $prediction = Prediction::factory()->make(['home_score' => 2, 'away_score' => 1]);
        $this->assertSame(10, $prediction->calculatePoints(2, 1));
    }

    public function test_exact_draw_gives_10_points(): void
    {
        $prediction = Prediction::factory()->make(['home_score' => 1, 'away_score' => 1]);
        $this->assertSame(10, $prediction->calculatePoints(1, 1));
    }

    public function test_same_goal_difference_gives_7_points(): void
    {
        // بازی 3-1 شد (فرق=2)، پیش‌بینی 2-0 (فرق=2)
        $prediction = Prediction::factory()->make(['home_score' => 2, 'away_score' => 0]);
        $this->assertSame(7, $prediction->calculatePoints(3, 1));
    }

    public function test_draw_with_different_score_gives_7_points(): void
    {
        // بازی 2-2 شد، پیش‌بینی 1-1 بود
        $prediction = Prediction::factory()->make(['home_score' => 1, 'away_score' => 1]);
        $this->assertSame(7, $prediction->calculatePoints(2, 2));
    }

    public function test_correct_winner_wrong_difference_gives_5_points(): void
    {
        // بازی 1-0 شد، پیش‌بینی 2-0 بود
        $prediction = Prediction::factory()->make(['home_score' => 2, 'away_score' => 0]);
        $this->assertSame(5, $prediction->calculatePoints(1, 0));
    }

    public function test_wrong_outcome_gives_2_points(): void
    {
        // بازی 1-0 شد (خانگی برنده)، پیش‌بینی 0-1 (مهمان برنده)
        $prediction = Prediction::factory()->make(['home_score' => 0, 'away_score' => 1]);
        $this->assertSame(2, $prediction->calculatePoints(1, 0));
    }

    public function test_predicted_draw_but_home_won_gives_2_points(): void
    {
        $prediction = Prediction::factory()->make(['home_score' => 1, 'away_score' => 1]);
        $this->assertSame(2, $prediction->calculatePoints(2, 0));
    }

    public function test_predicted_home_win_but_draw_gives_2_points(): void
    {
        $prediction = Prediction::factory()->make(['home_score' => 2, 'away_score' => 0]);
        $this->assertSame(2, $prediction->calculatePoints(1, 1));
    }

    // ─── processGame ──────────────────────────────────────────────────────────

    public function test_process_game_scores_all_predictions(): void
    {
        $game = Game::factory()->finished(2, 1)->create();
        $user = User::factory()->create();

        Prediction::factory()->create([
            'user_id'    => $user->id,
            'game_id'    => $game->id,
            'home_score' => 2,
            'away_score' => 1, // exact
        ]);

        $result = $this->service->processGame($game);

        $this->assertSame(1, $result['updated']);
        $this->assertFalse($result['skipped_disciplinary']);
        $this->assertDatabaseHas('predictions', [
            'user_id'       => $user->id,
            'game_id'       => $game->id,
            'points_earned' => 10,
        ]);
    }

    public function test_process_game_skips_disciplinary(): void
    {
        $game = Game::factory()->finished(2, 1)->disciplinary()->create();
        $user = User::factory()->create();

        Prediction::factory()->create([
            'user_id'    => $user->id,
            'game_id'    => $game->id,
            'home_score' => 2,
            'away_score' => 1,
        ]);

        $result = $this->service->processGame($game);

        $this->assertTrue($result['skipped_disciplinary']);
        $this->assertSame(0, $result['updated']);
        $this->assertDatabaseHas('predictions', [
            'game_id'       => $game->id,
            'points_earned' => 0,
        ]);
    }

    public function test_process_game_updates_user_total_score(): void
    {
        $game = Game::factory()->finished(3, 0)->create();
        $user = User::factory()->create(['total_score' => 0]);

        Prediction::factory()->create([
            'user_id'    => $user->id,
            'game_id'    => $game->id,
            'home_score' => 3,
            'away_score' => 0, // exact → 10 pts
        ]);

        $this->service->processGame($game);

        $this->assertSame(10, $user->fresh()->total_score);
    }

    public function test_process_game_scores_multiple_predictions_correctly(): void
    {
        $game   = Game::factory()->finished(2, 0)->create();
        $users  = User::factory()->count(4)->create();

        $predictions = [
            ['home' => 2, 'away' => 0, 'expected' => 10], // exact
            ['home' => 3, 'away' => 1, 'expected' => 7],  // same diff
            ['home' => 1, 'away' => 0, 'expected' => 5],  // right winner
            ['home' => 0, 'away' => 1, 'expected' => 2],  // wrong
        ];

        foreach ($predictions as $i => $p) {
            Prediction::factory()->create([
                'user_id'    => $users[$i]->id,
                'game_id'    => $game->id,
                'home_score' => $p['home'],
                'away_score' => $p['away'],
            ]);
        }

        $this->service->processGame($game);

        foreach ($predictions as $i => $p) {
            $this->assertDatabaseHas('predictions', [
                'user_id'       => $users[$i]->id,
                'game_id'       => $game->id,
                'points_earned' => $p['expected'],
            ]);
        }
    }

    // ─── recalculateAll ───────────────────────────────────────────────────────

    public function test_recalculate_all_resets_and_recomputes(): void
    {
        $game = Game::factory()->finished(1, 0)->create();
        $user = User::factory()->create(['total_score' => 99]);

        $pred = Prediction::factory()->create([
            'user_id'       => $user->id,
            'game_id'       => $game->id,
            'home_score'    => 1,
            'away_score'    => 0,
            'points_earned' => 99, // stale value
        ]);

        $result = $this->service->recalculateAll();

        $this->assertSame(1, $result['games_processed']);
        $this->assertSame(1, $result['predictions_updated']);
        $this->assertSame(10, $pred->fresh()->points_earned);
        $this->assertSame(10, $user->fresh()->total_score);
    }

    public function test_recalculate_all_ignores_upcoming_games(): void
    {
        $game = Game::factory()->create(['status' => 'upcoming']);
        $user = User::factory()->create();

        Prediction::factory()->create([
            'user_id' => $user->id,
            'game_id' => $game->id,
        ]);

        $result = $this->service->recalculateAll();

        $this->assertSame(0, $result['games_processed']);
        $this->assertNull(Prediction::first()->points_earned);
    }

    // ─── getGameStats ─────────────────────────────────────────────────────────

    public function test_get_game_stats_returns_correct_breakdown(): void
    {
        $game  = Game::factory()->finished(2, 1)->create();
        $users = User::factory()->count(3)->create();

        // بازی واقعی: 2-1 (diff=+1, home wins)
        $data = [
            ['home' => 2, 'away' => 1], // 10 — exact
            ['home' => 3, 'away' => 2], // 7  — same diff (+1), not exact
            ['home' => 3, 'away' => 0], // 5  — home wins, diff≠+1
        ];
        foreach ($data as $i => $d) {
            Prediction::factory()->create([
                'user_id' => $users[$i]->id,
                'game_id' => $game->id,
                'home_score' => $d['home'],
                'away_score' => $d['away'],
            ]);
        }

        $this->service->processGame($game);
        $stats = $this->service->getGameStats($game);

        $this->assertSame(3, $stats['total']);
        $this->assertSame(3, $stats['scored']);
        $this->assertSame(0, $stats['pending']);
        $this->assertSame(1, $stats['breakdown'][10]);
        $this->assertSame(1, $stats['breakdown'][7]);
        $this->assertSame(1, $stats['breakdown'][5]);
    }
}
