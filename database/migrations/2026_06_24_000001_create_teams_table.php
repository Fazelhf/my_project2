<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('نام تیم به انگلیسی، مثل Brazil');
            $table->string('name_fa', 100)->nullable()->comment('نام تیم به فارسی، مثل برزیل');
            $table->string('code', 3)->unique()->comment('کد ۳ حرفی فیفا، مثل BRA یا FRA');
            $table->char('group_name', 1)->nullable()->comment('گروه A تا H - برای مرحله گروهی');
            $table->string('flag_url')->nullable()->comment('مسیر تصویر پرچم تیم');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
