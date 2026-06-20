<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// User Authentication
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = [
        'user@worldcup.com' => 'user123',
        'ahmad@worldcup.com' => 'ahmad123',
        'sara@worldcup.com' => 'sara123',
        'reza@worldcup.com' => 'reza123'
    ];
    
    if (isset($credentials[$request->email]) && $credentials[$request->email] === $request->password) {
        session([
            'user_logged_in' => true,
            'user_name' => explode('@', $request->email)[0],
            'user_email' => $request->email,
            'user_id' => array_search($request->email, array_keys($credentials)) + 1
        ]);
        return redirect()->route('dashboard');
    }
    
    return back()->withErrors(['email' => 'اطلاعات ورود نادرست است']);
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', function () {
    session()->forget(['user_logged_in', 'user_name', 'user_email', 'user_id']);
    return redirect()->route('home');
})->name('logout');

// User Dashboard
Route::get('/dashboard', function () {
    if (!session('user_logged_in')) {
        return redirect()->route('login');
    }
    
    $stats = [
        'tournament_score' => 42,
        'match_score' => 86,
        'total_score' => 128,
        'rank' => 2,
        'total_users' => 847,
        'predictions_made' => 24,
        'correct_predictions' => 16,
        'accuracy' => 66.7
    ];
    
    $recentMatches = [
        ['team1' => 'آلمان', 'team2' => 'برزیل', 'predicted' => '3-1', 'actual' => '2-1', 'points' => 3, 'status' => 'finished'],
        ['team1' => 'فرانسه', 'team2' => 'آرژانتین', 'predicted' => '2-2', 'actual' => '2-2', 'points' => 5, 'status' => 'finished'],
        ['team1' => 'اسپانیا', 'team2' => 'انگلیس', 'predicted' => '1-0', 'actual' => '1-0', 'points' => 5, 'status' => 'finished'],
        ['team1' => 'پرتغال', 'team2' => 'ایتالیا', 'predicted' => '2-1', 'actual' => null, 'points' => 0, 'status' => 'upcoming'],
        ['team1' => 'هلند', 'team2' => 'بلژیک', 'predicted' => '3-2', 'actual' => null, 'points' => 0, 'status' => 'upcoming'],
    ];
    
    return view('user.dashboard', compact('stats', 'recentMatches'));
})->name('dashboard');

// Tournament Prediction (پیش‌بینی کل جام)
Route::get('/tournament-prediction', function () {
    if (!session('user_logged_in')) {
        return redirect()->route('login');
    }
    
    $groups = [
        'A' => ['قطر', 'اکوادور', 'سنگال', 'هلند'],
        'B' => ['انگلیس', 'ایران', 'ولز', 'آمریکا'],
        'C' => ['آرژانتین', 'عربستان', 'مکزیک', 'لهستان'],
        'D' => ['فرانسه', 'استرالیا', 'دانمارک', 'تونس'],
        'E' => ['اسپانیا', 'کاستاریکا', 'آلمان', 'ژاپن'],
        'F' => ['بلژیک', 'کانادا', 'مراکش', 'کرواسی'],
        'G' => ['برزیل', 'صربستان', 'سوئیس', 'کامرون'],
        'H' => ['پرتغال', 'غنا', 'اروگوئه', 'کره‌جنوبی']
    ];
    
    $userPrediction = [
        'group_winners' => [
            'A' => ['هلند', 'سنگال'],
            'B' => ['انگلیس', 'آمریکا'],
            'C' => ['آرژانتین', 'لهستان'],
            'D' => ['فرانسه', 'دانمارک'],
            'E' => ['اسپانیا', 'آلمان'],
            'F' => ['بلژیک', 'کرواسی'],
            'G' => ['برزیل', 'سوئیس'],
            'H' => ['پرتغال', 'اروگوئه']
        ],
        'round_of_16' => ['هلند', 'انگلیس', 'آرژانتین', 'فرانسه', 'اسپانیا', 'بلژیک', 'برزیل', 'پرتغال'],
        'quarter_finals' => ['انگلیس', 'فرانسه', 'اسپانیا', 'برزیل'],
        'semi_finals' => ['فرانسه', 'برزیل'],
        'final' => ['فرانسه', 'برزیل'],
        'champion' => 'برزیل',
        'is_locked' => true
    ];
    
    return view('user.tournament-prediction', compact('groups', 'userPrediction'));
})->name('tournament.prediction');

// Match Predictions (پیش‌بینی بازی‌ها)
Route::get('/matches', function () {
    if (!session('user_logged_in')) {
        return redirect()->route('login');
    }
    
    $matches = [
        ['id' => 1, 'team1' => 'قطر', 'team2' => 'اکوادور', 'date' => '2024-11-20', 'time' => '19:00', 'stage' => 'مرحله گروهی', 'group' => 'A', 'status' => 'finished', 'score1' => 0, 'score2' => 2, 'predicted' => true, 'pred_score1' => 1, 'pred_score2' => 1, 'points_earned' => 0],
        ['id' => 2, 'team1' => 'انگلیس', 'team2' => 'ایران', 'date' => '2024-11-21', 'time' => '16:00', 'stage' => 'مرحله گروهی', 'group' => 'B', 'status' => 'finished', 'score1' => 6, 'score2' => 2, 'predicted' => true, 'pred_score1' => 3, 'pred_score2' => 0, 'points_earned' => 3],
        ['id' => 3, 'team1' => 'سنگال', 'team2' => 'هلند', 'date' => '2024-11-21', 'time' => '19:00', 'stage' => 'مرحله گروهی', 'group' => 'A', 'status' => 'finished', 'score1' => 0, 'score2' => 2, 'predicted' => true, 'pred_score1' => 0, 'pred_score2' => 2, 'points_earned' => 5],
        ['id' => 4, 'team1' => 'آمریکا', 'team2' => 'ولز', 'date' => '2024-11-21', 'time' => '22:00', 'stage' => 'مرحله گروهی', 'group' => 'B', 'status' => 'finished', 'score1' => 1, 'score2' => 1, 'predicted' => true, 'pred_score1' => 1, 'pred_score2' => 1, 'points_earned' => 5],
        ['id' => 5, 'team1' => 'آرژانتین', 'team2' => 'عربستان', 'date' => '2024-11-22', 'time' => '13:00', 'stage' => 'مرحله گروهی', 'group' => 'C', 'status' => 'finished', 'score1' => 1, 'score2' => 2, 'predicted' => true, 'pred_score1' => 3, 'pred_score2' => 0, 'points_earned' => 0],
        ['id' => 6, 'team1' => 'دانمارک', 'team2' => 'تونس', 'date' => '2024-11-22', 'time' => '16:00', 'stage' => 'مرحله گروهی', 'group' => 'D', 'status' => 'live', 'score1' => 1, 'score2' => 0, 'predicted' => true, 'pred_score1' => 2, 'pred_score2' => 0, 'points_earned' => 0],
        ['id' => 7, 'team1' => 'مکزیک', 'team2' => 'لهستان', 'date' => '2024-11-22', 'time' => '19:00', 'stage' => 'مرحله گروهی', 'group' => 'C', 'status' => 'upcoming', 'score1' => null, 'score2' => null, 'predicted' => true, 'pred_score1' => 1, 'pred_score2' => 2, 'points_earned' => 0],
        ['id' => 8, 'team1' => 'فرانسه', 'team2' => 'استرالیا', 'date' => '2024-11-22', 'time' => '22:00', 'stage' => 'مرحله گروهی', 'group' => 'D', 'status' => 'upcoming', 'score1' => null, 'score2' => null, 'predicted' => false, 'pred_score1' => null, 'pred_score2' => null, 'points_earned' => 0],
        ['id' => 9, 'team1' => 'مراکش', 'team2' => 'کرواسی', 'date' => '2024-11-23', 'time' => '13:00', 'stage' => 'مرحله گروهی', 'group' => 'F', 'status' => 'upcoming', 'score1' => null, 'score2' => null, 'predicted' => false, 'pred_score1' => null, 'pred_score2' => null, 'points_earned' => 0],
        ['id' => 10, 'team1' => 'آلمان', 'team2' => 'ژاپن', 'date' => '2024-11-23', 'time' => '16:00', 'stage' => 'مرحله گروهی', 'group' => 'E', 'status' => 'upcoming', 'score1' => null, 'score2' => null, 'predicted' => false, 'pred_score1' => null, 'pred_score2' => null, 'points_earned' => 0],
        ['id' => 11, 'team1' => 'اسپانیا', 'team2' => 'کاستاریکا', 'date' => '2024-11-23', 'time' => '19:00', 'stage' => 'مرحله گروهی', 'group' => 'E', 'status' => 'upcoming', 'score1' => null, 'score2' => null, 'predicted' => false, 'pred_score1' => null, 'pred_score2' => null, 'points_earned' => 0],
        ['id' => 12, 'team1' => 'بلژیک', 'team2' => 'کانادا', 'date' => '2024-11-23', 'time' => '22:00', 'stage' => 'مرحله گروهی', 'group' => 'F', 'status' => 'upcoming', 'score1' => null, 'score2' => null, 'predicted' => false, 'pred_score1' => null, 'pred_score2' => null, 'points_earned' => 0],
    ];
    
    return view('user.matches', compact('matches'));
})->name('matches');

// Single Match Prediction
Route::get('/matches/{id}/predict', function ($id) {
    if (!session('user_logged_in')) {
        return redirect()->route('login');
    }
    
    $match = [
        'id' => $id,
        'team1' => 'آلمان',
        'team2' => 'ژاپن',
        'date' => '2024-11-23',
        'time' => '16:00',
        'stage' => 'مرحله گروهی',
        'group' => 'E',
        'status' => 'upcoming',
        'venue' => 'استادیوم خلیفه',
        'predicted' => false,
        'pred_score1' => null,
        'pred_score2' => null
    ];
    
    return view('user.predict-match', compact('match'));
})->name('match.predict');

// Leaderboard
Route::get('/leaderboard', function () {
    $leaderboard = [
        ['rank' => 1, 'name' => 'علی رضایی', 'tournament_score' => 67, 'match_score' => 142, 'total_score' => 209, 'predictions' => 32, 'accuracy' => 78.1, 'exact_scores' => 12, 'change' => 0],
        ['rank' => 2, 'name' => session('user_name', 'شما'), 'tournament_score' => 42, 'match_score' => 86, 'total_score' => 128, 'predictions' => 24, 'accuracy' => 66.7, 'exact_scores' => 8, 'change' => 1],
        ['rank' => 3, 'name' => 'سارا احمدی', 'tournament_score' => 51, 'match_score' => 73, 'total_score' => 124, 'predictions' => 28, 'accuracy' => 64.3, 'exact_scores' => 6, 'change' => -1],
        ['rank' => 4, 'name' => 'رضا کریمی', 'tournament_score' => 38, 'match_score' => 79, 'total_score' => 117, 'predictions' => 26, 'accuracy' => 69.2, 'exact_scores' => 9, 'change' => 2],
        ['rank' => 5, 'name' => 'مریم حسینی', 'tournament_score' => 45, 'match_score' => 68, 'total_score' => 113, 'predictions' => 25, 'accuracy' => 60.0, 'exact_scores' => 5, 'change' => -1],
        ['rank' => 6, 'name' => 'امیر محمدی', 'tournament_score' => 33, 'match_score' => 76, 'total_score' => 109, 'predictions' => 30, 'accuracy' => 63.3, 'exact_scores' => 7, 'change' => 0],
        ['rank' => 7, 'name' => 'فاطمه نوری', 'tournament_score' => 41, 'match_score' => 65, 'total_score' => 106, 'predictions' => 27, 'accuracy' => 59.3, 'exact_scores' => 4, 'change' => 1],
        ['rank' => 8, 'name' => 'حسین عباسی', 'tournament_score' => 29, 'match_score' => 71, 'total_score' => 100, 'predictions' => 29, 'accuracy' => 62.1, 'exact_scores' => 6, 'change' => -2],
        ['rank' => 9, 'name' => 'زهرا قاسمی', 'tournament_score' => 36, 'match_score' => 58, 'total_score' => 94, 'predictions' => 22, 'accuracy' => 54.5, 'exact_scores' => 3, 'change' => 0],
        ['rank' => 10, 'name' => 'محمد رستمی', 'tournament_score' => 27, 'match_score' => 64, 'total_score' => 91, 'predictions' => 31, 'accuracy' => 58.1, 'exact_scores' => 5, 'change' => 1],
    ];
    
    $topPredictors = [
        ['title' => 'بیشترین نتیجه دقیق', 'name' => 'علی رضایی', 'value' => 12, 'icon' => 'target'],
        ['title' => 'بالاترین درصد موفقیت', 'name' => 'علی رضایی', 'value' => '78.1%', 'icon' => 'chart'],
        ['title' => 'بیشترین پیش‌بینی', 'name' => 'محمد رستمی', 'value' => 31, 'icon' => 'users'],
    ];
    
    return view('leaderboard', compact('leaderboard', 'topPredictors'));
})->name('leaderboard');

// Admin Authentication
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

Route::post('/admin/login', function (Request $request) {
    $credentials = [
        'admin@worldcup.com' => 'admin123',
        'manager@worldcup.com' => 'manager123'
    ];
    
    if (isset($credentials[$request->email]) && $credentials[$request->email] === $request->password) {
        session([
            'admin_logged_in' => true,
            'admin_user' => explode('@', $request->email)[0],
            'admin_email' => $request->email
        ]);
        return redirect()->route('admin.dashboard');
    }
    
    return back()->withErrors(['email' => 'اطلاعات ورود نادرست است']);
});

Route::post('/admin/logout', function () {
    session()->forget(['admin_logged_in', 'admin_user', 'admin_email']);
    return redirect()->route('admin.login');
})->name('admin.logout');

// Admin Dashboard
Route::get('/admin/dashboard', function () {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    
    $stats = [
        'total_users' => 847,
        'total_matches' => 64,
        'completed_matches' => 12,
        'total_predictions' => 6842,
        'active_users_today' => 432,
        'avg_score' => 87.4,
        'matches_today' => 4,
        'upcoming_matches' => 52
    ];
    
    $recentActivity = [
        ['user' => 'علی رضایی', 'action' => 'پیش‌بینی بازی', 'match' => 'آلمان vs ژاپن', 'time' => '2 دقیقه پیش'],
        ['user' => 'سارا احمدی', 'action' => 'پیش‌بینی بازی', 'match' => 'اسپانیا vs کاستاریکا', 'time' => '5 دقیقه پیش'],
        ['user' => 'رضا کریمی', 'action' => 'ثبت نام', 'match' => '-', 'time' => '12 دقیقه پیش'],
        ['user' => 'مریم حسینی', 'action' => 'پیش‌بینی بازی', 'match' => 'بلژیک vs کانادا', 'time' => '18 دقیقه پیش'],
        ['user' => 'امیر محمدی', 'action' => 'بروزرسانی پیش‌بینی کل جام', 'match' => '-', 'time' => '25 دقیقه پیش'],
        ['user' => 'فاطمه نوری', 'action' => 'پیش‌بینی بازی', 'match' => 'مراکش vs کرواسی', 'time' => '31 دقیقه پیش'],
    ];
    
    $upcomingMatches = [
        ['id' => 7, 'team1' => 'مکزیک', 'team2' => 'لهستان', 'date' => '2024-11-22', 'time' => '19:00', 'predictions' => 234],
        ['id' => 8, 'team1' => 'فرانسه', 'team2' => 'استرالیا', 'date' => '2024-11-22', 'time' => '22:00', 'predictions' => 198],
        ['id' => 9, 'team1' => 'مراکش', 'team2' => 'کرواسی', 'date' => '2024-11-23', 'time' => '13:00', 'predictions' => 156],
        ['id' => 10, 'team1' => 'آلمان', 'team2' => 'ژاپن', 'date' => '2024-11-23', 'time' => '16:00', 'predictions' => 289],
    ];
    
    return view('admin.dashboard', compact('stats', 'recentActivity', 'upcomingMatches'));
})->name('admin.dashboard');

// Admin Teams
Route::get('/admin/teams', function () {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    
    $teams = [
        ['id' => 1, 'name' => 'قطر', 'group' => 'A', 'fifa_rank' => 50, 'played' => 3, 'won' => 0, 'drawn' => 0, 'lost' => 3, 'points' => 0],
        ['id' => 2, 'name' => 'اکوادور', 'group' => 'A', 'fifa_rank' => 44, 'played' => 3, 'won' => 1, 'drawn' => 1, 'lost' => 1, 'points' => 4],
        ['id' => 3, 'name' => 'سنگال', 'group' => 'A', 'fifa_rank' => 18, 'played' => 3, 'won' => 2, 'drawn' => 0, 'lost' => 1, 'points' => 6],
        ['id' => 4, 'name' => 'هلند', 'group' => 'A', 'fifa_rank' => 8, 'played' => 3, 'won' => 2, 'drawn' => 1, 'lost' => 0, 'points' => 7],
        ['id' => 5, 'name' => 'انگلیس', 'group' => 'B', 'fifa_rank' => 5, 'played' => 3, 'won' => 2, 'drawn' => 1, 'lost' => 0, 'points' => 7],
        ['id' => 6, 'name' => 'ایران', 'group' => 'B', 'fifa_rank' => 20, 'played' => 3, 'won' => 1, 'drawn' => 0, 'lost' => 2, 'points' => 3],
        ['id' => 7, 'name' => 'ولز', 'group' => 'B', 'fifa_rank' => 19, 'played' => 3, 'won' => 0, 'drawn' => 1, 'lost' => 2, 'points' => 1],
        ['id' => 8, 'name' => 'آمریکا', 'group' => 'B', 'fifa_rank' => 16, 'played' => 3, 'won' => 1, 'drawn' => 2, 'lost' => 0, 'points' => 5],
        ['id' => 9, 'name' => 'آرژانتین', 'group' => 'C', 'fifa_rank' => 3, 'played' => 2, 'won' => 1, 'drawn' => 0, 'lost' => 1, 'points' => 3],
        ['id' => 10, 'name' => 'عربستان', 'group' => 'C', 'fifa_rank' => 51, 'played' => 2, 'won' => 1, 'drawn' => 0, 'lost' => 1, 'points' => 3],
        ['id' => 11, 'name' => 'مکزیک', 'group' => 'C', 'fifa_rank' => 13, 'played' => 1, 'won' => 0, 'drawn' => 0, 'lost' => 1, 'points' => 0],
        ['id' => 12, 'name' => 'لهستان', 'group' => 'C', 'fifa_rank' => 26, 'played' => 1, 'won' => 1, 'drawn' => 0, 'lost' => 0, 'points' => 3],
        ['id' => 13, 'name' => 'فرانسه', 'group' => 'D', 'fifa_rank' => 4, 'played' => 1, 'won' => 1, 'drawn' => 0, 'lost' => 0, 'points' => 3],
        ['id' => 14, 'name' => 'استرالیا', 'group' => 'D', 'fifa_rank' => 38, 'played' => 1, 'won' => 0, 'drawn' => 0, 'lost' => 1, 'points' => 0],
        ['id' => 15, 'name' => 'دانمارک', 'group' => 'D', 'fifa_rank' => 10, 'played' => 2, 'won' => 0, 'drawn' => 1, 'lost' => 1, 'points' => 1],
        ['id' => 16, 'name' => 'تونس', 'group' => 'D', 'fifa_rank' => 30, 'played' => 2, 'won' => 0, 'drawn' => 2, 'lost' => 0, 'points' => 2],
    ];
    
    return view('admin.teams.index', compact('teams'));
})->name('admin.teams.index');

Route::get('/admin/teams/create', function () {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    
    $groups = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    
    return view('admin.teams.create', compact('groups'));
})->name('admin.teams.create');

Route::get('/admin/teams/{id}/edit', function ($id) {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    
    $team = [
        'id' => $id,
        'name' => 'آرژانتین',
        'group' => 'C',
        'fifa_rank' => 3
    ];
    
    $groups = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    
    return view('admin.teams.edit', compact('team', 'groups'));
})->name('admin.teams.edit');

// Admin Groups
Route::get('/admin/groups', function () {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    
    $groups = [
        [
            'name' => 'A',
            'teams' => [
                ['name' => 'هلند', 'played' => 3, 'won' => 2, 'drawn' => 1, 'lost' => 0, 'gf' => 5, 'ga' => 1, 'gd' => 4, 'points' => 7],
                ['name' => 'سنگال', 'played' => 3, 'won' => 2, 'drawn' => 0, 'lost' => 1, 'gf' => 5, 'ga' => 4, 'gd' => 1, 'points' => 6],
                ['name' => 'اکوادور', 'played' => 3, 'won' => 1, 'drawn' => 1, 'lost' => 1, 'gf' => 4, 'ga' => 3, 'gd' => 1, 'points' => 4],
                ['name' => 'قطر', 'played' => 3, 'won' => 0, 'drawn' => 0, 'lost' => 3, 'gf' => 1, 'ga' => 7, 'gd' => -6, 'points' => 0],
            ]
        ],
        [
            'name' => 'B',
            'teams' => [
                ['name' => 'انگلیس', 'played' => 3, 'won' => 2, 'drawn' => 1, 'lost' => 0, 'gf' => 9, 'ga' => 2, 'gd' => 7, 'points' => 7],
                ['name' => 'آمریکا', 'played' => 3, 'won' => 1, 'drawn' => 2, 'lost' => 0, 'gf' => 2, 'ga' => 1, 'gd' => 1, 'points' => 5],
                ['name' => 'ایران', 'played' => 3, 'won' => 1, 'drawn' => 0, 'lost' => 2, 'gf' => 4, 'ga' => 7, 'gd' => -3, 'points' => 3],
                ['name' => 'ولز', 'played' => 3, 'won' => 0, 'drawn' => 1, 'lost' => 2, 'gf' => 1, 'ga' => 6, 'gd' => -5, 'points' => 1],
            ]
        ],
        [
            'name' => 'C',
            'teams' => [
                ['name' => 'آرژانتین', 'played' => 2, 'won' => 1, 'drawn' => 0, 'lost' => 1, 'gf' => 3, 'ga' => 3, 'gd' => 0, 'points' => 3],
                ['name' => 'لهستان', 'played' => 2, 'won' => 1, 'drawn' => 0, 'lost' => 1, 'gf' => 2, 'ga' => 2, 'gd' => 0, 'points' => 3],
                ['name' => 'عربستان', 'played' => 2, 'won' => 1, 'drawn' => 0, 'lost' => 1, 'gf' => 2, 'ga' => 3, 'gd' => -1, 'points' => 3],
                ['name' => 'مکزیک', 'played' => 2, 'won' => 0, 'drawn' => 1, 'lost' => 1, 'gf' => 0, 'ga' => 1, 'gd' => -1, 'points' => 1],
            ]
        ],
        [
            'name' => 'D',
            'teams' => [
                ['name' => 'فرانسه', 'played' => 2, 'won' => 2, 'drawn' => 0, 'lost' => 0, 'gf' => 6, 'ga' => 2, 'gd' => 4, 'points' => 6],
                ['name' => 'تونس', 'played' => 2, 'won' => 0, 'drawn' => 2, 'lost' => 0, 'gf' => 1, 'ga' => 1, 'gd' => 0, 'points' => 2],
                ['name' => 'دانمارک', 'played' => 2, 'won' => 0, 'drawn' => 1, 'lost' => 1, 'gf' => 1, 'ga' => 2, 'gd' => -1, 'points' => 1],
                ['name' => 'استرالیا', 'played' => 2, 'won' => 0, 'drawn' => 1, 'lost' => 1, 'gf' => 2, 'ga' => 5, 'gd' => -3, 'points' => 1],
            ]
        ],
    ];
    
    return view('admin.groups.index', compact('groups'));
})->name('admin.groups.index');

// Admin Matches
Route::get('/admin/matches', function () {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    
    $matches = [
        ['id' => 1, 'team1' => 'قطر', 'team2' => 'اکوادور', 'date' => '2024-11-20', 'time' => '19:00', 'stage' => 'مرحله گروهی', 'group' => 'A', 'venue' => 'استادیوم البیت', 'status' => 'finished', 'score1' => 0, 'score2' => 2, 'predictions_count' => 847],
        ['id' => 2, 'team1' => 'انگلیس', 'team2' => 'ایران', 'date' => '2024-11-21', 'time' => '16:00', 'stage' => 'مرحله گروهی', 'group' => 'B', 'venue' => 'استادیوم خلیفه', 'status' => 'finished', 'score1' => 6, 'score2' => 2, 'predictions_count' => 847],
        ['id' => 3, 'team1' => 'سنگال', 'team2' => 'هلند', 'date' => '2024-11-21', 'time' => '19:00', 'stage' => 'مرحله گروهی', 'group' => 'A', 'venue' => 'استادیوم الثمامه', 'status' => 'finished', 'score1' => 0, 'score2' => 2, 'predictions_count' => 812],
        ['id' => 4, 'team1' => 'آمریکا', 'team2' => 'ولز', 'date' => '2024-11-21', 'time' => '22:00', 'stage' => 'مرحله گروهی', 'group' => 'B', 'venue' => 'استادیوم احمدبن‌علی', 'status' => 'finished', 'score1' => 1, 'score2' => 1, 'predictions_count' => 789],
        ['id' => 5, 'team1' => 'آرژانتین', 'team2' => 'عربستان', 'date' => '2024-11-22', 'time' => '13:00', 'stage' => 'مرحله گروهی', 'group' => 'C', 'venue' => 'استادیوم لوسیل', 'status' => 'finished', 'score1' => 1, 'score2' => 2, 'predictions_count' => 847],
        ['id' => 6, 'team1' => 'دانمارک', 'team2' => 'تونس', 'date' => '2024-11-22', 'time' => '16:00', 'stage' => 'مرحله گروهی', 'group' => 'D', 'venue' => 'استادیوم مدینه آموزشی', 'status' => 'live', 'score1' => 1, 'score2' => 0, 'predictions_count' => 734],
        ['id' => 7, 'team1' => 'مکزیک', 'team2' => 'لهستان', 'date' => '2024-11-22', 'time' => '19:00', 'stage' => 'مرحله گروهی', 'group' => 'C', 'venue' => 'استادیوم 974', 'status' => 'upcoming', 'score1' => null, 'score2' => null, 'predictions_count' => 567],
        ['id' => 8, 'team1' => 'فرانسه', 'team2' => 'استرالیا', 'date' => '2024-11-22', 'time' => '22:00', 'stage' => 'مرحله گروهی', 'group' => 'D', 'venue' => 'استادیوم الجنوب', 'status' => 'upcoming', 'score1' => null, 'score2' => null, 'predictions_count' => 623],
        ['id' => 9, 'team1' => 'مراکش', 'team2' => 'کرواسی', 'date' => '2024-11-23', 'time' => '13:00', 'stage' => 'مرحله گروهی', 'group' => 'F', 'venue' => 'استادیوم البیت', 'status' => 'upcoming', 'score1' => null, 'score2' => null, 'predictions_count' => 445],
        ['id' => 10, 'team1' => 'آلمان', 'team2' => 'ژاپن', 'date' => '2024-11-23', 'time' => '16:00', 'stage' => 'مرحله گروهی', 'group' => 'E', 'venue' => 'استادیوم خلیفه', 'status' => 'upcoming', 'score1' => null, 'score2' => null, 'predictions_count' => 712],
        ['id' => 11, 'team1' => 'اسپانیا', 'team2' => 'کاستاریکا', 'date' => '2024-11-23', 'time' => '19:00', 'stage' => 'مرحله گروهی', 'group' => 'E', 'venue' => 'استادیوم الثمامه', 'status' => 'upcoming', 'score1' => null, 'score2' => null, 'predictions_count' => 678],
        ['id' => 12, 'team1' => 'بلژیک', 'team2' => 'کانادا', 'date' => '2024-11-23', 'time' => '22:00', 'stage' => 'مرحله گروهی', 'group' => 'F', 'venue' => 'استادیوم احمدبن‌علی', 'status' => 'upcoming', 'score1' => null, 'score2' => null, 'predictions_count' => 534],
    ];
    
    return view('admin.matches.index', compact('matches'));
})->name('admin.matches.index');

Route::get('/admin/matches/create', function () {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    
    $teams = ['قطر', 'اکوادور', 'سنگال', 'هلند', 'انگلیس', 'ایران', 'ولز', 'آمریکا', 'آرژانتین', 'عربستان', 'مکزیک', 'لهستان', 'فرانسه', 'استرالیا', 'دانمارک', 'تونس', 'اسپانیا', 'کاستاریکا', 'آلمان', 'ژاپن', 'بلژیک', 'کانادا', 'مراکش', 'کرواسی', 'برزیل', 'صربستان', 'سوئیس', 'کامرون', 'پرتغال', 'غنا', 'اروگوئه', 'کره‌جنوبی'];
    $stages = ['مرحله گروهی', 'یک‌هشتم نهایی', 'یک‌چهارم نهایی', 'نیمه‌نهایی', 'فینال'];
    $groups = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    $venues = ['استادیوم البیت', 'استادیوم خلیفه', 'استادیوم الثمامه', 'استادیوم احمدبن‌علی', 'استادیوم لوسیل', 'استادیوم مدینه آموزشی', 'استادیوم 974', 'استادیوم الجنوب'];
    
    return view('admin.matches.create', compact('teams', 'stages', 'groups', 'venues'));
})->name('admin.matches.create');

Route::get('/admin/matches/{id}/edit', function ($id) {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    
    $match = [
        'id' => $id,
        'team1' => 'آلمان',
        'team2' => 'ژاپن',
        'date' => '2024-11-23',
        'time' => '16:00',
        'stage' => 'مرحله گروهی',
        'group' => 'E',
        'venue' => 'استادیوم خلیفه',
        'status' => 'upcoming',
        'score1' => null,
        'score2' => null
    ];
    
    $teams = ['قطر', 'اکوادور', 'سنگال', 'هلند', 'انگلیس', 'ایران', 'ولز', 'آمریکا', 'آرژانتین', 'عربستان', 'مکزیک', 'لهستان', 'فرانسه', 'استرالیا', 'دانمارک', 'تونس', 'اسپانیا', 'کاستاریکا', 'آلمان', 'ژاپن', 'بلژیک', 'کانادا', 'مراکش', 'کرواسی', 'برزیل', 'صربستان', 'سوئیس', 'کامرون', 'پرتغال', 'غنا', 'اروگوئه', 'کره‌جنوبی'];
    $stages = ['مرحله گروهی', 'یک‌هشتم نهایی', 'یک‌چهارم نهایی', 'نیمه‌نهایی', 'فینال'];
    $groups = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    $venues = ['استادیوم البیت', 'استادیوم خلیفه', 'استادیوم الثمامه', 'استادیوم احمدبن‌علی', 'استادیوم لوسیل', 'استادیوم مدینه آموزشی', 'استادیوم 974', 'استادیوم الجنوب'];
    
    return view('admin.matches.edit', compact('match', 'teams', 'stages', 'groups', 'venues'));
})->name('admin.matches.edit');

Route::get('/admin/matches/{id}/result', function ($id) {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    
    $match = [
        'id' => $id,
        'team1' => 'آلمان',
        'team2' => 'ژاپن',
        'date' => '2024-11-23',
        'time' => '16:00',
        'stage' => 'مرحله گروهی',
        'group' => 'E',
        'venue' => 'استادیوم خلیفه',
        'status' => 'live',
        'score1' => null,
        'score2' => null,
        'predictions_count' => 712
    ];
    
    return view('admin.matches.result', compact('match'));
})->name('admin.matches.result');

// Admin Users
Route::get('/admin/users', function () {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    
    $users = [
        ['id' => 1, 'name' => 'علی رضایی', 'email' => 'ali@example.com', 'total_score' => 209, 'rank' => 1, 'predictions' => 32, 'joined' => '2024-11-15', 'last_active' => '2 دقیقه پیش'],
        ['id' => 2, 'name' => 'سارا احمدی', 'email' => 'sara@example.com', 'total_score' => 124, 'rank' => 3, 'predictions' => 28, 'joined' => '2024-11-16', 'last_active' => '5 دقیقه پیش'],
        ['id' => 3, 'name' => 'رضا کریمی', 'email' => 'reza@example.com', 'total_score' => 117, 'rank' => 4, 'predictions' => 26, 'joined' => '2024-11-14', 'last_active' => '12 دقیقه پیش'],
        ['id' => 4, 'name' => 'مریم حسینی', 'email' => 'maryam@example.com', 'total_score' => 113, 'rank' => 5, 'predictions' => 25, 'joined' => '2024-11-17', 'last_active' => '18 دقیقه پیش'],
        ['id' => 5, 'name' => 'امیر محمدی', 'email' => 'amir@example.com', 'total_score' => 109, 'rank' => 6, 'predictions' => 30, 'joined' => '2024-11-13', 'last_active' => '25 دقیقه پیش'],
        ['id' => 6, 'name' => 'فاطمه نوری', 'email' => 'fatemeh@example.com', 'total_score' => 106, 'rank' => 7, 'predictions' => 27, 'joined' => '2024-11-18', 'last_active' => '31 دقیقه پیش'],
        ['id' => 7, 'name' => 'حسین عباسی', 'email' => 'hosein@example.com', 'total_score' => 100, 'rank' => 8, 'predictions' => 29, 'joined' => '2024-11-15', 'last_active' => '45 دقیقه پیش'],
        ['id' => 8, 'name' => 'زهرا قاسمی', 'email' => 'zahra@example.com', 'total_score' => 94, 'rank' => 9, 'predictions' => 22, 'joined' => '2024-11-19', 'last_active' => '1 ساعت پیش'],
        ['id' => 9, 'name' => 'محمد رستمی', 'email' => 'mohammad@example.com', 'total_score' => 91, 'rank' => 10, 'predictions' => 31, 'joined' => '2024-11-12', 'last_active' => '2 ساعت پیش'],
        ['id' => 10, 'name' => 'نرگس صادقی', 'email' => 'narges@example.com', 'total_score' => 87, 'rank' => 11, 'predictions' => 24, 'joined' => '2024-11-16', 'last_active' => '3 ساعت پیش'],
    ];
    
    return view('admin.users.index', compact('users'));
})->name('admin.users.index');

Route::get('/admin/users/{id}', function ($id) {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    
    $user = [
        'id' => $id,
        'name' => 'علی رضایی',
        'email' => 'ali@example.com',
        'tournament_score' => 67,
        'match_score' => 142,
        'total_score' => 209,
        'rank' => 1,
        'predictions' => 32,
        'accuracy' => 78.1,
        'exact_scores' => 12,
        'joined' => '2024-11-15',
        'last_active' => '2 دقیقه پیش'
    ];
    
    $predictions = [
        ['match' => 'قطر vs اکوادور', 'predicted' => '1-1', 'actual' => '0-2', 'points' => 0, 'date' => '2024-11-20'],
        ['match' => 'انگلیس vs ایران', 'predicted' => '3-0', 'actual' => '6-2', 'points' => 3, 'date' => '2024-11-21'],
        ['match' => 'سنگال vs هلند', 'predicted' => '0-2', 'actual' => '0-2', 'points' => 5, 'date' => '2024-11-21'],
        ['match' => 'آمریکا vs ولز', 'predicted' => '1-1', 'actual' => '1-1', 'points' => 5, 'date' => '2024-11-21'],
        ['match' => 'آرژانتین vs عربستان', 'predicted' => '3-0', 'actual' => '1-2', 'points' => 0, 'date' => '2024-11-22'],
    ];
    
    return view('admin.users.show', compact('user', 'predictions'));
})->name('admin.users.show');