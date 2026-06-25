@extends('layouts.app')

@section('title', 'داشبورد')

@section('content')

@php
    $user = auth()->user();
    $myPreds = $predictions ?? collect();
    $upcomingGames = $upcoming ?? collect();
    $rank = $rank ?? '—';
    $accuracy = $accuracy ?? 0;
    $totalPreds = $totalPredictions ?? 0;
@endphp

{{-- Hero --}}
<div class="mb-6 animate-slide-up">
    <div class="glass-card rounded-2xl p-6 bento-card relative overflow-hidden"
         style="background:linear-gradient(135deg,rgba(245,166,35,0.05),rgba(167,139,250,0.05));border-color:rgba(245,166,35,0.15);">
        <div class="absolute top-0 left-0 w-64 h-64 rounded-full pointer-events-none"
             style="background:radial-gradient(circle,rgba(245,166,35,0.07),transparent 70%);transform:translate(-30%,-30%);"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-brand-muted text-xs mb-1">خوش آمدی</p>
                <h1 class="text-xl font-black font-heading text-brand-text mb-2">{{ $user->name }}</h1>
                @if($user->department)
                    <span class="badge badge-purple text-xs">{{ $user->department }}</span>
                @endif
            </div>
            <div class="text-left">
                <p class="text-brand-muted text-xs mb-1">امتیاز</p>
                <p class="text-4xl font-black font-heading gradient-text-gold">{{ $user->total_score ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4 pt-4" style="border-top:1px solid rgba(255,255,255,0.05);">
            <p class="text-xs text-brand-subtle">
                <span style="color:#F5A623;" class="font-semibold">FIFA World Cup 2026</span>
                — کانادا، آمریکا، مکزیک · ۱۱ ژوئن – ۱۹ ژوئیه ۲۰۲۶
            </p>
        </div>
    </div>
</div>

{{-- 4 Stat Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6" style="animation:slide-up .5s .05s cubic-bezier(.16,1,.3,1) both;">
    @php
        $stats = [
            ['val' => $user->total_score ?? 0, 'label' => 'امتیاز کل',  'color' => '#F5A623', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
            ['val' => $rank,                    'label' => 'رتبه',        'color' => '#A78BFA', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
            ['val' => $totalPreds,              'label' => 'پیش‌بینی',   'color' => '#00E5A0', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2'],
            ['val' => $accuracy.'%',            'label' => 'دقت',         'color' => '#4D9FFF', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
        ];
    @endphp
    @foreach($stats as $s)
    <div class="glass-card rounded-2xl p-4 bento-card text-center cursor-default">
        <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center"
             style="background:linear-gradient(135deg,{{ $s['color'] }}33,{{ $s['color'] }}0a);border:1px solid {{ $s['color'] }}55;">
            <svg class="w-5 h-5" style="color:{{ $s['color'] }};" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/>
            </svg>
        </div>
        <p class="text-2xl font-black font-heading" style="color:{{ $s['color'] }};">{{ $s['val'] }}</p>
        <p class="text-xs text-brand-muted mt-0.5">{{ $s['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Upcoming without prediction --}}
@if($upcomingGames->isNotEmpty())
<div class="mb-6" style="animation:slide-up .5s .1s cubic-bezier(.16,1,.3,1) both;">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-1 h-5 rounded-full" style="background:linear-gradient(180deg,#F5A623,#A78BFA);"></div>
        <h2 class="font-black text-sm font-heading text-brand-text">بازی‌های پیش رو بدون پیش‌بینی</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($upcomingGames->take(6) as $game)
        <a href="{{ route('games.index') }}" class="glass-card rounded-2xl p-4 bento-card block" style="border-color:rgba(245,166,35,0.18);">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-brand-muted">{{ $game->scheduled_at?->format('j M — H:i') }}</span>
                <span class="badge badge-gold text-xs">باز</span>
            </div>
            <div class="flex items-center justify-between gap-2">
                <div class="flex-1 text-center">
                    <div class="w-10 h-10 rounded-xl mx-auto mb-1 flex items-center justify-center text-xs font-black font-heading"
                         style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#F0F4FF;">
                        {{ $game->homeTeam->code }}
                    </div>
                    <p class="text-xs font-bold text-brand-text truncate">{{ $game->homeTeam->name_fa ?? $game->homeTeam->name }}</p>
                </div>
                <span class="text-brand-subtle text-xs font-bold flex-shrink-0">vs</span>
                <div class="flex-1 text-center">
                    <div class="w-10 h-10 rounded-xl mx-auto mb-1 flex items-center justify-center text-xs font-black font-heading"
                         style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#F0F4FF;">
                        {{ $game->awayTeam->code }}
                    </div>
                    <p class="text-xs font-bold text-brand-text truncate">{{ $game->awayTeam->name_fa ?? $game->awayTeam->name }}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- Recent predictions --}}
@if($myPreds->isNotEmpty())
<div style="animation:slide-up .5s .15s cubic-bezier(.16,1,.3,1) both;">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-1 h-5 rounded-full" style="background:linear-gradient(180deg,#00E5A0,#4D9FFF);"></div>
        <h2 class="font-black text-sm font-heading text-brand-text">پیش‌بینی‌های اخیر</h2>
    </div>
    <div class="glass rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr style="border-bottom:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);">
                    <th class="px-4 py-3 text-right text-xs font-bold text-brand-subtle">بازی</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-brand-subtle">پیش‌بینی</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-brand-subtle">نتیجه</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-brand-subtle">امتیاز</th>
                </tr>
            </thead>
            <tbody>
                @foreach($myPreds->take(8) as $pred)
                <tr style="border-bottom:1px solid rgba(255,255,255,0.04);"
                    onmouseover="this.style.background='rgba(255,255,255,0.03)'"
                    onmouseout="this.style.background=''">
                    <td class="px-4 py-3 font-semibold text-brand-text text-xs">
                        {{ $pred->game->homeTeam->code }} <span class="text-brand-subtle">vs</span> {{ $pred->game->awayTeam->code }}
                    </td>
                    <td class="px-4 py-3 text-center font-heading font-black text-brand-text">
                        {{ $pred->home_score }}–{{ $pred->away_score }}
                    </td>
                    <td class="px-4 py-3 text-center font-heading font-black text-brand-muted">
                        @if($pred->game->status === 'finished')
                            {{ $pred->game->home_score }}–{{ $pred->game->away_score }}
                        @else
                            <span class="text-brand-subtle">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($pred->points_earned !== null)
                            @php $pc = $pred->points_earned >= 7 ? 'badge-green' : ($pred->points_earned >= 5 ? 'badge-blue' : ($pred->points_earned >= 2 ? 'badge-gray' : 'badge-red')); @endphp
                            <span class="badge {{ $pc }}">+{{ $pred->points_earned }}</span>
                        @else
                            <span class="text-brand-subtle text-xs">—</span>
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
<div class="glass-card rounded-2xl p-16 text-center bento-card">
    <p class="text-brand-muted text-sm mb-4">هنوز پیش‌بینی‌ای ثبت نشده</p>
    <a href="{{ route('games.index') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold cursor-pointer transition-all"
       style="background:linear-gradient(135deg,#D4890A,#F5A623);color:#0a0a0a;"
       onmouseover="this.style.boxShadow='0 0 25px rgba(245,166,35,0.4)'"
       onmouseout="this.style.boxShadow=''">
        شروع پیش‌بینی
    </a>
</div>
@endif

@endsection
