<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * اجرای مایگریشن (اضافه کردن ستون‌ها)
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // اضافه کردن ۳ ستون امتیاز با مقدار پیش‌فرض صفر
            $table->unsignedInteger('tournament_score')->default(0)->after('remember_token');
            $table->unsignedInteger('match_score')->default(0)->after('tournament_score');
            $table->unsignedInteger('total_score')->default(0)->after('match_score');
        });
    }

    /**
     * برگرداندن مایگریشن (حذف ستون‌ها در صورت نیاز)
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tournament_score', 'match_score', 'total_score']);
        });
    }
};