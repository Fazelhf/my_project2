<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\PredictionController;
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

    // پیش‌بینی بازی‌ها
    Route::prefix('games')->name('games.')->group(function () {
        Route::get('/', [PredictionController::class, 'index'])->name('index');
        Route::get('/{game}', [PredictionController::class, 'show'])->name('show');
        Route::post('/{game}/predict', [PredictionController::class, 'store'])->name('predict');
        Route::put('/{game}/predict', [PredictionController::class, 'update'])->name('predict.update');
    });
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
    });
