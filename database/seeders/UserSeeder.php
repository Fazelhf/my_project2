<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── ادمین ────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@worldcup.local'],
            [
                'name'       => 'مدیر سیستم',
                'password'   => Hash::make('admin1234'),
                'department' => 'فناوری اطلاعات',
                'is_admin'   => true,
                'total_score' => 0,
            ]
        );

        // ── کاربران نمونه ─────────────────────────────────────────
        $users = [
            ['name' => 'علی رضایی',    'email' => 'ali@worldcup.local',    'department' => 'فناوری اطلاعات'],
            ['name' => 'سارا احمدی',   'email' => 'sara@worldcup.local',   'department' => 'مالی'],
            ['name' => 'محمد کریمی',   'email' => 'mohammad@worldcup.local','department' => 'بازاریابی'],
            ['name' => 'فاطمه نوری',   'email' => 'fateme@worldcup.local', 'department' => 'منابع انسانی'],
            ['name' => 'رضا صادقی',    'email' => 'reza@worldcup.local',   'department' => 'عملیات'],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'        => $data['name'],
                    'password'    => Hash::make('password'),
                    'department'  => $data['department'],
                    'is_admin'    => false,
                    'total_score' => 0,
                ]
            );
        }
    }
}
