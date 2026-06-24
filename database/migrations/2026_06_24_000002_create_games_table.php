<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();

            // تیم‌های بازی
            $table->foreignId('home_team_id')
                ->constrained('teams')
                ->restrictOnDelete()
                ->comment('تیم میزبان');

            $table->foreignId('away_team_id')
                ->constrained('teams')
                ->restrictOnDelete()
                ->comment('تیم مهمان');

            // اطلاعات مسابقه
            $table->unsignedTinyInteger('match_number')
                ->nullable()
                ->comment('شماره مسابقه برای مرتب‌سازی نمایش (۱ تا ۶۴)');

            $table->char('group_name', 1)
                ->nullable()
                ->comment('نام گروه (A تا H) - فقط برای مرحله گروهی');

            $table->enum('stage', [
                'group',         // مرحله گروهی
                'round_of_16',   // دور اول حذفی
                'quarter_final', // ربع نهایی
                'semi_final',    // نیمه نهایی
                'third_place',   // رده‌بندی سوم و چهارم
                'final',         // فینال
            ])->default('group')->comment('مرحله مسابقه');

            $table->dateTime('scheduled_at')->comment('تاریخ و ساعت برگزاری');
            $table->string('venue', 150)->nullable()->comment('نام ورزشگاه');

            // ─── نتیجه ۹۰ دقیقه قانونی ───────────────────────────────────────
            // این دو فیلد اساس محاسبه امتیاز پیش‌بینی هستند.
            // در مسابقات حذفی، وقت اضافه و پنالتی در نتیجه این دو فیلد لحاظ نمی‌شود.
            $table->unsignedTinyInteger('home_score')
                ->nullable()
                ->comment('گل تیم اول در ۹۰ دقیقه - مبنای محاسبه امتیاز');

            $table->unsignedTinyInteger('away_score')
                ->nullable()
                ->comment('گل تیم دوم در ۹۰ دقیقه - مبنای محاسبه امتیاز');

            // ─── نتیجه نهایی (فقط برای نمایش) ──────────────────────────────
            // در مسابقات گروهی برابر با home_score/away_score است.
            // در مسابقات حذفی ممکن است شامل وقت اضافه و پنالتی باشد.
            $table->unsignedTinyInteger('home_score_final')
                ->nullable()
                ->comment('نتیجه نهایی تیم اول (شامل وقت اضافه) - فقط برای نمایش');

            $table->unsignedTinyInteger('away_score_final')
                ->nullable()
                ->comment('نتیجه نهایی تیم دوم (شامل وقت اضافه) - فقط برای نمایش');

            // برنده نهایی در مسابقات حذفی (پس از احتمال پنالتی)
            $table->foreignId('winner_team_id')
                ->nullable()
                ->constrained('teams')
                ->nullOnDelete()
                ->comment('تیم برنده نهایی - برای مسابقات حذفی که با پنالتی تعیین می‌شوند');

            // ─── وضعیت مسابقه ─────────────────────────────────────────────────
            $table->enum('status', [
                'upcoming',   // پیش رو
                'live',       // در حال برگزاری
                'finished',   // پایان یافته
                'postponed',  // به تعویق افتاده
            ])->default('upcoming');

            // ─── قانون انضباطی ────────────────────────────────────────────────
            // اگر true باشد: این بازی از تمام محاسبات امتیاز خارج می‌شود
            // و پیش‌بینی هیچ کاربری برای این بازی امتیاز نخواهد گرفت.
            $table->boolean('is_disciplinary')
                ->default(false)
                ->comment('اگر true باشد این بازی از محاسبات امتیاز خارج می‌شود');

            $table->text('notes')->nullable()->comment('یادداشت ادمین');

            $table->timestamps();

            // ─── Index ها برای بهبود کارایی Query ───────────────────────────
            $table->index('stage');
            $table->index('status');
            $table->index('scheduled_at');
            $table->index('group_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
