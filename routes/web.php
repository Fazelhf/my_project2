<?php
// routes/web.php
use App\Http\Controllers\MatchPredictionController;
use App\Http\Controllers\TournamentPredictionController;
use App\Http\Controllers\Admin\AdminMatchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController; // کنترلر لاگین/ثبت نام شما
use Illuminate\Support\Facades\Route;

// مسیر صفحه اصلی
Route::get('/', function () { return view('welcome'); })->name('home');

// مسیرهای احراز هویت کاربران
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// هاب مسیرهای کاربران تایید شده (بر پایه سشن کاربری شما)
Route::middleware(['user.session.auth'])->group(function () {
    
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/leaderboard', [AuthController::class, 'leaderboard'])->name('leaderboard');

    // بخش اول: پیش‌بینی مسابقات بازی به بازی
    Route::get('/matches', [MatchPredictionController::class, 'index'])->name('matches');
    Route::get('/matches/{id}/predict', [MatchPredictionController::class, 'predict'])->name('match.predict');
    Route::post('/matches/{id}/predict', [MatchPredictionController::class, 'storePrediction']);

    // بخش دوم: پیش‌بینی کل تورنمنت (ساختار درختی جام)
    Route::get('/tournament-prediction', [TournamentPredictionController::class, 'index'])->name('tournament.prediction');
    Route::post('/tournament-prediction', [TournamentPredictionController::class, 'store']);
});
// routes/web.php

Route::middleware([\App\Http\Middleware\UserSessionAuth::class])->group(function () {
    // روت جدید داشبورد متصل به کنترلر دینامیک
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // روت جدید لیدربرد متصل به کنترلر دینامیک
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

    // سایر مسیرهای مسابقات...
    Route::get('/matches', [MatchPredictionController::class, 'index'])->name('matches');
    Route::get('/matches/{id}/predict', [MatchPredictionController::class, 'predict'])->name('match.predict');
    Route::post('/matches/{id}/predict', [MatchPredictionController::class, 'storePrediction']);
});
// پنل ادمین
Route::middleware(['admin.session.auth'])->prefix('admin')->name('admin.')->group(function () {
    // مسیرهای مدیریت بازی‌ها و تیم‌ها توسط ادمین...
    Route::post('/matches/{id}/result', [AdminMatchController::class, 'submitResult'])->name('matches.result.submit');
});

