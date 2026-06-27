<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_scoring_rules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_id')
                ->unique()
                ->constrained('games')
                ->cascadeOnDelete()
                ->comment('بازی مربوطه - هر بازی حداکثر یک قانون امتیازدهی دارد');

            // ─── امتیازات هر سطح ──────────────────────────────────────────────
            // اگر این جدول رکوردی نداشت، سیستم از مقادیر پیش‌فرض استفاده می‌کند
            $table->unsignedTinyInteger('points_exact')
                ->default(10)
                ->comment('امتیاز پیش‌بینی دقیق نتیجه (پیش‌فرض: ۱۰)');

            $table->unsignedTinyInteger('points_diff')
                ->default(7)
                ->comment('امتیاز پیش‌بینی اختلاف گل درست (پیش‌فرض: ۷)');

            $table->unsignedTinyInteger('points_outcome')
                ->default(5)
                ->comment('امتیاز پیش‌بینی روند درست (پیش‌فرض: ۵)');

            $table->unsignedTinyInteger('points_participation')
                ->default(2)
                ->comment('امتیاز شرکت اما پیش‌بینی اشتباه (پیش‌فرض: ۲)');

            // ─── ضریب نهایی ──────────────────────────────────────────────────
            // امتیاز نهایی = امتیاز محاسبه‌شده × multiplier
            // مثال: بازی فینال با ضریب ۲ → پیش‌بینی دقیق = ۲۰ امتیاز
            $table->decimal('multiplier', 4, 2)
                ->default(1.00)
                ->comment('ضریب کلی بازی (0.00 تا 10.00) - امتیاز نهایی = امتیاز × ضریب');

            $table->boolean('is_active')
                ->default(true)
                ->comment('اگر false باشد این بازی از امتیازدهی خارج می‌شود (مثل is_disciplinary)');

            $table->text('notes')
                ->nullable()
                ->comment('دلیل تغییر قانون امتیازدهی');

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete()
                ->comment('ادمین سازنده این قانون');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('آخرین ادمینی که این قانون را ویرایش کرده');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_scoring_rules');
    }
};
