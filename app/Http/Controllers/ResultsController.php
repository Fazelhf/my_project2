<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Prediction;
use Illuminate\Http\Request;

class ResultsController extends Controller
{
    public function index()
    {
        $games = Game::with(['homeTeam', 'awayTeam', 'predictions.user'])
            ->where('status', 'finished')
            ->orderByDesc('scheduled_at')
            ->paginate(20);

        return view('user.results', compact('games'));
    }
}
