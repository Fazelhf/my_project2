<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportWorldCupGames extends Command
{
    protected $signature   = 'worldcup:import {file : Path to JSON file}';
    protected $description = 'Import or update World Cup 2026 matches from JSON';

    // Maps team name → actual filename in public/flags/
    private array $nameToFile = [
        'USA'                    => 'United States.png',
        'United States'          => 'United States.png',
        'Mexico'                 => 'Mexico.png',
        'Canada'                 => 'Canada.png',
        'Argentina'              => 'Argentina.png',
        'Brazil'                 => 'Brazil.png',
        'France'                 => 'France.png',
        'Germany'                => 'Germany.png',
        'Spain'                  => 'Spain.png',
        'Portugal'               => 'Portugal.png',
        'England'                => 'England.png',
        'Netherlands'            => 'Netherlands.png',
        'Belgium'                => 'Belgium.png',
        'Uruguay'                => 'Uruguay.png',
        'Colombia'               => 'Colombia.png',
        'Ecuador'                => 'Ecuador.png',
        'Paraguay'               => 'Paraguay.png',
        'Panama'                 => 'Panama.png',
        'Haiti'                  => 'Haiti.png',
        'Curaçao'                => 'Curaçao.png',
        'Morocco'                => 'Morocco.png',
        'Senegal'                => 'Senegal.png',
        'Ghana'                  => 'Ghana.png',
        'Egypt'                  => 'Egypt.png',
        'Tunisia'                => 'Tunisia.png',
        'Algeria'                => 'Algeria.png',
        'DR Congo'               => 'DR Congo.png',
        'South Africa'           => 'South Africa.png',
        'Cape Verde'             => 'Cape Verde.png',
        'Japan'                  => 'Japan.png',
        'South Korea'            => 'South Korea.png',
        'Australia'              => 'Australia.png',
        'Saudi Arabia'           => 'Saudi Arabia.png',
        'Iran'                   => 'Iran.png',
        'Qatar'                  => 'Qatar.png',
        'Uzbekistan'             => 'Uzbekistan.png',
        'Jordan'                 => 'Jordan.png',
        'New Zealand'            => 'New Zealand.png',
        'Croatia'                => 'Croatia.png',
        'Switzerland'            => 'Switzerland.png',
        'Norway'                 => 'Norway.png',
        'Sweden'                 => 'Sweden.png',
        'Scotland'               => 'Scotland.png',
        'Austria'                => 'Austria.png',
        'Turkey'                 => 'Turkiye.png',
        'Czech Republic'         => 'Czechia.png',
        'Bosnia and Herzegovina' => 'Bosnia and Herzegovina.png',
        'Bosnia & Herzegovina'   => 'Bosnia and Herzegovina.png',
    ];

    private array $roundToStage = [
        'Matchday 1'         => 'group',
        'Matchday 2'         => 'group',
        'Matchday 3'         => 'group',
        'Matchday 4'         => 'group',
        'Matchday 5'         => 'group',
        'Matchday 6'         => 'group',
        'Matchday 7'         => 'group',
        'Matchday 8'         => 'group',
        'Matchday 9'         => 'group',
        'Matchday 10'        => 'group',
        'Matchday 11'        => 'group',
        'Matchday 12'        => 'group',
        'Matchday 13'        => 'group',
        'Matchday 14'        => 'group',
        'Matchday 15'        => 'group',
        'Matchday 16'        => 'group',
        'Matchday 17'        => 'group',
        'Round of 32'        => 'round_of_32',
        'Round of 16'        => 'round_of_16',
        'Quarter-final'      => 'quarter_final',
        'Semi-final'         => 'semi_final',
        'Match for third place' => 'third_place',
        'Final'              => 'final',
    ];

    public function handle(): int
    {
        $file = $this->argument('file');
        if (! file_exists($file)) {
            $this->error("File not found: $file");
            return 1;
        }

        $raw = json_decode(file_get_contents($file), true);
        if (! $raw) {
            $this->error('Invalid JSON');
            return 1;
        }

        // Support both {name, matches: [...]} and plain array
        $matches = isset($raw['matches']) ? $raw['matches'] : $raw;

        $created = 0;
        $updated = 0;

        foreach ($matches as $match) {
            $round     = $match['round'] ?? 'Matchday 1';
            $stage     = $this->roundToStage[$round] ?? 'group';
            $matchNum  = $match['num'] ?? null;
            $team1Name = $match['team1'] ?? null;
            $team2Name = $match['team2'] ?? null;

            // Skip placeholder teams (e.g. "W74")
            if (! $team1Name || ! $team2Name || str_starts_with($team1Name, 'W') || str_starts_with($team2Name, 'W') || str_starts_with($team1Name, 'L') || str_starts_with($team2Name, 'L')) {
                $this->line("  Skipping placeholder: $team1Name vs $team2Name");
                continue;
            }

            // Parse group: "Group A" → "A"
            $groupRaw  = $match['group'] ?? null;
            $groupName = $groupRaw ? preg_replace('/^Group\s+/i', '', $groupRaw) : null;

            $homeTeam = $this->findOrCreateTeam($team1Name, $groupName);
            $awayTeam = $this->findOrCreateTeam($team2Name, $groupName);

            $scheduledAt = $this->parseTime($match['time'] ?? null, $match['date'] ?? null);

            $score   = $match['score'] ?? null;
            $ftScore = $score['ft'] ?? null;
            $htScore = $score['ht'] ?? null;
            $etScore = $score['et'] ?? null;

            $homeScore = isset($ftScore[0]) ? (int) $ftScore[0] : null;
            $awayScore = isset($ftScore[1]) ? (int) $ftScore[1] : null;
            $homeFinal = isset($etScore[0]) ? (int) $etScore[0] : $homeScore;
            $awayFinal = isset($etScore[1]) ? (int) $etScore[1] : $awayScore;
            $homeHt    = isset($htScore[0]) ? (int) $htScore[0] : null;
            $awayHt    = isset($htScore[1]) ? (int) $htScore[1] : null;
            $status    = ($homeScore !== null) ? 'finished' : 'upcoming';

            // Build normalized goals array: [{name, minute, team: home|away, penalty, owngoal}]
            $goals = [];
            foreach ($match['goals1'] ?? [] as $g) {
                $goals[] = array_merge(['team' => 'home'], $g);
            }
            foreach ($match['goals2'] ?? [] as $g) {
                $goals[] = array_merge(['team' => 'away'], $g);
            }

            // Find existing game
            $game = null;
            if ($matchNum) {
                $game = Game::where('match_number', $matchNum)->first();
            }
            if (! $game) {
                $game = Game::where('home_team_id', $homeTeam->id)
                    ->where('away_team_id', $awayTeam->id)
                    ->first();
            }

            $base = [
                'home_team_id' => $homeTeam->id,
                'away_team_id' => $awayTeam->id,
                'stage'        => $stage,
                'group_name'   => $groupName,
                'venue'        => $match['ground'] ?? null,
                'status'       => $status,
            ];

            if ($matchNum !== null)   $base['match_number']     = $matchNum;
            if ($scheduledAt)         $base['scheduled_at']     = $scheduledAt;
            if ($homeScore !== null)  $base['home_score']       = $homeScore;
            if ($awayScore !== null)  $base['away_score']       = $awayScore;
            if ($homeScore !== null)  $base['home_score_final'] = $homeFinal;
            if ($awayScore !== null)  $base['away_score_final'] = $awayFinal;
            if ($homeHt !== null)     $base['home_score_ht']    = $homeHt;
            if ($awayHt !== null)     $base['away_score_ht']    = $awayHt;
            if ($goals)               $base['goals']            = $goals;

            if ($game) {
                $dirty = [];
                foreach ($base as $k => $v) {
                    if ($v !== null) {
                        $dirty[$k] = $v;
                    }
                }
                $game->update($dirty);
                $updated++;
            } else {
                Game::create($base);
                $created++;
            }
        }

        $this->info("Done. Created: $created, Updated: $updated");
        return 0;
    }

    private function findOrCreateTeam(string $name, ?string $group): Team
    {
        $name = str_replace('Bosnia & Herzegovina', 'Bosnia and Herzegovina', $name);

        $team = Team::where('name', $name)->first();
        if (! $team) {
            $file    = $this->nameToFile[$name] ?? null;
            $flagUrl = $file ? '/flags/' . rawurlencode($file) : null;
            $code = $this->uniqueCode($name);
            $team = Team::create([
                'name'       => $name,
                'code'       => $code,
                'flag_url'   => $flagUrl,
                'group_name' => $group,
            ]);
            $this->line("  Created team: $name");
        } elseif (! $team->flag_url || str_contains($team->flag_url, 'flagcdn.com')) {
            $file = $this->nameToFile[$name] ?? null;
            if ($file) {
                $team->update(['flag_url' => '/flags/' . rawurlencode($file)]);
            }
        }

        return $team;
    }

    private function uniqueCode(string $name): string
    {
        $base = strtoupper(mb_substr($name, 0, 3));
        $code = $base;
        $i    = 1;
        while (Team::where('code', $code)->exists()) {
            $code = substr($base, 0, 2) . $i;
            $i++;
        }
        return $code;
    }

    private function parseTime(?string $time, ?string $date): ?Carbon
    {
        if (! $time || ! $date) {
            return null;
        }
        try {
            // e.g. "13:00 UTC-6"
            if (preg_match('/(\d{2}:\d{2})\s+UTC([+-]\d+)/', $time, $m)) {
                $offset = (int) $m[2];
                return Carbon::parse("{$date} {$m[1]}")->subHours($offset);
            }
            return Carbon::parse("$date $time", 'UTC');
        } catch (\Exception $e) {
            return null;
        }
    }
}
