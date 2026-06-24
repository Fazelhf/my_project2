<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            // ── Group A ──────────────────────────────────────────
            ['name' => 'Qatar',        'name_fa' => 'قطر',         'code' => 'QAT', 'group_name' => 'A'],
            ['name' => 'Ecuador',      'name_fa' => 'اکوادور',     'code' => 'ECU', 'group_name' => 'A'],
            ['name' => 'Senegal',      'name_fa' => 'سنگال',       'code' => 'SEN', 'group_name' => 'A'],
            ['name' => 'Netherlands',  'name_fa' => 'هلند',        'code' => 'NED', 'group_name' => 'A'],

            // ── Group B ──────────────────────────────────────────
            ['name' => 'England',      'name_fa' => 'انگلستان',    'code' => 'ENG', 'group_name' => 'B'],
            ['name' => 'Iran',         'name_fa' => 'ایران',       'code' => 'IRN', 'group_name' => 'B'],
            ['name' => 'USA',          'name_fa' => 'آمریکا',      'code' => 'USA', 'group_name' => 'B'],
            ['name' => 'Wales',        'name_fa' => 'ولز',         'code' => 'WAL', 'group_name' => 'B'],

            // ── Group C ──────────────────────────────────────────
            ['name' => 'Argentina',    'name_fa' => 'آرژانتین',    'code' => 'ARG', 'group_name' => 'C'],
            ['name' => 'Saudi Arabia', 'name_fa' => 'عربستان',     'code' => 'KSA', 'group_name' => 'C'],
            ['name' => 'Mexico',       'name_fa' => 'مکزیک',       'code' => 'MEX', 'group_name' => 'C'],
            ['name' => 'Poland',       'name_fa' => 'لهستان',      'code' => 'POL', 'group_name' => 'C'],

            // ── Group D ──────────────────────────────────────────
            ['name' => 'France',       'name_fa' => 'فرانسه',      'code' => 'FRA', 'group_name' => 'D'],
            ['name' => 'Australia',    'name_fa' => 'استرالیا',    'code' => 'AUS', 'group_name' => 'D'],
            ['name' => 'Denmark',      'name_fa' => 'دانمارک',     'code' => 'DEN', 'group_name' => 'D'],
            ['name' => 'Tunisia',      'name_fa' => 'تونس',        'code' => 'TUN', 'group_name' => 'D'],

            // ── Group E ──────────────────────────────────────────
            ['name' => 'Spain',        'name_fa' => 'اسپانیا',     'code' => 'ESP', 'group_name' => 'E'],
            ['name' => 'Costa Rica',   'name_fa' => 'کاستاریکا',   'code' => 'CRC', 'group_name' => 'E'],
            ['name' => 'Germany',      'name_fa' => 'آلمان',       'code' => 'GER', 'group_name' => 'E'],
            ['name' => 'Japan',        'name_fa' => 'ژاپن',        'code' => 'JPN', 'group_name' => 'E'],

            // ── Group F ──────────────────────────────────────────
            ['name' => 'Belgium',      'name_fa' => 'بلژیک',       'code' => 'BEL', 'group_name' => 'F'],
            ['name' => 'Canada',       'name_fa' => 'کانادا',      'code' => 'CAN', 'group_name' => 'F'],
            ['name' => 'Morocco',      'name_fa' => 'مراکش',       'code' => 'MAR', 'group_name' => 'F'],
            ['name' => 'Croatia',      'name_fa' => 'کرواسی',      'code' => 'CRO', 'group_name' => 'F'],

            // ── Group G ──────────────────────────────────────────
            ['name' => 'Brazil',       'name_fa' => 'برزیل',       'code' => 'BRA', 'group_name' => 'G'],
            ['name' => 'Serbia',       'name_fa' => 'صربستان',     'code' => 'SRB', 'group_name' => 'G'],
            ['name' => 'Switzerland',  'name_fa' => 'سوئیس',       'code' => 'SUI', 'group_name' => 'G'],
            ['name' => 'Cameroon',     'name_fa' => 'کامرون',      'code' => 'CMR', 'group_name' => 'G'],

            // ── Group H ──────────────────────────────────────────
            ['name' => 'Portugal',     'name_fa' => 'پرتغال',      'code' => 'POR', 'group_name' => 'H'],
            ['name' => 'Ghana',        'name_fa' => 'غنا',         'code' => 'GHA', 'group_name' => 'H'],
            ['name' => 'Uruguay',      'name_fa' => 'اروگوئه',     'code' => 'URU', 'group_name' => 'H'],
            ['name' => 'South Korea',  'name_fa' => 'کره جنوبی',   'code' => 'KOR', 'group_name' => 'H'],
        ];

        foreach ($teams as $data) {
            Team::updateOrCreate(['code' => $data['code']], $data);
        }
    }
}
