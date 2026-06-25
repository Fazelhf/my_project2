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
        $p1 = $t->first();
        $p2 = $t->skip(1)->first();

        /*
         * Source: openfootball/worldcup.json — 2026/worldcup.json
         * Times converted to UTC. Scores included where available.
         * Format: [match_num, home_code, away_code, utc_datetime, venue, group, stage, home_score, away_score]
         * home/away_code = null for TBD knockout slots
         */
        $matches = [
            // ── Group A ──────────────────────────────────────────────────
            [1,  'MEX','RSA','2026-06-11 19:00','Estadio Azteca, Mexico City',        'A','group',2,0],
            [2,  'KOR','CZE','2026-06-12 02:00','Estadio Akron, Guadalajara',         'A','group',2,1],
            [3,  'CZE','RSA','2026-06-18 16:00','Mercedes-Benz Stadium, Atlanta',     'A','group',1,1],
            [4,  'MEX','KOR','2026-06-19 01:00','Estadio Akron, Guadalajara',         'A','group',1,0],
            [5,  'CZE','MEX','2026-06-25 01:00','Estadio Azteca, Mexico City',        'A','group',null,null],
            [6,  'RSA','KOR','2026-06-25 01:00','Estadio BBVA, Monterrey',            'A','group',null,null],

            // ── Group B ──────────────────────────────────────────────────
            [7,  'CAN','BIH','2026-06-12 19:00','BMO Field, Toronto',                 'B','group',1,1],
            [8,  'QAT','SUI','2026-06-13 19:00',"Levi's Stadium, San Francisco",      'B','group',1,1],
            [9,  'SUI','BIH','2026-06-18 19:00','SoFi Stadium, Los Angeles',          'B','group',4,1],
            [10, 'CAN','QAT','2026-06-18 22:00','BC Place, Vancouver',                'B','group',6,0],
            [11, 'SUI','CAN','2026-06-24 19:00','BC Place, Vancouver',                'B','group',2,1],
            [12, 'BIH','QAT','2026-06-24 19:00','Lumen Field, Seattle',              'B','group',3,1],

            // ── Group C ──────────────────────────────────────────────────
            [13, 'BRA','MAR','2026-06-13 22:00','MetLife Stadium, New York',          'C','group',1,1],
            [14, 'HAI','SCO','2026-06-14 01:00','Gillette Stadium, Boston',           'C','group',0,1],
            [15, 'SCO','MAR','2026-06-19 22:00','Gillette Stadium, Boston',           'C','group',0,1],
            [16, 'BRA','HAI','2026-06-20 00:30','Lincoln Financial Field, Philadelphia','C','group',3,0],
            [17, 'SCO','BRA','2026-06-24 22:00','Hard Rock Stadium, Miami',           'C','group',null,null],
            [18, 'MAR','HAI','2026-06-24 22:00','Mercedes-Benz Stadium, Atlanta',     'C','group',null,null],

            // ── Group D ──────────────────────────────────────────────────
            [19, 'USA','PAR','2026-06-13 01:00','SoFi Stadium, Los Angeles',          'D','group',4,1],
            [20, 'AUS','TUR','2026-06-14 04:00','BC Place, Vancouver',                'D','group',2,0],
            [21, 'USA','AUS','2026-06-19 19:00','Lumen Field, Seattle',               'D','group',2,0],
            [22, 'TUR','PAR','2026-06-20 03:00',"Levi's Stadium, San Francisco",      'D','group',0,1],
            [23, 'TUR','USA','2026-06-26 02:00','SoFi Stadium, Los Angeles',          'D','group',null,null],
            [24, 'PAR','AUS','2026-06-26 02:00',"Levi's Stadium, San Francisco",      'D','group',null,null],

            // ── Group E ──────────────────────────────────────────────────
            [25, 'GER','CUW','2026-06-14 17:00','NRG Stadium, Houston',               'E','group',7,1],
            [26, 'CIV','ECU','2026-06-14 23:00','Lincoln Financial Field, Philadelphia','E','group',1,0],
            [27, 'GER','CIV','2026-06-20 20:00','BMO Field, Toronto',                 'E','group',2,1],
            [28, 'ECU','CUW','2026-06-21 00:00','Arrowhead Stadium, Kansas City',     'E','group',0,0],
            [29, 'CUW','CIV','2026-06-25 20:00','Lincoln Financial Field, Philadelphia','E','group',null,null],
            [30, 'ECU','GER','2026-06-25 20:00','MetLife Stadium, New York',          'E','group',null,null],

            // ── Group F ──────────────────────────────────────────────────
            [31, 'NED','JPN','2026-06-14 20:00','AT&T Stadium, Dallas',               'F','group',2,2],
            [32, 'SWE','TUN','2026-06-15 02:00','Estadio BBVA, Monterrey',            'F','group',5,1],
            [33, 'NED','SWE','2026-06-20 17:00','NRG Stadium, Houston',               'F','group',5,1],
            [34, 'TUN','JPN','2026-06-21 04:00','Estadio BBVA, Monterrey',            'F','group',0,4],
            [35, 'JPN','SWE','2026-06-25 23:00','AT&T Stadium, Dallas',               'F','group',null,null],
            [36, 'TUN','NED','2026-06-25 23:00','Arrowhead Stadium, Kansas City',     'F','group',null,null],

            // ── Group G ──────────────────────────────────────────────────
            [37, 'BEL','EGY','2026-06-15 19:00','Lumen Field, Seattle',               'G','group',1,1],
            [38, 'IRN','NZL','2026-06-16 01:00','SoFi Stadium, Los Angeles',          'G','group',2,2],
            [39, 'BEL','IRN','2026-06-21 19:00','SoFi Stadium, Los Angeles',          'G','group',0,0],
            [40, 'NZL','EGY','2026-06-22 01:00','BC Place, Vancouver',                'G','group',1,3],
            [41, 'EGY','IRN','2026-06-27 03:00','Lumen Field, Seattle',               'G','group',null,null],
            [42, 'NZL','BEL','2026-06-27 03:00','BC Place, Vancouver',                'G','group',null,null],

            // ── Group H ──────────────────────────────────────────────────
            [43, 'ESP','CPV','2026-06-15 16:00','Mercedes-Benz Stadium, Atlanta',     'H','group',0,0],
            [44, 'KSA','URU','2026-06-15 22:00','Hard Rock Stadium, Miami',           'H','group',1,1],
            [45, 'ESP','KSA','2026-06-21 16:00','Mercedes-Benz Stadium, Atlanta',     'H','group',4,0],
            [46, 'URU','CPV','2026-06-21 22:00','Hard Rock Stadium, Miami',           'H','group',2,2],
            [47, 'CPV','KSA','2026-06-27 00:00','NRG Stadium, Houston',               'H','group',null,null],
            [48, 'URU','ESP','2026-06-27 00:00','Estadio Akron, Guadalajara',         'H','group',null,null],

            // ── Group I ──────────────────────────────────────────────────
            [49, 'FRA','SEN','2026-06-16 19:00','MetLife Stadium, New York',          'I','group',3,1],
            [50, 'IRQ','NOR','2026-06-16 22:00','Gillette Stadium, Boston',           'I','group',1,4],
            [51, 'FRA','IRQ','2026-06-22 21:00','Lincoln Financial Field, Philadelphia','I','group',3,0],
            [52, 'NOR','SEN','2026-06-23 00:00','MetLife Stadium, New York',          'I','group',3,2],
            [53, 'NOR','FRA','2026-06-26 19:00','Gillette Stadium, Boston',           'I','group',null,null],
            [54, 'SEN','IRQ','2026-06-26 19:00','BMO Field, Toronto',                 'I','group',null,null],

            // ── Group J ──────────────────────────────────────────────────
            [55, 'ARG','ALG','2026-06-17 01:00','Arrowhead Stadium, Kansas City',     'J','group',3,0],
            [56, 'AUT','JOR','2026-06-17 04:00',"Levi's Stadium, San Francisco",      'J','group',3,1],
            [57, 'ARG','AUT','2026-06-22 17:00','AT&T Stadium, Dallas',               'J','group',2,0],
            [58, 'JOR','ALG','2026-06-23 03:00',"Levi's Stadium, San Francisco",      'J','group',1,2],
            [59, 'ALG','AUT','2026-06-28 02:00','Arrowhead Stadium, Kansas City',     'J','group',null,null],
            [60, 'JOR','ARG','2026-06-28 02:00','AT&T Stadium, Dallas',               'J','group',null,null],

            // ── Group K ──────────────────────────────────────────────────
            [61, 'POR','COD','2026-06-17 17:00','NRG Stadium, Houston',               'K','group',1,1],
            [62, 'UZB','COL','2026-06-18 02:00','Estadio Azteca, Mexico City',        'K','group',1,3],
            [63, 'POR','UZB','2026-06-23 17:00','NRG Stadium, Houston',               'K','group',5,0],
            [64, 'COL','COD','2026-06-24 02:00','Estadio Akron, Guadalajara',         'K','group',1,0],
            [65, 'COL','POR','2026-06-27 23:30','Hard Rock Stadium, Miami',           'K','group',null,null],
            [66, 'COD','UZB','2026-06-27 23:30','Mercedes-Benz Stadium, Atlanta',     'K','group',null,null],

            // ── Group L ──────────────────────────────────────────────────
            [67, 'ENG','CRO','2026-06-17 20:00','AT&T Stadium, Dallas',               'L','group',4,2],
            [68, 'GHA','PAN','2026-06-17 23:00','BMO Field, Toronto',                 'L','group',1,0],
            [69, 'ENG','GHA','2026-06-23 20:00','Gillette Stadium, Boston',           'L','group',0,0],
            [70, 'PAN','CRO','2026-06-23 23:00','BMO Field, Toronto',                 'L','group',0,1],
            [71, 'PAN','ENG','2026-06-27 21:00','MetLife Stadium, New York',          'L','group',null,null],
            [72, 'CRO','GHA','2026-06-27 21:00','Lincoln Financial Field, Philadelphia','L','group',null,null],

            // ── Round of 32 (match 73–88) ──────────────────────────────
            [73, null,null,'2026-06-28 19:00','SoFi Stadium, Los Angeles',            null,'round_of_32',null,null],
            [74, 'GER',null,'2026-06-29 20:30','Gillette Stadium, Boston',            null,'round_of_32',null,null],
            [75, null,null,'2026-06-30 01:00','Estadio BBVA, Monterrey',             null,'round_of_32',null,null],
            [76, null,null,'2026-06-29 17:00','NRG Stadium, Houston',                null,'round_of_32',null,null],
            [77, null,null,'2026-06-30 21:00','MetLife Stadium, New York',            null,'round_of_32',null,null],
            [78, null,null,'2026-06-30 17:00','AT&T Stadium, Dallas',                 null,'round_of_32',null,null],
            [79, 'MEX',null,'2026-07-01 01:00','Estadio Azteca, Mexico City',         null,'round_of_32',null,null],
            [80, null,null,'2026-07-01 16:00','Mercedes-Benz Stadium, Atlanta',       null,'round_of_32',null,null],
            [81, 'USA',null,'2026-07-02 00:00',"Levi's Stadium, San Francisco",       null,'round_of_32',null,null],
            [82, null,null,'2026-07-01 20:00','Lumen Field, Seattle',                 null,'round_of_32',null,null],
            [83, null,null,'2026-07-02 23:00','BMO Field, Toronto',                   null,'round_of_32',null,null],
            [84, null,null,'2026-07-02 19:00','SoFi Stadium, Los Angeles',            null,'round_of_32',null,null],
            [85, null,null,'2026-07-03 03:00','BC Place, Vancouver',                  null,'round_of_32',null,null],
            [86, null,null,'2026-07-03 22:00','Hard Rock Stadium, Miami',             null,'round_of_32',null,null],
            [87, null,null,'2026-07-04 01:30','Arrowhead Stadium, Kansas City',       null,'round_of_32',null,null],
            [88, null,null,'2026-07-03 18:00','AT&T Stadium, Dallas',                 null,'round_of_32',null,null],

            // ── Round of 16 (match 89–96) ──────────────────────────────
            [89, null,null,'2026-07-04 21:00','Lincoln Financial Field, Philadelphia',null,'round_of_16',null,null],
            [90, null,null,'2026-07-04 17:00','NRG Stadium, Houston',                 null,'round_of_16',null,null],
            [91, null,null,'2026-07-05 20:00','MetLife Stadium, New York',            null,'round_of_16',null,null],
            [92, null,null,'2026-07-06 00:00','Estadio Azteca, Mexico City',          null,'round_of_16',null,null],
            [93, null,null,'2026-07-06 19:00','AT&T Stadium, Dallas',                 null,'round_of_16',null,null],
            [94, null,null,'2026-07-07 00:00','Lumen Field, Seattle',                 null,'round_of_16',null,null],
            [95, null,null,'2026-07-07 16:00','Mercedes-Benz Stadium, Atlanta',       null,'round_of_16',null,null],
            [96, null,null,'2026-07-07 20:00','BC Place, Vancouver',                  null,'round_of_16',null,null],

            // ── Quarter-finals (match 97–100) ──────────────────────────
            [97,  null,null,'2026-07-09 20:00','Gillette Stadium, Boston',            null,'quarter_final',null,null],
            [98,  null,null,'2026-07-10 19:00','SoFi Stadium, Los Angeles',           null,'quarter_final',null,null],
            [99,  null,null,'2026-07-11 21:00','Hard Rock Stadium, Miami',            null,'quarter_final',null,null],
            [100, null,null,'2026-07-12 01:00','Arrowhead Stadium, Kansas City',      null,'quarter_final',null,null],

            // ── Semi-finals (match 101–102) ────────────────────────────
            [101, null,null,'2026-07-14 19:00','AT&T Stadium, Dallas',                null,'semi_final',null,null],
            [102, null,null,'2026-07-15 19:00','Mercedes-Benz Stadium, Atlanta',      null,'semi_final',null,null],

            // ── Third place + Final ─────────────────────────────────────
            [103, null,null,'2026-07-18 21:00','Hard Rock Stadium, Miami',            null,'third_place',null,null],
            [104, null,null,'2026-07-19 19:00','MetLife Stadium, New York',           null,'final',null,null],
        ];

        foreach ($matches as [$num, $home, $away, $date, $venue, $group, $stage, $hs, $as]) {
            $homeId = $home && isset($t[$home]) ? $t[$home]->id : $p1->id;
            $awayId = $away && isset($t[$away]) ? $t[$away]->id : $p2->id;

            $status   = $hs !== null ? 'finished' : 'upcoming';
            $homeFinal = $hs;
            $awayFinal = $as;

            Game::updateOrCreate(
                ['match_number' => $num],
                [
                    'home_team_id' => $homeId,
                    'away_team_id' => $awayId,
                    'stage'        => $stage,
                    'group_name'   => $group,
                    'match_number' => $num,
                    'scheduled_at' => $date,
                    'venue'        => $venue,
                    'status'       => $status,
                    'home_score'   => $homeFinal,
                    'away_score'   => $awayFinal,
                ]
            );
        }
    }
}
