<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tournament_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            
            // ذخیره تیم‌های اول و دوم هر گروه به صورت آرایه جی‌سان
            // نمونه: {"A": ["هلند", "سنگال"], "B": ["انگلیس", "آمریکا"]}
            $table->json('group_winners'); 
            
            // ذخیره تیم‌های صعود کننده به مراحل حذفی به صورت آرایه
            $table->json('round_of_16');
            $table->json('quarter_finals');
            $table->json('semi_finals');
            $table->json('final');
            
            $table->string('champion');
            $table->boolean('is_locked')->default(false);
            $table->integer('points_earned')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_predictions');
    }
};