<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\JsonResponse;

class TeamStatsController extends Controller
{
    public function stats(Team $team): JsonResponse
    {
        $games = Game::with(['homeTeam', 'awayTeam'])
            ->where('status', 'finished')
            ->where(fn ($q) => $q->where('home_team_id', $team->id)->orWhere('away_team_id', $team->id))
            ->orderByDesc('scheduled_at')
            ->get();

        $wins = $draws = $losses = $goalsFor = $goalsAgainst = 0;

        foreach ($games as $g) {
            $isHome = $g->home_team_id === $team->id;
            $myGoals  = $isHome ? $g->home_score : $g->away_score;
            $oppGoals = $isHome ? $g->away_score : $g->home_score;

            $goalsFor     += $myGoals;
            $goalsAgainst += $oppGoals;

            if ($myGoals > $oppGoals)      $wins++;
            elseif ($myGoals === $oppGoals) $draws++;
            else                           $losses++;
        }

        $recent = $games->take(5)->map(fn ($g) => [
            'id'           => $g->id,
            'home_team_id' => $g->home_team_id,
            'away_team_id' => $g->away_team_id,
            'home_name'    => $g->homeTeam->name,
            'away_name'    => $g->awayTeam->name,
            'home_score'   => $g->home_score,
            'away_score'   => $g->away_score,
        ]);

        return response()->json([
            'team' => [
                'id'         => $team->id,
                'name'       => $team->name,
                'code'       => $team->code,
                'group_name' => $team->group_name ?? null,
            ],
            'stats' => [
                'played'        => $games->count(),
                'wins'          => $wins,
                'draws'         => $draws,
                'losses'        => $losses,
                'goals_for'     => $goalsFor,
                'goals_against' => $goalsAgainst,
            ],
            'recent_matches' => $recent,
        ]);
    }
}
