<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE games MODIFY COLUMN stage ENUM(
            'group',
            'round_of_32',
            'round_of_16',
            'quarter_final',
            'semi_final',
            'third_place',
            'final'
        ) NOT NULL DEFAULT 'group'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE games MODIFY COLUMN stage ENUM(
            'group',
            'round_of_16',
            'quarter_final',
            'semi_final',
            'third_place',
            'final'
        ) NOT NULL DEFAULT 'group'");
    }
};
