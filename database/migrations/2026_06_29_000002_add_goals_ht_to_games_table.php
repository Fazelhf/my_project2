<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->json('goals')->nullable()->after('away_score_final');
            $table->unsignedTinyInteger('home_score_ht')->nullable()->after('goals');
            $table->unsignedTinyInteger('away_score_ht')->nullable()->after('home_score_ht');
        });

        // Activate round_of_32 in MySQL enum (was commented out in previous migration)
        try {
            DB::statement("ALTER TABLE games MODIFY COLUMN stage ENUM('group','round_of_32','round_of_16','quarter_final','semi_final','third_place','final') NOT NULL DEFAULT 'group'");
        } catch (\Exception $e) {
            // SQLite — no-op
        }
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['goals', 'home_score_ht', 'away_score_ht']);
        });
    }
};
