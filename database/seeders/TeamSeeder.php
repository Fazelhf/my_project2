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
            ['name' => 'Mexico',                'name_fa' => 'مکزیک',               'code' => 'MEX', 'group_name' => 'A'],
            ['name' => 'South Africa',          'name_fa' => 'آفریقای جنوبی',       'code' => 'RSA', 'group_name' => 'A'],
            ['name' => 'South Korea',           'name_fa' => 'کره جنوبی',           'code' => 'KOR', 'group_name' => 'A'],
            ['name' => 'Czech Republic',        'name_fa' => 'جمهوری چک',           'code' => 'CZE', 'group_name' => 'A'],

            // ── Group B ──────────────────────────────────────────
            ['name' => 'Canada',                'name_fa' => 'کانادا',              'code' => 'CAN', 'group_name' => 'B'],
            ['name' => 'Bosnia & Herzegovina',  'name_fa' => 'بوسنی و هرزگوین',    'code' => 'BIH', 'group_name' => 'B'],
            ['name' => 'Qatar',                 'name_fa' => 'قطر',                 'code' => 'QAT', 'group_name' => 'B'],
            ['name' => 'Switzerland',           'name_fa' => 'سوئیس',               'code' => 'SUI', 'group_name' => 'B'],

            // ── Group C ──────────────────────────────────────────
            ['name' => 'Brazil',                'name_fa' => 'برزیل',               'code' => 'BRA', 'group_name' => 'C'],
            ['name' => 'Morocco',               'name_fa' => 'مراکش',               'code' => 'MAR', 'group_name' => 'C'],
            ['name' => 'Haiti',                 'name_fa' => 'هائیتی',              'code' => 'HAI', 'group_name' => 'C'],
            ['name' => 'Scotland',              'name_fa' => 'اسکاتلند',            'code' => 'SCO', 'group_name' => 'C'],

            // ── Group D ──────────────────────────────────────────
            ['name' => 'USA',                   'name_fa' => 'آمریکا',              'code' => 'USA', 'group_name' => 'D'],
            ['name' => 'Paraguay',              'name_fa' => 'پاراگوئه',            'code' => 'PAR', 'group_name' => 'D'],
            ['name' => 'Australia',             'name_fa' => 'استرالیا',            'code' => 'AUS', 'group_name' => 'D'],
            ['name' => 'Turkey',                'name_fa' => 'ترکیه',               'code' => 'TUR', 'group_name' => 'D'],

            // ── Group E ──────────────────────────────────────────
            ['name' => 'Germany',               'name_fa' => 'آلمان',               'code' => 'GER', 'group_name' => 'E'],
            ['name' => 'Curaçao',               'name_fa' => 'کوراسائو',            'code' => 'CUW', 'group_name' => 'E'],
            ['name' => 'Ivory Coast',           'name_fa' => 'ساحل عاج',            'code' => 'CIV', 'group_name' => 'E'],
            ['name' => 'Ecuador',               'name_fa' => 'اکوادور',             'code' => 'ECU', 'group_name' => 'E'],

            // ── Group F ──────────────────────────────────────────
            ['name' => 'Netherlands',           'name_fa' => 'هلند',                'code' => 'NED', 'group_name' => 'F'],
            ['name' => 'Japan',                 'name_fa' => 'ژاپن',                'code' => 'JPN', 'group_name' => 'F'],
            ['name' => 'Sweden',                'name_fa' => 'سوئد',                'code' => 'SWE', 'group_name' => 'F'],
            ['name' => 'Tunisia',               'name_fa' => 'تونس',                'code' => 'TUN', 'group_name' => 'F'],

            // ── Group G ──────────────────────────────────────────
            ['name' => 'Belgium',               'name_fa' => 'بلژیک',               'code' => 'BEL', 'group_name' => 'G'],
            ['name' => 'Egypt',                 'name_fa' => 'مصر',                 'code' => 'EGY', 'group_name' => 'G'],
            ['name' => 'Iran',                  'name_fa' => 'ایران',               'code' => 'IRN', 'group_name' => 'G'],
            ['name' => 'New Zealand',           'name_fa' => 'نیوزیلند',            'code' => 'NZL', 'group_name' => 'G'],

            // ── Group H ──────────────────────────────────────────
            ['name' => 'Spain',                 'name_fa' => 'اسپانیا',             'code' => 'ESP', 'group_name' => 'H'],
            ['name' => 'Cape Verde',            'name_fa' => 'کیپ ورد',             'code' => 'CPV', 'group_name' => 'H'],
            ['name' => 'Saudi Arabia',          'name_fa' => 'عربستان سعودی',       'code' => 'KSA', 'group_name' => 'H'],
            ['name' => 'Uruguay',               'name_fa' => 'اروگوئه',             'code' => 'URU', 'group_name' => 'H'],

            // ── Group I ──────────────────────────────────────────
            ['name' => 'France',                'name_fa' => 'فرانسه',              'code' => 'FRA', 'group_name' => 'I'],
            ['name' => 'Senegal',               'name_fa' => 'سنگال',               'code' => 'SEN', 'group_name' => 'I'],
            ['name' => 'Iraq',                  'name_fa' => 'عراق',                'code' => 'IRQ', 'group_name' => 'I'],
            ['name' => 'Norway',                'name_fa' => 'نروژ',                'code' => 'NOR', 'group_name' => 'I'],

            // ── Group J ──────────────────────────────────────────
            ['name' => 'Argentina',             'name_fa' => 'آرژانتین',            'code' => 'ARG', 'group_name' => 'J'],
            ['name' => 'Algeria',               'name_fa' => 'الجزایر',             'code' => 'ALG', 'group_name' => 'J'],
            ['name' => 'Austria',               'name_fa' => 'اتریش',               'code' => 'AUT', 'group_name' => 'J'],
            ['name' => 'Jordan',                'name_fa' => 'اردن',                'code' => 'JOR', 'group_name' => 'J'],

            // ── Group K ──────────────────────────────────────────
            ['name' => 'Portugal',              'name_fa' => 'پرتغال',              'code' => 'POR', 'group_name' => 'K'],
            ['name' => 'DR Congo',              'name_fa' => 'کنگو',                'code' => 'COD', 'group_name' => 'K'],
            ['name' => 'Uzbekistan',            'name_fa' => 'ازبکستان',            'code' => 'UZB', 'group_name' => 'K'],
            ['name' => 'Colombia',              'name_fa' => 'کلمبیا',              'code' => 'COL', 'group_name' => 'K'],

            // ── Group L ──────────────────────────────────────────
            ['name' => 'England',               'name_fa' => 'انگلستان',            'code' => 'ENG', 'group_name' => 'L'],
            ['name' => 'Croatia',               'name_fa' => 'کرواسی',              'code' => 'CRO', 'group_name' => 'L'],
            ['name' => 'Ghana',                 'name_fa' => 'غنا',                 'code' => 'GHA', 'group_name' => 'L'],
            ['name' => 'Panama',                'name_fa' => 'پاناما',              'code' => 'PAN', 'group_name' => 'L'],
        ];

        foreach ($teams as $data) {
            Team::updateOrCreate(['code' => $data['code']], $data);
        }
    }
}
