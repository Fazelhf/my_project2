@extends('layouts.app')

@section('title', 'داشبورد')

@section('content')

@php
    $user = auth()->user();
    $myPreds = $predictions ?? collect();
    $upcomingGames = $upcomingGames ?? collect();
    $rank = $rank ?? '—';
    $accuracy = $accuracy ?? 0;
    $totalPreds = $totalPredictions ?? 0;
@endphp

{{-- ── Hero Stats Row ──────────────────────────────────────── --}}
<section class="grid grid-cols-1 md:grid-cols-12 gap-5 mb-6">

    {{-- امتیاز کل --}}
    <div class="md:col-span-4 liquid-glass bento-card rounded-3xl p-8 flex flex-col justify-between h-[200px] reveal animate-slide-up stagger-1">
        <div>
            <p class="text-sm mb-1" style="color:rgba(185,203,185,0.6);">امتیاز کل</p>
            <h2 class="text-5xl font-black font-heading" style="color:#00e476;">{{ $user->total_score ?? 0 }}</h2>
        </div>
        <div class="space-y-2">
            <div class="flex items-center gap-2 text-sm font-bold" style="color:#00e476;">
                <span class="material-symbols-outlined text-base">trending_up</span>
                <span>خوش آمدی، {{ $user->name }}</span>
            </div>
            <div class="w-full h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.1);">
                <div class="h-full rounded-full shadow-[0_0_10px_#00e476]" style="width:{{ min(($user->total_score ?? 0) / 20, 100) }}%;background:#00e476;"></div>
            </div>
        </div>
    </div>

    {{-- رتبه --}}
    <div class="md:col-span-4 liquid-glass bento-card rounded-3xl p-8 flex flex-col justify-between h-[200px] reveal animate-slide-up stagger-2" style="border-right:4px solid #00e476;">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm mb-1" style="color:rgba(185,203,185,0.6);">رتبه جهانی</p>
                <h2 class="text-5xl font-black font-heading text-white">#{{ $rank }}</h2>
            </div>
            <div class="p-3 rounded-2xl" style="background:rgba(0,228,118,0.1);">
                <span class="material-symbols-outlined text-3xl" style="color:#00e476;">leaderboard</span>
            </div>
        </div>
        <div class="flex gap-1 mt-2">
            @for($i = 0; $i < 4; $i++)
            <div class="h-1 flex-1 rounded-full" style="background:{{ $i < 3 ? '#00e476' : 'rgba(0,228,118,0.2)' }};"></div>
            @endfor
        </div>
    </div>

    {{-- دقت پیش‌بینی --}}
    <div class="md:col-span-4 liquid-glass bento-card rounded-3xl p-6 flex items-center gap-6 h-[200px] reveal animate-slide-up stagger-3">
        <div class="relative flex items-center justify-center flex-shrink-0">
            <svg class="w-28 h-28" viewBox="0 0 120 120">
                <circle cx="60" cy="60" r="50" fill="transparent" stroke="rgba(255,255,255,0.06)" stroke-width="8"/>
                <circle cx="60" cy="60" r="50" fill="transparent" stroke="#00e476" stroke-width="8"
                    stroke-dasharray="314.15"
                    stroke-dashoffset="{{ 314.15 - (314.15 * min($accuracy, 100) / 100) }}"
                    stroke-linecap="round"
                    style="transform:rotate(-90deg);transform-origin:50% 50%;"/>
            </svg>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-2xl font-black font-heading" style="color:#00e476;">{{ $accuracy }}%</span>
            </div>
        </div>
        <div>
            <h3 class="text-lg font-bold font-heading text-white mb-1">دقت پیش‌بینی</h3>
            <p class="text-sm" style="color:rgba(221,226,240,0.6);">{{ $totalPreds }} پیش‌بینی ثبت‌شده</p>
            <div class="mt-3 flex gap-1 items-center text-xs" style="color:#00e476;">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background:#00e476;"></span>
                {{ $user->department ?? 'کاربر فعال' }}
            </div>
        </div>
    </div>

</section>

{{-- ── Main Content + Sidebar ──────────────────────────────── --}}
<section class="grid grid-cols-1 lg:grid-cols-12 gap-5">

    <div class="lg:col-span-8 space-y-5">

        {{-- بازی‌های پیش رو --}}
        @if($upcomingGames->isNotEmpty())
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold font-heading flex items-center gap-2 text-white">
                    <span class="material-symbols-outlined" style="color:#00e476;">schedule</span>
                    بازی‌های پیش‌رو بدون پیش‌بینی
                </h2>
                <a href="{{ route('games.index') }}" class="text-sm font-bold" style="color:#00e476;">مشاهده همه</a>
            </div>
            <div class="space-y-4">
                @foreach($upcomingGames->take(3) as $game)
                <div class="liquid-glass card-glow rounded-2xl p-5" style="border-right:4px solid #00e476;">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="flex-1 flex items-center justify-center gap-6 w-full">
                            <div class="text-center">
                                <div class="w-14 h-14 rounded-full mx-auto mb-2 flex items-center justify-center overflow-hidden"
                                     style="background:rgba(255,255,255,0.06);border:2px solid rgba(255,255,255,0.1);">
                                    @if($game->homeTeam->flag_url)
                                        <img src="{{ $game->homeTeam->flag_url }}" alt="{{ $game->homeTeam->code }}" class="w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                        <span class="text-sm font-black font-heading text-white hidden w-full h-full items-center justify-center">{{ $game->homeTeam->code }}</span>
                                    @else
                                        <span class="text-sm font-black font-heading text-white">{{ $game->homeTeam->code }}</span>
                                    @endif
                                </div>
                                <p class="text-sm font-bold text-white">{{ $game->homeTeam->name_fa ?? $game->homeTeam->name }}</p>
                            </div>
                            <div class="flex flex-col items-center gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs px-2 py-0.5 rounded-full font-bold font-mono"
                                          style="background:rgba(0,228,118,0.1);color:#00e476;">
                                        {{ $game->scheduled_at?->format('j M — H:i') }}
                                    </span>
                                </div>
                                <span class="text-white/30 text-lg font-bold">vs</span>
                            </div>
                            <div class="text-center">
                                <div class="w-14 h-14 rounded-full mx-auto mb-2 flex items-center justify-center overflow-hidden"
                                     style="background:rgba(255,255,255,0.06);border:2px solid rgba(255,255,255,0.1);">
                                    @if($game->awayTeam->flag_url)
                                        <img src="{{ $game->awayTeam->flag_url }}" alt="{{ $game->awayTeam->code }}" class="w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                        <span class="text-sm font-black font-heading text-white hidden w-full h-full items-center justify-center">{{ $game->awayTeam->code }}</span>
                                    @else
                                        <span class="text-sm font-black font-heading text-white">{{ $game->awayTeam->code }}</span>
                                    @endif
                                </div>
                                <p class="text-sm font-bold text-white">{{ $game->awayTeam->name_fa ?? $game->awayTeam->name }}</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('games.index') }}"
                               class="block text-center px-6 py-2.5 rounded-xl font-bold text-sm transition-all"
                               style="background:#00e476;color:#003919;"
                               onmouseover="this.style.boxShadow='0 0 20px rgba(0,228,118,0.4)'"
                               onmouseout="this.style.boxShadow=''">
                                ثبت پیش‌بینی
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- پیش‌بینی‌های اخیر --}}
        @if($myPreds->isNotEmpty())
        <div>
            <h2 class="text-lg font-bold font-heading flex items-center gap-2 text-white mb-4">
                <span class="material-symbols-outlined" style="color:#00e476;">history</span>
                پیش‌بینی‌های اخیر
            </h2>
            <div class="liquid-glass rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);">
                            <th class="px-4 py-3 text-right text-xs font-bold" style="color:rgba(185,203,185,0.7);">بازی</th>
                            <th class="px-4 py-3 text-center text-xs font-bold" style="color:rgba(185,203,185,0.7);">پیش‌بینی</th>
                            <th class="px-4 py-3 text-center text-xs font-bold" style="color:rgba(185,203,185,0.7);">نتیجه</th>
                            <th class="px-4 py-3 text-center text-xs font-bold" style="color:rgba(185,203,185,0.7);">امتیاز</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myPreds->take(8) as $pred)
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.04);"
                            onmouseover="this.style.background='rgba(0,228,118,0.03)'"
                            onmouseout="this.style.background=''">
                            <td class="px-4 py-3 font-semibold text-white text-xs">
                                {{ $pred->game->homeTeam->code }} <span style="color:rgba(255,255,255,0.3);">vs</span> {{ $pred->game->awayTeam->code }}
                            </td>
                            <td class="px-4 py-3 text-center font-heading font-black text-white">
                                {{ $pred->home_score }}–{{ $pred->away_score }}
                            </td>
                            <td class="px-4 py-3 text-center font-heading font-black" style="color:rgba(221,226,240,0.6);">
                                @if($pred->game->status === 'finished')
                                    {{ $pred->game->home_score }}–{{ $pred->game->away_score }}
                                @else
                                    <span style="color:rgba(255,255,255,0.2);">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($pred->points_earned !== null)
                                    @php
                                        $pc = $pred->points_earned >= 7
                                            ? 'rgba(0,228,118,0.15);color:#00e476;border:1px solid rgba(0,228,118,0.3)'
                                            : ($pred->points_earned >= 5
                                                ? 'rgba(77,159,255,0.15);color:#4D9FFF;border:1px solid rgba(77,159,255,0.3)'
                                                : ($pred->points_earned >= 2
                                                    ? 'rgba(255,255,255,0.08);color:#b9cbb9;border:1px solid rgba(255,255,255,0.12)'
                                                    : 'rgba(255,90,90,0.15);color:#FF8A8A;border:1px solid rgba(255,90,90,0.3)'));
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold" style="background:{{ $pc }}">
                                        +{{ $pred->points_earned }}
                                    </span>
                                @else
                                    <span style="color:rgba(255,255,255,0.2);font-size:12px;">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($myPreds->isEmpty() && $upcomingGames->isEmpty())
        <div class="liquid-glass rounded-2xl p-16 text-center">
            <span class="material-symbols-outlined text-5xl mb-4 block" style="color:rgba(0,228,118,0.4);">sports_soccer</span>
            <p class="text-sm mb-5" style="color:rgba(185,203,185,0.7);">هنوز پیش‌بینی‌ای ثبت نشده</p>
            <a href="{{ route('games.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold transition-all"
               style="background:#00e476;color:#003919;"
               onmouseover="this.style.boxShadow='0 0 25px rgba(0,228,118,0.4)'"
               onmouseout="this.style.boxShadow=''">
                <span class="material-symbols-outlined text-base">add_circle</span>
                شروع پیش‌بینی
            </a>
        </div>
        @endif

    </div>

    {{-- Sidebar --}}
    <div class="lg:col-span-4 space-y-5">

        {{-- آمار سریع --}}
        <div class="liquid-glass rounded-3xl p-6 space-y-5">
            <h3 class="font-bold font-heading text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-base" style="color:#00e476;">query_stats</span>
                آمار عملکرد
            </h3>
            <div class="grid grid-cols-2 gap-3">
                <div class="text-center p-4 rounded-2xl" style="background:rgba(255,255,255,0.04);">
                    <p class="text-2xl font-black font-heading" style="color:#00e476;">{{ $totalPreds }}</p>
                    <p class="text-xs mt-1" style="color:rgba(185,203,185,0.6);">کل پیش‌بینی‌ها</p>
                </div>
                <div class="text-center p-4 rounded-2xl" style="background:rgba(255,255,255,0.04);">
                    <p class="text-2xl font-black font-heading text-white">
                        {{ $myPreds->where('points_earned', '>', 0)->count() }}
                    </p>
                    <p class="text-xs mt-1" style="color:rgba(185,203,185,0.6);">پیش‌بینی درست</p>
                </div>
            </div>
            <a href="{{ route('leaderboard') }}"
               class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl font-bold text-sm transition-all"
               style="background:rgba(0,228,118,0.08);color:#00e476;border:1px solid rgba(0,228,118,0.2);"
               onmouseover="this.style.background='rgba(0,228,118,0.15)'"
               onmouseout="this.style.background='rgba(0,228,118,0.08)'">
                <span class="material-symbols-outlined text-base">leaderboard</span>
                مشاهده جدول رده‌بندی
            </a>
        </div>

        {{-- لینک سریع به پیش‌بینی --}}
        <div class="liquid-glass card-glow rounded-3xl overflow-hidden cursor-pointer group">
            <div class="p-6 space-y-3"
                 style="background:linear-gradient(135deg,rgba(0,228,118,0.08),rgba(0,26,61,0.5));">
                <span class="material-symbols-outlined text-3xl" style="color:#00e476;">emoji_events</span>
                <h4 class="font-heading font-bold text-white text-lg">قهرمان را پیش‌بینی کنید!</h4>
                <p class="text-sm" style="color:rgba(221,226,240,0.6);">جایزه ویژه امتیازی در انتظار شماست</p>
                <a href="{{ route('games.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold mt-2"
                   style="background:#00e476;color:#003919;">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    شروع کنید
                </a>
            </div>
        </div>

    </div>

</section>

@endsection
