<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\Admin\ScoringRuleController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\TeamStatsController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultsController;
use Illuminate\Support\Facades\Route;

// ─── صفحه خوش‌آمدگویی ─────────────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'))->name('home');

// ─── احراز هویت ───────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── بخش کاربران ──────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    Route::get('/results', [ResultsController::class, 'index'])->name('results.index');

    // پروفایل کاربر
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // پیش‌بینی بازی‌ها
    Route::prefix('games')->name('games.')->group(function () {
        Route::get('/', [PredictionController::class, 'index'])->name('index');
        Route::get('/{game}', [PredictionController::class, 'show'])->name('show');
        Route::post('/{game}/predict', [PredictionController::class, 'store'])->name('predict');
        Route::put('/{game}/predict', [PredictionController::class, 'update'])->name('predict.update');
    });
});

// ─── API تیم‌ها ────────────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/teams/{team}/stats', [TeamStatsController::class, 'stats']);
});

// ─── پنل ادمین ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // داشبورد
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/recalculate-scores', [AdminDashboardController::class, 'recalculateScores'])
            ->name('recalculate');

        // مدیریت تیم‌ها
        Route::resource('teams', AdminTeamController::class);

        // مدیریت بازی‌ها
        Route::resource('games', AdminGameController::class);

        // ثبت نتیجه بازی (اکشن جداگانه)
        Route::post('/games/{game}/result', [AdminGameController::class, 'submitResult'])
            ->name('games.result');
        // ویرایش نتیجه بازی تمام‌شده
        Route::post('/games/{game}/update-result', [AdminGameController::class, 'updateResult'])
            ->name('games.update-result');

        // ── مدیریت کاربران ────────────────────────────────────────────────────
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/profile', [UserManagementController::class, 'updateProfile'])->name('users.profile');
        Route::post('/users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])->name('users.toggle-active');
        Route::post('/users/{user}/note', [UserManagementController::class, 'updateNote'])->name('users.note');
        Route::post('/users/{user}/override', [UserManagementController::class, 'manualOverride'])->name('users.override');
        Route::post('/users/bulk-action', [UserManagementController::class, 'bulkAction'])->name('users.bulk-action');
        Route::post('/predictions/{prediction}/edit', [UserManagementController::class, 'editPrediction'])->name('predictions.edit');
        Route::post('/predictions/{prediction}/points-override', [UserManagementController::class, 'overridePredictionPoints'])->name('predictions.points-override');

        // ── قوانین امتیازدهی ──────────────────────────────────────────────────
        Route::get('/scoring-rules', [ScoringRuleController::class, 'index'])->name('scoring-rules.index');
        Route::post('/scoring-rules/{game}', [ScoringRuleController::class, 'update'])->name('scoring-rules.update');
        Route::delete('/scoring-rules/{game}', [ScoringRuleController::class, 'destroy'])->name('scoring-rules.destroy');

        // ── Audit Log ──────────────────────────────────────────────────────────
        Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-log');

        // ایمپورت / اکسپورت
        Route::get('/import-export', [ImportExportController::class, 'index'])->name('import-export');
        Route::post('/import/games', [ImportExportController::class, 'importGames'])->name('import.games');
        Route::get('/export/games', [ImportExportController::class, 'exportGames'])->name('export.games');
        Route::get('/export/leaderboard', [ImportExportController::class, 'exportLeaderboard'])->name('export.leaderboard');
        Route::get('/export/user/{user}/predictions', [ImportExportController::class, 'exportUserPredictions'])->name('export.user.predictions');
        Route::get('/import/predictions', [ImportExportController::class, 'importPredictionsPage'])->name('import.predictions');
        Route::post('/import/predictions', [ImportExportController::class, 'importPredictions'])->name('import.predictions.store');
    });
