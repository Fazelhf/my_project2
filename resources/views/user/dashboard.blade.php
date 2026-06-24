@extends('layouts.app')

@section('title', 'داشبورد')
@section('page-title', 'داشبورد')

@section('content')

{{-- Greeting --}}
<div class="mb-6">
    <h2 class="text-xl font-bold" style="font-family:'Poppins',sans-serif; color:#F8FAFC;">
        سلام، {{ auth()->user()->name }}
    </h2>
    <p class="text-sm mt-1" style="color:#94A3B8;">خلاصه عملکرد شما در پیش‌بینی جام جهانی ۲۰۲۶</p>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Total Score --}}
    <div class="rounded-2xl p-5 border" style="background-color:#0F172A; border-color:#334155;">
        <p class="text-xs font-medium uppercase tracking-wider mb-3" style="color:#94A3B8;">امتیاز کل</p>
        <p class="text-3xl font-bold" style="color:#22C55E; font-family:'Poppins',sans-serif;">{{ auth()->user()->total_score }}</p>
        <p class="text-xs mt-2" style="color:#475569;">از مجموع پیش‌بینی‌ها</p>
    </div>

    {{-- Rank --}}
    <div class="rounded-2xl p-5 border" style="background-color:#0F172A; border-color:#334155;">
        <p class="text-xs font-medium uppercase tracking-wider mb-3" style="color:#94A3B8;">رتبه</p>
        <p class="text-3xl font-bold" style="color:#F8FAFC; font-family:'Poppins',sans-serif;">#{{ $rank }}</p>
        <p class="text-xs mt-2" style="color:#475569;">از {{ $totalUsers }} نفر</p>
    </div>

    {{-- Predictions --}}
    <div class="rounded-2xl p-5 border" style="background-color:#0F172A; border-color:#334155;">
        <p class="text-xs font-medium uppercase tracking-wider mb-3" style="color:#94A3B8;">پیش‌بینی‌ها</p>
        <p class="text-3xl font-bold" style="color:#F8FAFC; font-family:'Poppins',sans-serif;">{{ $predictionsMade }}</p>
        <p class="text-xs mt-2" style="color:#475569;">{{ $exactPredictions }} نتیجه دقیق</p>
    </div>

    {{-- Accuracy --}}
    <div class="rounded-2xl p-5 border" style="background-color:#0F172A; border-color:#334155;">
        <p class="text-xs font-medium uppercase tracking-wider mb-3" style="color:#94A3B8;">دقت</p>
        <p class="text-3xl font-bold" style="color:#F8FAFC; font-family:'Poppins',sans-serif;">{{ $accuracy }}%</p>
        <p class="text-xs mt-2" style="color:#475569;">{{ $correctPredictions }} از {{ $scoredPredictions }} ارزیابی شده</p>
    </div>
</div>

{{-- Scoring Guide + Recent Predictions --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Recent Predictions --}}
    <div class="lg:col-span-2 rounded-2xl border overflow-hidden" style="background-color:#0F172A; border-color:#334155;">
        <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color:#334155;">
            <h3 class="font-semibold text-sm" style="color:#F8FAFC; font-family:'Poppins',sans-serif;">آخرین پیش‌بینی‌های شما</h3>
            <a href="{{ route('games.index') }}"
               class="text-xs font-medium transition-colors"
               style="color:#22C55E;"
               onmouseover="this.style.color='#86efac';"
               onmouseout="this.style.color='#22C55E';">
                مشاهده همه
            </a>
        </div>

        @if($recentPredictions->isEmpty())
            <div class="px-5 py-10 text-center">
                <p class="text-sm" style="color:#475569;">هنوز پیش‌بینی‌ای ثبت نشده است.</p>
                <a href="{{ route('games.index') }}"
                   class="inline-block mt-3 px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                   style="background-color:#22C55E; color:#020617;"
                   onmouseover="this.style.backgroundColor='#16A34A';"
                   onmouseout="this.style.backgroundColor='#22C55E';">
                    پیش‌بینی بازی‌ها
                </a>
            </div>
        @else
            <div class="divide-y" style="border-color:#334155;">
                @foreach($recentPredictions as $pred)
                    <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color:#F8FAFC;">
                                {{ $pred->game->homeTeam->name }} vs {{ $pred->game->awayTeam->name }}
                            </p>
                            <p class="text-xs mt-0.5" style="color:#94A3B8;">
                                پیش‌بینی: {{ $pred->home_score }}–{{ $pred->away_score }}
                                @if($pred->game->status === 'finished')
                                    &nbsp;|&nbsp; نتیجه: {{ $pred->game->home_score }}–{{ $pred->game->away_score }}
                                @endif
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            @if($pred->points_earned !== null)
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold"
                                      style="{{ $pred->points_earned >= 7 ? 'background-color:#14532d; color:#86efac;'
                                               : ($pred->points_earned >= 5 ? 'background-color:#1e3a5f; color:#93c5fd;'
                                               : ($pred->points_earned >= 2 ? 'background-color:#1e293b; color:#94a3b8;'
                                               : 'background-color:#1e293b; color:#64748b;')) }}">
                                    +{{ $pred->points_earned }}
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium"
                                      style="background-color:#1E293B; color:#475569;">
                                    در انتظار
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Scoring Guide --}}
    <div class="rounded-2xl border p-5" style="background-color:#0F172A; border-color:#334155;">
        <h3 class="font-semibold text-sm mb-4" style="color:#F8FAFC; font-family:'Poppins',sans-serif;">سیستم امتیازدهی</h3>
        <div class="space-y-3">
            @foreach([
                [10, 'نتیجه دقیق',       '#22C55E', '#14532d'],
                [7,  'تفاضل گل یکسان',   '#60a5fa', '#1e3a5f'],
                [5,  'برنده درست',        '#a78bfa', '#2e1065'],
                [2,  'شرکت در پیش‌بینی', '#94A3B8', '#1E293B'],
            ] as [$pts, $label, $color, $bg])
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0"
                          style="background-color:{{ $bg }}; color:{{ $color }};">
                        {{ $pts }}
                    </span>
                    <span class="text-sm" style="color:#94A3B8;">{{ $label }}</span>
                </div>
            @endforeach
        </div>

        <div class="mt-5 pt-4 border-t" style="border-color:#334155;">
            <a href="{{ route('games.index') }}"
               class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-semibold transition-colors cursor-pointer"
               style="background-color:#22C55E; color:#020617;"
               onmouseover="this.style.backgroundColor='#16A34A';"
               onmouseout="this.style.backgroundColor='#22C55E';">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                پیش‌بینی بازی‌ها
            </a>
        </div>
    </div>

</div>

@endsection
