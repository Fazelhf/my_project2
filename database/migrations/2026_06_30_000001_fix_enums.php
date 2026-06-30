<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE games MODIFY COLUMN stage ENUM('group','round_of_32','round_of_16','quarter_final','semi_final','third_place','final') NOT NULL DEFAULT 'group'");

        DB::statement("ALTER TABLE admin_audit_logs MODIFY COLUMN action ENUM(
            'user_activated',
            'user_deactivated',
            'user_note_updated',
            'user_profile_updated',
            'user_deleted',
            'score_override',
            'prediction_edited',
            'prediction_points_override',
            'scoring_rule_created',
            'scoring_rule_updated',
            'bulk_activate',
            'bulk_deactivate',
            'bulk_score_adjust',
            'recalculate_scores',
            'game_result_updated',
            'predictions_imported'
        ) NOT NULL");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE games MODIFY COLUMN stage ENUM('group','round_of_16','quarter_final','semi_final','third_place','final') NOT NULL DEFAULT 'group'");
    }
};
