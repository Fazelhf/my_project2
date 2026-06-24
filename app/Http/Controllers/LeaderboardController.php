<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function index(): View
    {
        $users = User::orderByDesc('total_score')
            ->orderByDesc('id')
            ->get();

        return view('user.leaderboard', compact('users'));
    }
}
