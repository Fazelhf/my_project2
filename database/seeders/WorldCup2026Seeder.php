<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class WorldCup2026Seeder extends Seeder
{
    // Convert UTC offset string + time + date to Tehran (UTC+3:30)
    private function toTehran(string $date, string $time): string
    {
        // e.g. "13:00 UTC-6" or "16:30 UTC-4"
        preg_match('/(\d+:\d+)\s+UTC([+-]\d+)/', $time, $m);
        $hhmm   = $m[1];
        $offset = (int)$m[2]; // e.g. -6
        // Tehran = UTC+3.5, so diff = 3.5 - offset
        $diffMinutes = (int)((3.5 - $offset) * 60);
        $dt = Carbon::createFromFormat('Y-m-d H:i', "$date $hhmm", 'UTC');
        $dt->addMinutes($diffMinutes);
        return $dt->format('Y-m-d H:i:s');
    }

    private function teamId(string $name): int
    {
        static $map = null;
        if ($map === null) {
            $map = [
                'Mexico'=>11,'South Africa'=>33,'South Korea'=>32,'Czech Republic'=>34,
                'Canada'=>22,'Bosnia & Herzegovina'=>35,'Bosnia and Herzegovina'=>35,
                'Qatar'=>1,'Switzerland'=>27,'Brazil'=>25,'Morocco'=>23,'Haiti'=>36,
                'Scotland'=>37,'USA'=>7,'Paraguay'=>38,'Australia'=>14,'Turkey'=>39,
                'Germany'=>19,'Curaçao'=>40,'Ivory Coast'=>41,'Ecuador'=>2,
                'Netherlands'=>4,'Japan'=>20,'Sweden'=>42,'Tunisia'=>16,
                'Belgium'=>21,'Egypt'=>43,'Iran'=>6,'New Zealand'=>44,
                'Spain'=>17,'Cape Verde'=>45,'Saudi Arabia'=>10,'Uruguay'=>31,
                'France'=>13,'Senegal'=>3,'Iraq'=>46,'Norway'=>47,
                'Argentina'=>9,'Algeria'=>48,'Austria'=>49,'Jordan'=>50,
                'Portugal'=>29,'DR Congo'=>51,'Uzbekistan'=>52,'Colombia'=>53,
                'Panama'=>54,'Ghana'=>30,'England'=>5,'Croatia'=>24,
            ];
        }
        return $map[$name] ?? $this->tbd();
    }

    private function tbd(): int
    {
        static $id = null;
        if ($id === null) {
            $team = Team::firstOrCreate(
                ['name' => '?'],
                ['name_fa' => '؟', 'code' => '?', 'group_name' => null]
            );
            $id = $team->id;
        }
        return $id;
    }

    private function stageKey(string $round): string
    {
        return match(true) {
            str_contains($round, 'Matchday') => 'group',
            $round === 'Round of 32'          => 'round_of_32',
            $round === 'Round of 16'          => 'round_of_16',
            $round === 'Quarter-final'        => 'quarter_final',
            $round === 'Semi-final'           => 'semi_final',
            str_contains($round,'third')      => 'third_place',
            $round === 'Final'                => 'final',
            default                           => 'group',
        };
    }

    private function groupLetter(?string $group): ?string
    {
        if (!$group) return null;
        preg_match('/Group ([A-L])/', $group, $m);
        return $m[1] ?? null;
    }

    public function run(): void
    {
        // Delete all predictions first to avoid FK constraint, then games
        \DB::table('predictions')->delete();
        Game::query()->delete();

        $json = $this->data();

        foreach ($json as $idx => $m) {
            $round = $m['round'];
            $stage = $this->stageKey($round);
            $isTbd = !isset($m['score']);

            // team IDs — handle TBD placeholders like "W74"
            $home = isset($m['team1']) && !preg_match('/^[WL]\d+$/', $m['team1'])
                ? $this->teamId($m['team1']) : $this->tbd();
            $away = isset($m['team2']) && !preg_match('/^[WL]\d+$/', $m['team2'])
                ? $this->teamId($m['team2']) : $this->tbd();

            $scheduledAt = $this->toTehran($m['date'], $m['time']);

            $homeScore = $m['score']['ft'][0] ?? null;
            $awayScore = $m['score']['ft'][1] ?? null;
            $homeScoreHt = $m['score']['ht'][0] ?? null;
            $awayScoreHt = $m['score']['ht'][1] ?? null;
            // extra time / penalty
            $homeScoreFinal = $m['score']['et'][0] ?? null;
            $awayScoreFinal = $m['score']['et'][1] ?? null;

            $status = ($homeScore !== null) ? 'finished' : 'upcoming';

            // Build goals JSON
            $goals = null;
            if (isset($m['goals1']) || isset($m['goals2'])) {
                $goals = json_encode([
                    'home' => $m['goals1'] ?? [],
                    'away' => $m['goals2'] ?? [],
                ]);
            }

            $matchNum = $m['num'] ?? null;

            Game::create([
                'home_team_id'    => $home,
                'away_team_id'    => $away,
                'stage'           => $stage,
                'match_number'    => $matchNum,
                'group_name'      => $this->groupLetter($m['group'] ?? null),
                'scheduled_at'    => $scheduledAt,
                'venue'           => $m['ground'] ?? null,
                'home_score'      => $homeScore,
                'away_score'      => $awayScore,
                'home_score_ht'   => $homeScoreHt,
                'away_score_ht'   => $awayScoreHt,
                'home_score_final'=> $homeScoreFinal,
                'away_score_final'=> $awayScoreFinal,
                'status'          => $status,
                'goals'           => $goals,
            ]);
        }

        echo 'Imported '.count($json).' games'.PHP_EOL;
    }

    private function data(): array
    {
        return json_decode(file_get_contents(database_path('seeders/wc2026.json')), true);
    }
}
