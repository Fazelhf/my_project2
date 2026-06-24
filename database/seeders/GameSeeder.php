<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Team;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $t = Team::all()->keyBy('code');

        // placeholder teams for knockout TBD slots
        $p1 = $t->first();
        $p2 = $t->skip(1)->first();

        /*
         * All times stored as UTC.
         * Source: openfootball/world-cup 2026--usa/cup.txt + cup_finals.txt
         * UTC conversion: local time + offset (e.g. 13:00 UTC-6 → 19:00 UTC)
         */

        // ── Group Stage ──────────────────────────────────────────────────
        $group = [
            // Group A
            [1,  'MEX', 'RSA', '2026-06-11 19:00', 'Estadio Azteca, Mexico City',              'A'],
            [2,  'KOR', 'CZE', '2026-06-12 02:00', 'Estadio Akron, Guadalajara',               'A'],
            [3,  'CZE', 'RSA', '2026-06-18 16:00', 'Mercedes-Benz Stadium, Atlanta',           'A'],
            [4,  'MEX', 'KOR', '2026-06-19 01:00', 'Estadio Akron, Guadalajara',               'A'],
            [5,  'CZE', 'MEX', '2026-06-25 01:00', 'Estadio Azteca, Mexico City',              'A'],
            [6,  'RSA', 'KOR', '2026-06-25 01:00', 'Estadio BBVA, Monterrey',                  'A'],

            // Group B
            [7,  'CAN', 'BIH', '2026-06-12 19:00', 'BMO Field, Toronto',                       'B'],
            [8,  'QAT', 'SUI', '2026-06-13 19:00', "Levi's Stadium, San Francisco",            'B'],
            [9,  'SUI', 'BIH', '2026-06-18 19:00', 'SoFi Stadium, Los Angeles',                'B'],
            [10, 'CAN', 'QAT', '2026-06-18 22:00', 'BC Place, Vancouver',                      'B'],
            [11, 'SUI', 'CAN', '2026-06-24 19:00', 'BC Place, Vancouver',                      'B'],
            [12, 'BIH', 'QAT', '2026-06-24 19:00', 'Lumen Field, Seattle',                     'B'],

            // Group C
            [13, 'BRA', 'MAR', '2026-06-13 22:00', 'MetLife Stadium, New York',                'C'],
            [14, 'HAI', 'SCO', '2026-06-14 01:00', 'Gillette Stadium, Boston',                 'C'],
            [15, 'SCO', 'MAR', '2026-06-19 22:00', 'Gillette Stadium, Boston',                 'C'],
            [16, 'BRA', 'HAI', '2026-06-20 00:30', 'Lincoln Financial Field, Philadelphia',    'C'],
            [17, 'SCO', 'BRA', '2026-06-24 22:00', 'Hard Rock Stadium, Miami',                 'C'],
            [18, 'MAR', 'HAI', '2026-06-24 22:00', 'Mercedes-Benz Stadium, Atlanta',           'C'],

            // Group D
            [19, 'USA', 'PAR', '2026-06-13 01:00', 'SoFi Stadium, Los Angeles',                'D'],
            [20, 'AUS', 'TUR', '2026-06-14 04:00', 'BC Place, Vancouver',                      'D'],
            [21, 'USA', 'AUS', '2026-06-19 19:00', 'Lumen Field, Seattle',                     'D'],
            [22, 'TUR', 'PAR', '2026-06-20 03:00', "Levi's Stadium, San Francisco",            'D'],
            [23, 'TUR', 'USA', '2026-06-26 02:00', 'SoFi Stadium, Los Angeles',                'D'],
            [24, 'PAR', 'AUS', '2026-06-26 02:00', "Levi's Stadium, San Francisco",            'D'],

            // Group E
            [25, 'GER', 'CUW', '2026-06-14 17:00', 'NRG Stadium, Houston',                    'E'],
            [26, 'CIV', 'ECU', '2026-06-14 23:00', 'Lincoln Financial Field, Philadelphia',   'E'],
            [27, 'GER', 'CIV', '2026-06-20 20:00', 'BMO Field, Toronto',                      'E'],
            [28, 'ECU', 'CUW', '2026-06-21 00:00', 'Arrowhead Stadium, Kansas City',          'E'],
            [29, 'CUW', 'CIV', '2026-06-25 20:00', 'Lincoln Financial Field, Philadelphia',   'E'],
            [30, 'ECU', 'GER', '2026-06-25 20:00', 'MetLife Stadium, New York',               'E'],

            // Group F
            [31, 'NED', 'JPN', '2026-06-14 20:00', 'AT&T Stadium, Dallas',                    'F'],
            [32, 'SWE', 'TUN', '2026-06-15 02:00', 'Estadio BBVA, Monterrey',                 'F'],
            [33, 'NED', 'SWE', '2026-06-20 17:00', 'NRG Stadium, Houston',                    'F'],
            [34, 'TUN', 'JPN', '2026-06-21 04:00', 'Estadio BBVA, Monterrey',                 'F'],
            [35, 'JPN', 'SWE', '2026-06-25 23:00', 'AT&T Stadium, Dallas',                    'F'],
            [36, 'TUN', 'NED', '2026-06-25 23:00', 'Arrowhead Stadium, Kansas City',          'F'],

            // Group G
            [37, 'BEL', 'EGY', '2026-06-15 19:00', 'Lumen Field, Seattle',                    'G'],
            [38, 'IRN', 'NZL', '2026-06-16 01:00', 'SoFi Stadium, Los Angeles',               'G'],
            [39, 'BEL', 'IRN', '2026-06-21 19:00', 'SoFi Stadium, Los Angeles',               'G'],
            [40, 'NZL', 'EGY', '2026-06-22 01:00', 'BC Place, Vancouver',                     'G'],
            [41, 'EGY', 'IRN', '2026-06-27 03:00', 'Lumen Field, Seattle',                    'G'],
            [42, 'NZL', 'BEL', '2026-06-27 03:00', 'BC Place, Vancouver',                     'G'],

            // Group H
            [43, 'ESP', 'CPV', '2026-06-15 16:00', 'Mercedes-Benz Stadium, Atlanta',          'H'],
            [44, 'KSA', 'URU', '2026-06-15 22:00', 'Hard Rock Stadium, Miami',                'H'],
            [45, 'ESP', 'KSA', '2026-06-21 16:00', 'Mercedes-Benz Stadium, Atlanta',          'H'],
            [46, 'URU', 'CPV', '2026-06-21 22:00', 'Hard Rock Stadium, Miami',                'H'],
            [47, 'CPV', 'KSA', '2026-06-27 00:00', 'NRG Stadium, Houston',                    'H'],
            [48, 'URU', 'ESP', '2026-06-27 00:00', 'Estadio Akron, Guadalajara',              'H'],

            // Group I
            [49, 'FRA', 'SEN', '2026-06-16 19:00', 'MetLife Stadium, New York',               'I'],
            [50, 'IRQ', 'NOR', '2026-06-16 22:00', 'Gillette Stadium, Boston',                'I'],
            [51, 'FRA', 'IRQ', '2026-06-22 21:00', 'Lincoln Financial Field, Philadelphia',   'I'],
            [52, 'NOR', 'SEN', '2026-06-23 00:00', 'MetLife Stadium, New York',               'I'],
            [53, 'NOR', 'FRA', '2026-06-26 19:00', 'Gillette Stadium, Boston',                'I'],
            [54, 'SEN', 'IRQ', '2026-06-26 19:00', 'BMO Field, Toronto',                      'I'],

            // Group J
            [55, 'ARG', 'ALG', '2026-06-17 01:00', 'Arrowhead Stadium, Kansas City',          'J'],
            [56, 'AUT', 'JOR', '2026-06-17 04:00', "Levi's Stadium, San Francisco",           'J'],
            [57, 'ARG', 'AUT', '2026-06-22 17:00', 'AT&T Stadium, Dallas',                    'J'],
            [58, 'JOR', 'ALG', '2026-06-23 03:00', "Levi's Stadium, San Francisco",           'J'],
            [59, 'ALG', 'AUT', '2026-06-28 02:00', 'Arrowhead Stadium, Kansas City',          'J'],
            [60, 'JOR', 'ARG', '2026-06-28 02:00', 'AT&T Stadium, Dallas',                    'J'],

            // Group K
            [61, 'POR', 'COD', '2026-06-17 17:00', 'NRG Stadium, Houston',                    'K'],
            [62, 'UZB', 'COL', '2026-06-18 02:00', 'Estadio Azteca, Mexico City',             'K'],
            [63, 'POR', 'UZB', '2026-06-23 17:00', 'NRG Stadium, Houston',                    'K'],
            [64, 'COL', 'COD', '2026-06-24 02:00', 'Estadio Akron, Guadalajara',              'K'],
            [65, 'COL', 'POR', '2026-06-27 23:30', 'Hard Rock Stadium, Miami',                'K'],
            [66, 'COD', 'UZB', '2026-06-27 23:30', 'Mercedes-Benz Stadium, Atlanta',          'K'],

            // Group L
            [67, 'ENG', 'CRO', '2026-06-17 20:00', 'AT&T Stadium, Dallas',                    'L'],
            [68, 'GHA', 'PAN', '2026-06-17 23:00', 'BMO Field, Toronto',                      'L'],
            [69, 'ENG', 'GHA', '2026-06-23 20:00', 'Gillette Stadium, Boston',                'L'],
            [70, 'PAN', 'CRO', '2026-06-23 23:00', 'BMO Field, Toronto',                      'L'],
            [71, 'PAN', 'ENG', '2026-06-27 21:00', 'MetLife Stadium, New York',               'L'],
            [72, 'CRO', 'GHA', '2026-06-27 21:00', 'Lincoln Financial Field, Philadelphia',   'L'],
        ];

        foreach ($group as [$num, $home, $away, $date, $venue, $grp]) {
            if (!isset($t[$home], $t[$away])) {
                continue;
            }
            Game::updateOrCreate(
                ['match_number' => $num],
                [
                    'home_team_id' => $t[$home]->id,
                    'away_team_id' => $t[$away]->id,
                    'stage'        => 'group',
                    'group_name'   => $grp,
                    'match_number' => $num,
                    'scheduled_at' => $date,
                    'venue'        => $venue,
                    'status'       => 'upcoming',
                ]
            );
        }

        // ── Knockout Stage ────────────────────────────────────────────────
        $knockout = [
            // Round of 32 (match 73–88)
            [73,  'round_of_16', '2026-06-28 19:00', 'SoFi Stadium, Los Angeles'],
            [74,  'round_of_16', '2026-06-29 20:30', 'Gillette Stadium, Boston'],
            [75,  'round_of_16', '2026-06-30 01:00', 'Estadio BBVA, Monterrey'],
            [76,  'round_of_16', '2026-06-29 17:00', 'NRG Stadium, Houston'],
            [77,  'round_of_16', '2026-06-30 21:00', 'MetLife Stadium, New York'],
            [78,  'round_of_16', '2026-06-30 17:00', 'AT&T Stadium, Dallas'],
            [79,  'round_of_16', '2026-07-01 01:00', 'Estadio Azteca, Mexico City'],
            [80,  'round_of_16', '2026-07-01 16:00', 'Mercedes-Benz Stadium, Atlanta'],
            [81,  'round_of_16', '2026-07-02 00:00', "Levi's Stadium, San Francisco"],
            [82,  'round_of_16', '2026-07-01 20:00', 'Lumen Field, Seattle'],
            [83,  'round_of_16', '2026-07-02 23:00', 'BMO Field, Toronto'],
            [84,  'round_of_16', '2026-07-02 19:00', 'SoFi Stadium, Los Angeles'],
            [85,  'round_of_16', '2026-07-03 03:00', 'BC Place, Vancouver'],
            [86,  'round_of_16', '2026-07-03 22:00', 'Hard Rock Stadium, Miami'],
            [87,  'round_of_16', '2026-07-04 01:30', 'Arrowhead Stadium, Kansas City'],
            [88,  'round_of_16', '2026-07-03 18:00', 'AT&T Stadium, Dallas'],

            // Round of 16 (match 89–96)
            [89,  'quarter_final', '2026-07-04 21:00', 'Lincoln Financial Field, Philadelphia'],
            [90,  'quarter_final', '2026-07-04 17:00', 'NRG Stadium, Houston'],
            [91,  'quarter_final', '2026-07-05 20:00', 'MetLife Stadium, New York'],
            [92,  'quarter_final', '2026-07-06 00:00', 'Estadio Azteca, Mexico City'],
            [93,  'quarter_final', '2026-07-06 19:00', 'AT&T Stadium, Dallas'],
            [94,  'quarter_final', '2026-07-07 00:00', 'Lumen Field, Seattle'],
            [95,  'quarter_final', '2026-07-07 16:00', 'Mercedes-Benz Stadium, Atlanta'],
            [96,  'quarter_final', '2026-07-07 20:00', 'BC Place, Vancouver'],

            // Quarter-finals (match 97–100)
            [97,  'semi_final', '2026-07-09 20:00', 'Gillette Stadium, Boston'],
            [98,  'semi_final', '2026-07-10 19:00', 'SoFi Stadium, Los Angeles'],
            [99,  'semi_final', '2026-07-11 21:00', 'Hard Rock Stadium, Miami'],
            [100, 'semi_final', '2026-07-12 01:00', 'Arrowhead Stadium, Kansas City'],

            // Semi-finals (match 101–102)
            [101, 'third_place', '2026-07-14 19:00', 'AT&T Stadium, Dallas'],
            [102, 'final',       '2026-07-15 19:00', 'Mercedes-Benz Stadium, Atlanta'],

            // Third place + Final
            [103, 'third_place', '2026-07-18 21:00', 'Hard Rock Stadium, Miami'],
            [104, 'final',       '2026-07-19 19:00', 'MetLife Stadium, New York'],
        ];

        foreach ($knockout as [$num, $stage, $date, $venue]) {
            Game::updateOrCreate(
                ['match_number' => $num],
                [
                    'home_team_id' => $p1->id,
                    'away_team_id' => $p2->id,
                    'stage'        => $stage,
                    'group_name'   => null,
                    'match_number' => $num,
                    'scheduled_at' => $date,
                    'venue'        => $venue,
                    'status'       => 'upcoming',
                ]
            );
        }
    }
}
