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
            ['email' => 'fazel@admin'],
            [
                'name'       => 'admin',
                'password'   => Hash::make('admin123'),
                'department' => 'IT',
                'is_admin'   => true,
                'total_score' => 0,
            ]
        );



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
