<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\View\View;

class ResultsController extends Controller
{
    public function index(): View
    {
        $games = Game::with(['homeTeam', 'awayTeam', 'predictions.user'])
            ->where('status', 'finished')
            ->orderByDesc('scheduled_at')
            ->paginate(20);

        return view('user.results', compact('games'));
    }
}
