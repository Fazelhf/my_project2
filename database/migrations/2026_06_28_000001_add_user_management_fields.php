<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')
                ->default(true)
                ->after('is_admin')
                ->comment('فعال/غیرفعال بودن حساب کاربری');

            $table->integer('score_adjustment')
                ->default(0)
                ->after('total_score')
                ->comment('تنظیم دستی امتیاز توسط ادمین (می‌تواند منفی باشد)');

            $table->text('admin_note')
                ->nullable()
                ->after('score_adjustment')
                ->comment('یادداشت ادمین درباره کاربر');
        });

        Schema::table('predictions', function (Blueprint $table) {
            $table->unsignedTinyInteger('points_override')
                ->nullable()
                ->after('points_earned')
                ->comment('امتیاز دستی ادمین - اگر مقدار داشت به‌جای points_earned استفاده می‌شود');

            $table->boolean('is_admin_edited')
                ->default(false)
                ->after('points_override')
                ->comment('آیا این پیش‌بینی توسط ادمین ویرایش شده');

            $table->text('admin_note')
                ->nullable()
                ->after('is_admin_edited')
                ->comment('دلیل ویرایش توسط ادمین');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'score_adjustment', 'admin_note']);
        });

        Schema::table('predictions', function (Blueprint $table) {
            $table->dropColumn(['points_override', 'is_admin_edited', 'admin_note']);
        });
    }
};
