<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Prediction;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    private function regularUser(): User
    {
        return User::factory()->create(['is_admin' => false]);
    }

    // ─── Access control ───────────────────────────────────────────────────────

    public function test_guest_cannot_access_admin(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin(): void
    {
        $this->actingAs($this->regularUser())
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_access_dashboard(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    // ─── Submit game result ───────────────────────────────────────────────────

    public function test_admin_can_submit_game_result(): void
    {
        $admin = $this->admin();
        $game  = Game::factory()->create(['status' => 'upcoming']);

        $this->actingAs($admin)
            ->post(route('admin.games.result', $game), [
                'home_score' => 2,
                'away_score' => 1,
            ])
            ->assertRedirect();

        $game->refresh();
        $this->assertSame('finished', $game->status);
        $this->assertSame(2, $game->home_score);
        $this->assertSame(1, $game->away_score);
    }

    public function test_submit_result_triggers_scoring(): void
    {
        $admin = $this->admin();
        $game  = Game::factory()->create(['status' => 'upcoming', 'scheduled_at' => now()->subDay()]);
        $user  = User::factory()->create(['total_score' => 0]);

        Prediction::factory()->create([
            'user_id'    => $user->id,
            'game_id'    => $game->id,
            'home_score' => 3,
            'away_score' => 0,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.games.result', $game), [
                'home_score' => 3,
                'away_score' => 0,
            ]);

        $this->assertSame(10, $user->fresh()->total_score);
    }

    public function test_regular_user_cannot_submit_result(): void
    {
        $game = Game::factory()->create(['status' => 'upcoming']);

        $this->actingAs($this->regularUser())
            ->post(route('admin.games.result', $game), [
                'home_score' => 1,
                'away_score' => 0,
            ])
            ->assertForbidden();
    }

    // ─── Recalculate scores ───────────────────────────────────────────────────

    public function test_admin_can_recalculate_all_scores(): void
    {
        $admin = $this->admin();
        $game  = Game::factory()->finished(2, 0)->create();
        $user  = User::factory()->create(['total_score' => 99]);

        Prediction::factory()->create([
            'user_id'       => $user->id,
            'game_id'       => $game->id,
            'home_score'    => 2,
            'away_score'    => 0,
            'points_earned' => 99,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.recalculate'))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame(10, $user->fresh()->total_score);
    }

    // ─── Teams CRUD ───────────────────────────────────────────────────────────

    public function test_admin_can_create_team(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.teams.store'), [
                'name'       => 'Brazil',
                'name_fa'    => 'برزیل',
                'code'       => 'BRA',
                'group_name' => 'A',
            ])
            ->assertRedirect(route('admin.teams.index'));

        $this->assertDatabaseHas('teams', ['code' => 'BRA']);
    }

    public function test_admin_can_delete_team(): void
    {
        $team = Team::factory()->create();

        $this->actingAs($this->admin())
            ->delete(route('admin.teams.destroy', $team))
            ->assertRedirect();

        $this->assertDatabaseMissing('teams', ['id' => $team->id]);
    }

    // ─── Games CRUD ───────────────────────────────────────────────────────────

    public function test_admin_can_create_game(): void
    {
        $home = Team::factory()->create();
        $away = Team::factory()->create();

        $this->actingAs($this->admin())
            ->post(route('admin.games.store'), [
                'home_team_id' => $home->id,
                'away_team_id' => $away->id,
                'stage'        => 'group',
                'group_name'   => 'A',
                'scheduled_at' => now()->addDays(10)->format('Y-m-d\TH:i'),
                'match_number' => 1,
            ])
            ->assertRedirect(route('admin.games.index'));

        $this->assertDatabaseHas('games', [
            'home_team_id' => $home->id,
            'away_team_id' => $away->id,
        ]);
    }

    public function test_admin_can_delete_upcoming_game(): void
    {
        $game = Game::factory()->create(['status' => 'upcoming']);

        $this->actingAs($this->admin())
            ->delete(route('admin.games.destroy', $game))
            ->assertRedirect();

        $this->assertDatabaseMissing('games', ['id' => $game->id]);
    }
}
