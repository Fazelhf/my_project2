<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class RoundOf32Seeder extends Seeder
{
    public function run(): void
    {
        $games = [
            ['home'=>41,'away'=>47,'at'=>'2026-06-30 20:30','mn'=>1],
            ['home'=>13,'away'=>42,'at'=>'2026-07-01 00:30','mn'=>2],
            ['home'=>11,'away'=>2, 'at'=>'2026-07-01 04:30','mn'=>3],
            ['home'=>5, 'away'=>51,'at'=>'2026-07-01 19:30','mn'=>4],
            ['home'=>21,'away'=>3, 'at'=>'2026-07-01 23:30','mn'=>5],
            ['home'=>7, 'away'=>35,'at'=>'2026-07-02 03:30','mn'=>6],
            ['home'=>17,'away'=>49,'at'=>'2026-07-02 22:30','mn'=>7],
            ['home'=>29,'away'=>24,'at'=>'2026-07-03 02:30','mn'=>8],
            ['home'=>27,'away'=>48,'at'=>'2026-07-03 06:30','mn'=>9],
            ['home'=>14,'away'=>43,'at'=>'2026-07-03 21:30','mn'=>10],
            ['home'=>9, 'away'=>45,'at'=>'2026-07-04 01:30','mn'=>11],
            ['home'=>53,'away'=>30,'at'=>'2026-07-04 05:00','mn'=>12],
        ];

        foreach ($games as $g) {
            Game::firstOrCreate(
                ['home_team_id' => $g['home'], 'away_team_id' => $g['away'], 'stage' => 'round_of_32'],
                ['match_number' => $g['mn'], 'scheduled_at' => $g['at'], 'status' => 'upcoming']
            );
        }
    }
}
