<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_audit_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admin_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->comment('ادمینی که تغییر را اعمال کرده');

            $table->enum('action', [
                'user_activated',        // فعال‌سازی حساب
                'user_deactivated',      // غیرفعال‌سازی حساب
                'user_note_updated',     // بروزرسانی یادداشت کاربر
                'score_override',        // تنظیم دستی امتیاز کاربر
                'prediction_edited',     // ویرایش پیش‌بینی کاربر
                'prediction_points_override', // override امتیاز یک پیش‌بینی
                'scoring_rule_created',  // ایجاد قانون امتیازدهی بازی
                'scoring_rule_updated',  // بروزرسانی قانون امتیازدهی
                'bulk_activate',         // فعال‌سازی گروهی
                'bulk_deactivate',       // غیرفعال‌سازی گروهی
                'bulk_score_adjust',     // تنظیم گروهی امتیاز
                'recalculate_scores',    // بازمحاسبه امتیازات
            ])->comment('نوع اکشن');

            // target: به چه موجودیتی تغییر اعمال شده
            $table->string('target_type', 50)->comment('نوع هدف: User / Prediction / Game / GameScoringRule');
            $table->unsignedBigInteger('target_id')->comment('ID هدف');

            // snapshot قبل و بعد از تغییر
            $table->json('before')->nullable()->comment('مقادیر قبل از تغییر');
            $table->json('after')->nullable()->comment('مقادیر بعد از تغییر');

            $table->text('reason')->nullable()->comment('دلیل یا توضیح تغییر');

            // IP برای امنیت بیشتر
            $table->string('ip_address', 45)->nullable();

            $table->timestamp('created_at')->useCurrent();

            // Indexes
            $table->index(['target_type', 'target_id']);
            $table->index('admin_id');
            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_audit_logs');
    }
};
