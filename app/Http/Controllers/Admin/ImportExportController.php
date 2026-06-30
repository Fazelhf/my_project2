<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Prediction;
use App\Models\Team;
use App\Models\User;
use App\Services\PredictionScoringService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ImportExportController extends Controller
{
    // ── نگاشت نام تیم JSON → name در DB ─────────────────────────────────────
    private const TEAM_NAME_MAP = [
        'Bosnia & Herzegovina' => 'Bosnia and Herzegovina',
        'DR Congo'             => 'DR Congo',
        'USA'                  => 'United States',
        'Ivory Coast'          => 'Ivory Coast',
        'Cape Verde'           => 'Cape Verde',
        'New Zealand'          => 'New Zealand',
        'South Korea'          => 'South Korea',
        'Saudi Arabia'         => 'Saudi Arabia',
        'South Africa'         => 'South Africa',
    ];

    // ── نگاشت نام round JSON → stage در DB ─────────────────────────────────
    private const STAGE_MAP = [
        'Round of 32'          => 'round_of_32',
        'Round of 16'          => 'round_of_16',
        'Quarter-final'        => 'quarter_final',
        'Semi-final'           => 'semi_final',
        'Match for third place' => 'third_place',
        'Final'                => 'final',
    ];

    public function index(): View
    {
        $stats = [
            'games'       => Game::count(),
            'teams'       => Team::count(),
            'users'       => User::regular()->count(),
            'predictions' => Prediction::count(),
        ];
        $users = User::regular()->orderBy('name')->get();
        return view('admin.import-export.index', compact('stats', 'users'));
    }

    // ════════════════════════════════════════════════════════════════════════
    //  IMPORT
    // ════════════════════════════════════════════════════════════════════════

    public function importGames(Request $request): RedirectResponse
    {
        $request->validate([
            'json_file' => ['required', 'file', 'mimes:json', 'max:5120'],
        ], [
            'json_file.required' => 'فایل JSON الزامی است.',
            'json_file.mimes'    => 'فایل باید از نوع JSON باشد.',
        ]);

        $content = file_get_contents($request->file('json_file')->getRealPath());
        $data    = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || ! isset($data['matches'])) {
            return back()->with('error', 'فایل JSON نامعتبر است.');
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors  = [];

        foreach ($data['matches'] as $index => $match) {
            try {
                $result = $this->processMatch($match, $request->boolean('update_existing'));
                match ($result) {
                    'created' => $created++,
                    'updated' => $updated++,
                    'skipped' => $skipped++,
                };
            } catch (\Throwable $e) {
                $errors[] = "ردیف {$index}: {$e->getMessage()}";
            }
        }

        $msg = "ایمپورت انجام شد — ایجاد: {$created} | بروزرسانی: {$updated} | رد شده: {$skipped}";
        if ($errors) {
            $msg .= ' | خطا: ' . count($errors);
            session()->flash('import_errors', array_slice($errors, 0, 10));
        }

        return back()->with('success', $msg);
    }

    private function processMatch(array $m, bool $updateExisting): string
    {
        // بازی‌های بدون تیم مشخص (مثل Round of 32) رد می‌کنیم
        if (str_contains($m['team1'] ?? '', '/') || str_contains($m['team2'] ?? '', '/')) {
            return 'skipped';
        }
        if (str_starts_with($m['team1'] ?? '', 'W') || str_starts_with($m['team1'] ?? '', 'L') ||
            str_starts_with($m['team2'] ?? '', 'W') || str_starts_with($m['team2'] ?? '', 'L') ||
            str_starts_with($m['team1'] ?? '', '1') || str_starts_with($m['team1'] ?? '', '2') ||
            str_starts_with($m['team1'] ?? '', '3')) {
            return 'skipped';
        }

        $homeTeam = $this->findOrCreateTeam($m['team1']);
        $awayTeam = $this->findOrCreateTeam($m['team2']);
        $stage    = $this->mapStage($m['round']);
        $group    = isset($m['group']) ? trim(str_replace('Group ', '', $m['group'])) : null;

        // تبدیل تاریخ + زمان به UTC
        $scheduledAt = $this->parseDateTime($m['date'], $m['time'] ?? '00:00 UTC');

        // پیدا کردن بازی موجود
        $game = Game::where('home_team_id', $homeTeam->id)
            ->where('away_team_id', $awayTeam->id)
            ->whereDate('scheduled_at', $scheduledAt->toDateString())
            ->first();

        $hasScore  = isset($m['score']['ft']);
        $homeScore = $hasScore ? $m['score']['ft'][0] : null;
        $awayScore = $hasScore ? $m['score']['ft'][1] : null;
        $status    = $hasScore ? 'finished' : 'upcoming';

        $payload = [
            'home_team_id'  => $homeTeam->id,
            'away_team_id'  => $awayTeam->id,
            'stage'         => $stage,
            'group_name'    => $group,
            'scheduled_at'  => $scheduledAt,
            'venue'         => $m['ground'] ?? null,
            'match_number'  => $m['num'] ?? null,
            'home_score'    => $homeScore,
            'away_score'    => $awayScore,
            'status'        => $status,
        ];

        if (! $game) {
            Game::create($payload);
            return 'created';
        }

        if ($updateExisting) {
            $game->update($payload);
            return 'updated';
        }

        // فقط نتیجه رو آپدیت کن اگه بازی وجود داشته باشه
        if ($hasScore && $game->status !== 'finished') {
            $game->update([
                'home_score' => $homeScore,
                'away_score' => $awayScore,
                'status'     => 'finished',
            ]);
            return 'updated';
        }

        return 'skipped';
    }

    private function findOrCreateTeam(string $jsonName): Team
    {
        $name = self::TEAM_NAME_MAP[$jsonName] ?? $jsonName;

        $team = Team::where('name', $name)->first()
            ?? Team::where('name', $jsonName)->first();

        if (! $team) {
            // کد FIFA: ۳ حرف اول
            $words = explode(' ', $name);
            $code  = strtoupper(count($words) >= 2
                ? substr($words[0], 0, 1) . substr($words[1], 0, 1) . substr($words[count($words)-1], 0, 1)
                : substr($name, 0, 3));

            // مطمئن شو کد تکراری نباشه
            $base = $code;
            $i    = 2;
            while (Team::where('code', $code)->exists()) {
                $code = $base . $i++;
            }

            $team = Team::create(['name' => $name, 'code' => $code]);
        }

        return $team;
    }

    private function mapStage(string $round): string
    {
        if (isset(self::STAGE_MAP[$round])) {
            return self::STAGE_MAP[$round];
        }
        // Matchday X → group
        if (str_starts_with($round, 'Matchday')) {
            return 'group';
        }
        return 'group';
    }

    private function parseDateTime(string $date, string $timeWithTz): Carbon
    {
        // مثال: "13:00 UTC-6"  یا "20:00 UTC-4"
        preg_match('/(\d{1,2}:\d{2})\s*UTC([+-]\d+)?/i', $timeWithTz, $m);
        $time   = $m[1] ?? '00:00';
        $offset = isset($m[2]) ? (int)$m[2] : 0;

        // ساعت محلی → UTC
        $dt = Carbon::createFromFormat('Y-m-d H:i', "{$date} {$time}", 'UTC');
        $dt->subHours($offset); // UTC-6 یعنی +6 ساعت به UTC

        return $dt;
    }

    // ════════════════════════════════════════════════════════════════════════
    //  EXPORT
    // ════════════════════════════════════════════════════════════════════════

    public function exportGames(): Response
    {
        $games = Game::with(['homeTeam', 'awayTeam'])->orderBy('scheduled_at')->get();

        $data = $games->map(fn ($g) => [
            'id'         => $g->id,
            'stage'      => $g->stage,
            'group'      => $g->group_name,
            'home_team'  => $g->homeTeam?->name,
            'away_team'  => $g->awayTeam?->name,
            'home_score' => $g->home_score,
            'away_score' => $g->away_score,
            'venue'      => $g->venue,
            'status'     => $g->status,
            'scheduled_at' => $g->scheduled_at?->toISOString(),
        ]);

        return response(json_encode(['games' => $data], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="games-' . now()->format('Y-m-d') . '.json"');
    }

    public function exportUserPredictions(User $user): Response
    {
        $predictions = Prediction::with(['game.homeTeam', 'game.awayTeam'])
            ->where('user_id', $user->id)
            ->get();

        $data = [
            'user'        => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'exported_at' => now()->toISOString(),
            'total_score' => $user->total_score,
            'predictions' => $predictions->map(fn ($p) => [
                'game'        => $p->game?->homeTeam?->name . ' vs ' . $p->game?->awayTeam?->name,
                'scheduled_at' => $p->game?->scheduled_at?->toISOString(),
                'home_score'  => $p->home_score,
                'away_score'  => $p->away_score,
                'real_home'   => $p->game?->home_score,
                'real_away'   => $p->game?->away_score,
                'points'      => $p->points_earned,
            ]),
        ];

        $filename = 'predictions-' . str($user->name)->slug() . '-' . now()->format('Y-m-d') . '.json';

        return response(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    // ── import پیش‌بینی‌های سیستم قدیم ──────────────────────────────────────

    public function importPredictionsPage(): View
    {
        $games = Game::with(['homeTeam', 'awayTeam'])
            ->orderBy('scheduled_at')
            ->get();

        $users = User::regular()->orderBy('name')->get(['id', 'name', 'email']);

        // پیش‌بینی‌های موجود: [user_id][game_id] => {home, away}
        $existing = collect();
        if ($userId = old('user_id', request('user_id'))) {
            $existing = Prediction::where('user_id', $userId)
                ->get()
                ->keyBy('game_id');
        }

        return view('admin.import-export.import-predictions', compact('games', 'users', 'existing'));
    }

    public function importPredictions(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'predictions' => 'required|array',
        ]);

        $user    = User::findOrFail($request->user_id);
        $games   = Game::with('scoringRule')->get()->keyBy('id');
        $scorer  = app(\App\Services\PredictionScoringService::class);
        $created = 0;
        $updated = 0;

        foreach ($request->predictions as $gameId => $row) {
            $homeScore = isset($row['home_score']) && $row['home_score'] !== '' ? (int) $row['home_score'] : null;
            $awayScore = isset($row['away_score']) && $row['away_score'] !== '' ? (int) $row['away_score'] : null;

            // اگه هر دو یا یکی خالی بود رد کن
            if ($homeScore === null || $awayScore === null) {
                continue;
            }

            $game   = $games->get($gameId);
            $points = null;

            if ($game && $game->isScorable()) {
                $rule   = $game->scoringRule;
                $dummy  = new Prediction(['home_score' => $homeScore, 'away_score' => $awayScore]);
                $points = $rule
                    ? $rule->calculatePoints($homeScore, $awayScore, $game->home_score, $game->away_score)
                    : $dummy->calculatePoints($game->home_score, $game->away_score);
            }

            $existing = Prediction::where('user_id', $user->id)->where('game_id', $gameId)->first();

            if ($existing) {
                $existing->update([
                    'home_score'      => $homeScore,
                    'away_score'      => $awayScore,
                    'points_earned'   => $points,
                    'is_admin_edited' => true,
                    'admin_note'      => 'وارد شده از سیستم قدیم',
                ]);
                $updated++;
            } else {
                Prediction::create([
                    'user_id'         => $user->id,
                    'game_id'         => $gameId,
                    'home_score'      => $homeScore,
                    'away_score'      => $awayScore,
                    'points_earned'   => $points,
                    'is_admin_edited' => true,
                    'admin_note'      => 'وارد شده از سیستم قدیم',
                ]);
                $created++;
            }
        }

        if ($created + $updated > 0) {
            $scorer->recalculateUserScores();
        }

        return redirect()->route('admin.import.predictions', ['user_id' => $user->id])
            ->with('success', "پیش‌بینی‌های {$user->name}: {$created} جدید، {$updated} بروزرسانی شد.");
    }

    public function importPredictionsJson(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'json_file' => ['required', 'file', 'mimes:json,txt', 'max:2048'],
        ]);

        $user = User::findOrFail($request->user_id);
        $data = json_decode($request->file('json_file')->get(), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            return back()->withErrors(['json_file' => 'فایل JSON معتبر نیست.'])->withInput();
        }

        $games   = Game::with('scoringRule')->get()->keyBy('id');
        $scorer  = app(\App\Services\PredictionScoringService::class);
        $created = 0;
        $updated = 0;

        foreach ($data as $row) {
            $gameId    = $row['game_id'] ?? null;
            $homeScore = isset($row['home_score']) && $row['home_score'] !== '' ? (int) $row['home_score'] : null;
            $awayScore = isset($row['away_score']) && $row['away_score'] !== '' ? (int) $row['away_score'] : null;

            if (!$gameId || $homeScore === null || $awayScore === null) {
                continue;
            }

            $game   = $games->get($gameId);
            $points = null;

            if (!$game) {
                continue;  // Skip if game doesn't exist
            }

            if ($game->isScorable()) {
                $rule   = $game->scoringRule;
                $dummy  = new Prediction(['home_score' => $homeScore, 'away_score' => $awayScore]);
                $points = $rule
                    ? $rule->calculatePoints($homeScore, $awayScore, $game->home_score, $game->away_score)
                    : $dummy->calculatePoints($game->home_score, $game->away_score);
            }

            $existing = Prediction::where('user_id', $user->id)->where('game_id', $gameId)->first();

            if ($existing) {
                $existing->update([
                    'home_score' => $homeScore, 'away_score' => $awayScore,
                    'points_earned' => $points, 'is_admin_edited' => true,
                    'admin_note' => 'وارد شده از JSON',
                ]);
                $updated++;
            } else {
                Prediction::create([
                    'user_id' => $user->id, 'game_id' => $gameId,
                    'home_score' => $homeScore, 'away_score' => $awayScore,
                    'points_earned' => $points, 'is_admin_edited' => true,
                    'admin_note' => 'وارد شده از JSON',
                ]);
                $created++;
            }
        }

        if ($created + $updated > 0) {
            $scorer->recalculateUserScores();
        }

        return redirect()->route('admin.import.predictions', ['user_id' => $user->id])
            ->with('success', "JSON ایمپورت شد — {$user->name}: {$created} جدید، {$updated} بروزرسانی.");
    }

    public function exportLeaderboard(): Response
    {
        $users = User::regular()->orderByDesc('total_score')->get();

        $data = [
            'exported_at' => now()->toISOString(),
            'leaderboard' => $users->map(fn ($u, $i) => [
                'rank'       => $i + 1,
                'name'       => $u->name,
                'department' => $u->department,
                'score'      => $u->total_score,
            ]),
        ];

        return response(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="leaderboard-' . now()->format('Y-m-d') . '.json"');
    }
}
