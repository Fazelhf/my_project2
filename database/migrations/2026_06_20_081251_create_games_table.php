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
            // اتصال به جدول تیم‌ها برای تیم اول و دوم
            $table->foreignId('team1_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('team2_id')->constrained('teams')->cascadeOnDelete();
            
            $table->dateTime('game_datetime');
            $table->enum('stage', ['group', 'round_16', 'quarter_final', 'semi_final', 'final'])->default('group');
            
            // نتیجه بازی که ادمین وارد می‌کند
            $table->unsignedTinyInteger('real_score1')->nullable();
            $table->unsignedTinyInteger('real_score2')->nullable();
            
            $table->enum('status', ['upcoming', 'live', 'finished'])->default('upcoming');
            $table->string('venue', 150)->nullable(); // نام ورزشگاه
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};