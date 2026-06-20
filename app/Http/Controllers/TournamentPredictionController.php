<?php
// app/Http/Controllers/TournamentPredictionController.php
namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TournamentPrediction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TournamentPredictionController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        
        // دسته‌بندی تیم‌ها بر اساس گروه‌ها برای نمایش در س thingsکت‌ها
        $teamsByGroup = Team::all()->groupBy('group_name')->toArray();
        
        // دریافت پیش‌بینی قبلی کاربر
        $prediction = TournamentPrediction::where('user_id', $userId)->first();
        
        // لایه امنیتی: فرض می‌کنیم اگر اولین بازی جام شروع شده باشد، کل پیش‌بینی جام قفل می‌شود
        // می‌توانید یک تاریخ ثابت یا فیلد تنظیمات دیتابیس را ملاک قرار دهید
        $tournamentStartTime = Carbon::create(2026, 11, 20, 19, 0, 0); 
        $isLocked = Carbon::now()->greaterThanOrEqualTo($tournamentStartTime) || ($prediction && $prediction->is_locked);

        $userPrediction = [
            'group_winners' => $prediction ? json_decode($prediction->group_winners, true) : $this->getEmptyGroupStructure(),
            'round_of_16' => $prediction ? json_decode($prediction->round_of_16, true) : array_fill(0, 8, 'انتخاب نشده'),
            'quarter_finals' => $prediction ? json_decode($prediction->quarter_finals, true) : array_fill(0, 4, 'انتخاب نشده'),
            'semi_finals' => $prediction ? json_decode($prediction->semi_finals, true) : array_fill(0, 2, 'انتخاب نشده'),
            'final' => $prediction ? json_decode($prediction->final, true) : array_fill(0, 2, 'انتخاب نشده'),
            'champion' => $prediction ? $prediction->champion : 'انتخاب نشده',
            'is_locked' => $isLocked
        ];

        return view('user.tournament-prediction', [
            'groups' => $teamsByGroup,
            'userPrediction' => $userPrediction
        ]);
    }

    public function store(Request $request)
    {
        $tournamentStartTime = Carbon::create(2026, 11, 20, 19, 0, 0);
        if (Carbon::now()->greaterThanOrEqualTo($tournamentStartTime)) {
            return back()->withErrors(['error' => 'مسابقات جام آغاز شده و امکان ثبت پیش‌بینی کل جام وجود ندارد.']);
        }

        // ذخیره داده‌های ارسالی به صورت جی‌سان در دیتابیس
        TournamentPrediction::updateOrCreate(
            ['user_id' => session('user_id')],
            [
                'group_winners' => json_encode($request->input('group_winners')),
                'round_of_16' => json_encode($request->input('round_of_16')),
                'quarter_finals' => json_encode($request->input('quarter_finals')),
                'semi_finals' => json_encode($request->input('semi_finals')),
                'final' => json_encode($request->input('final')),
                'champion' => $request->input('champion'),
                'is_locked' => $request->has('lock_prediction') ? true : false
            ]
        );

        return redirect()->route('dashboard')->with('success', 'پیش‌بینی کل جام شما ذخیره شد.');
    }

    private function getEmptyGroupStructure()
    {
        $structure = [];
        foreach(range('A', 'H') as $group) {
            $structure[$group] = ['انتخاب نشده', 'انتخاب نشده'];
        }
        return $structure;
    }
}