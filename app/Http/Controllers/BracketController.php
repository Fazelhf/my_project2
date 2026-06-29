<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use Illuminate\View\View;

class BracketController extends Controller
{
    public function index(): View
    {
        $knockoutGames = Game::with(['homeTeam', 'awayTeam', 'winnerTeam'])
            ->whereIn('stage', Game::KNOCKOUT_STAGES)
            ->orderBy('scheduled_at')
            ->get()
            ->groupBy('stage');

        $teams = Team::orderBy('name')->get();

        return view('user.bracket', compact('knockoutGames', 'teams'));
    }
}
