<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournament_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('champion_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->foreignId('runner_up_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->foreignId('third_place_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->integer('champion_points')->default(0);
            $table->integer('runner_up_points')->default(0);
            $table->integer('third_place_points')->default(0);
            $table->timestamps();

            $table->unique('user_id');
        });

        Schema::create('tournament_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_predictions');
        Schema::dropIfExists('tournament_settings');
    }
};
