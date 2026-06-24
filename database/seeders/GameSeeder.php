<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Team;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        // تاریخ شروع جام — ۱۱ ژوئن ۲۰۲۶
        $start = \Carbon\Carbon::create(2026, 6, 11, 18, 0, 0);

        /*
         * هر گروه ۶ بازی دارد (ترکیب ۴ تیم، هر جفت یک بار)
         * مسابقات گروهی بین روز ۱ تا ۱۸ جام پخش می‌شود
         * برای سادگی هر روز ۳ بازی در نظر گرفتیم
         */
        $matchNumber = 1;

        $groups = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        foreach ($groups as $groupIndex => $group) {
            $groupTeams = Team::where('group_name', $group)->ordered()->get();

            if ($groupTeams->count() < 4) {
                continue;
            }

            // ۶ ترکیب ممکن از ۴ تیم
            $pairs = [
                [0, 1], [2, 3],
                [0, 2], [1, 3],
                [0, 3], [1, 2],
            ];

            foreach ($pairs as $pairIndex => [$a, $b]) {
                // هر گروه ۳ روز بازی دارد (۲ بازی در روز)
                $dayOffset   = ($groupIndex * 2) + intdiv($pairIndex, 2);
                $slotOffset  = ($pairIndex % 2) * 4; // ۴ ساعت بین بازی‌ها

                $scheduledAt = $start->copy()
                    ->addDays($dayOffset)
                    ->addHours($slotOffset);

                Game::updateOrCreate(
                    [
                        'home_team_id' => $groupTeams[$a]->id,
                        'away_team_id' => $groupTeams[$b]->id,
                        'stage'        => 'group',
                    ],
                    [
                        'stage'        => 'group',
                        'group_name'   => $group,
                        'match_number' => $matchNumber,
                        'scheduled_at' => $scheduledAt,
                        'venue'        => $this->venue($group),
                        'status'       => 'upcoming',
                    ]
                );

                $matchNumber++;
            }
        }

        // ── مرحله حذفی (placeholder — تیم‌ها مشخص نشده‌اند) ──────────
        $knockoutStages = [
            ['stage' => 'round_of_16',   'count' => 8,  'day_start' => 20],
            ['stage' => 'quarter_final', 'count' => 4,  'day_start' => 25],
            ['stage' => 'semi_final',    'count' => 2,  'day_start' => 29],
            ['stage' => 'third_place',   'count' => 1,  'day_start' => 32],
            ['stage' => 'final',         'count' => 1,  'day_start' => 33],
        ];

        // تیم‌های placeholder برای مرحله حذفی
        $allTeams = Team::all();
        $tba1 = $allTeams->firstWhere('code', 'ENG'); // نماد «منتظر»
        $tba2 = $allTeams->firstWhere('code', 'FRA');

        foreach ($knockoutStages as $ks) {
            for ($i = 0; $i < $ks['count']; $i++) {
                $scheduledAt = $start->copy()->addDays($ks['day_start'] + $i);

                Game::firstOrCreate(
                    [
                        'stage'        => $ks['stage'],
                        'match_number' => $matchNumber,
                    ],
                    [
                        'home_team_id' => $tba1->id,
                        'away_team_id' => $tba2->id,
                        'stage'        => $ks['stage'],
                        'match_number' => $matchNumber,
                        'scheduled_at' => $scheduledAt,
                        'venue'        => 'Lusail Stadium',
                        'status'       => 'upcoming',
                        'notes'        => 'تیم‌ها پس از مرحله قبل مشخص می‌شوند',
                    ]
                );

                $matchNumber++;
            }
        }
    }

    private function venue(string $group): string
    {
        return match ($group) {
            'A' => 'Al Bayt Stadium',
            'B' => 'Khalifa Stadium',
            'C' => 'Lusail Stadium',
            'D' => 'Education City Stadium',
            'E' => 'Stadium 974',
            'F' => 'Al Janoub Stadium',
            'G' => 'Ahmed bin Ali Stadium',
            'H' => 'Al Thumama Stadium',
            default => 'TBD',
        };
    }
}
