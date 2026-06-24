<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->comment('کاربری که پیش‌بینی کرده');

            $table->foreignId('game_id')
                ->constrained('games')
                ->cascadeOnDelete()
                ->comment('مسابقه‌ای که برایش پیش‌بینی شده');

            // پیش‌بینی کاربر (بر اساس ۹۰ دقیقه قانونی)
            $table->unsignedTinyInteger('home_score')
                ->comment('پیش‌بینی گل تیم اول');

            $table->unsignedTinyInteger('away_score')
                ->comment('پیش‌بینی گل تیم دوم');

            // ─── امتیاز محاسبه‌شده ───────────────────────────────────────────
            // null = نتیجه بازی هنوز اعلام نشده
            // 10   = پیش‌بینی دقیق نتیجه
            // 7    = اختلاف گل درست یا مساوی با نتیجه متفاوت
            // 5    = تیم برنده/بازنده درست اما اختلاف غلط
            // 2    = شرکت اما پیش‌بینی اشتباه
            // 0    = بازی انضباطی (از محاسبات خارج)
            $table->unsignedTinyInteger('points_earned')
                ->nullable()
                ->comment('امتیاز کسب‌شده - null تا زمانی که ادمین نتیجه را ثبت نکرده');

            $table->timestamps();

            // هر کاربر فقط یک پیش‌بینی برای هر بازی می‌تواند داشته باشد
            $table->unique(['user_id', 'game_id'], 'unique_user_game_prediction');

            // Index برای Query های رده‌بندی
            $table->index(['user_id', 'points_earned']);
            $table->index('game_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
