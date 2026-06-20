<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete(); // اتصال به جدول games
            
            $table->unsignedTinyInteger('predicted_score1');
            $table->unsignedTinyInteger('predicted_score2');
            $table->unsignedTinyInteger('points_earned')->default(0);
            $table->timestamps();

            // جلوگیری از اینکه یک کاربر بتواند برای یک بازی دو پیش‌بینی ثبت کند
            $table->unique(['user_id', 'game_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_predictions');
    }
};