@extends('layouts.app')

@section('title', 'پیش‌بینی بازی‌ها')

@section('content')

@php
$stageLabels = [
    'group'         => ['label' => 'مرحله گروهی',      'icon' => 'G',  'color' => '#4D9FFF'],
    'round_of_32'   => ['label' => 'یک‌شانزدهم نهایی', 'icon' => '32', 'color' => '#A78BFA'],
    'round_of_16'   => ['label' => 'یک‌هشتم نهایی',    'icon' => '16', 'color' => '#A78BFA'],
    'quarter_final' => ['label' => 'ربع نهایی',         'icon' => 'QF', 'color' => '#00E5A0'],
    'semi_final'    => ['label' => 'نیمه نهایی',        'icon' => 'SF', 'color' => '#F5A623'],
    'third_place'   => ['label' => 'رده‌بندی سوم',     'icon' => '3P', 'color' => '#FF8A8A'],
    'final'         => ['label' => 'فینال',             'icon' => 'F',  'color' => '#F5A623'],
];
@endphp

@if($games->isEmpty())
<div class="glass rounded-2xl p-16 text-center">
    <p class="text-sm text-brand-muted">هیچ بازی‌ای ثبت نشده است.</p>
</div>
@endif

@foreach($stageLabels as $stage => $info)
@if($games->has($stage))
@php $stageGames = $games[$stage]; @endphp
<div class="mb-10 animate-slide-up">

    <div class="flex items-center gap-3 mb-4">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black font-heading flex-shrink-0"
             style="background:linear-gradient(135deg,{{ $info['color'] }}22,{{ $info['color'] }}0a);border:1px solid {{ $info['color'] }}40;color:{{ $info['color'] }};">
            {{ $info['icon'] }}
        </div>
        <h2 class="text-base font-black font-heading text-brand-text">{{ $info['label'] }}</h2>
        <div class="flex-1 h-px" style="background:linear-gradient(90deg,rgba(255,255,255,0.08),transparent);"></div>
        <span class="text-xs text-brand-subtle">{{ $stageGames->count() }} بازی</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
        @foreach($stageGames as $game)
        @php
            $pred   = $game->predictions->first();
            $locked = $game->isPredictionLocked();
            $done   = $game->status === 'finished';
            if ($done) {
                $bc = 'rgba(100,116,139,0.3)'; $hb = 'rgba(100,116,139,0.04)';
            } elseif ($pred) {
                $bc = 'rgba(0,229,160,0.3)'; $hb = 'rgba(0,229,160,0.04)';
            } elseif ($locked) {
                $bc = 'rgba(255,90,90,0.25)'; $hb = 'rgba(255,90,90,0.04)';
            } else {
                $bc = 'rgba(245,166,35,0.25)'; $hb = 'rgba(245,166,35,0.04)';
            }
        @endphp

        <div class="glass-card rounded-2xl overflow-hidden bento-card" style="border-color:{{ $bc }};">

            {{-- Card header --}}
            <div class="px-4 py-2.5 flex items-center justify-between" style="background:{{ $hb }};border-bottom:1px solid rgba(255,255,255,0.05);">
                <span class="text-xs text-brand-muted">{{ $game->scheduled_at?->format('j M Y — H:i') }}</span>
                @if($done)
                    <span class="badge badge-gray text-xs">پایان یافته</span>
                @elseif($locked)
                    <span class="badge badge-red text-xs">قفل شده</span>
                @elseif($pred)
                    <span class="badge badge-green text-xs">ثبت شده</span>
                @else
                    <span class="badge badge-gold text-xs" style="animation:pulse 2s ease-in-out infinite;">باز</span>
                @endif
            </div>

            {{-- Teams --}}
            <div class="px-5 py-4">
                <div class="flex items-center justify-between gap-2 mb-4">

                    <div class="flex-1 text-center">
                        <div class="w-11 h-11 rounded-xl mx-auto mb-1.5 flex items-center justify-center text-xs font-black font-heading"
                             style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#F0F4FF;">
                            {{ $game->homeTeam->code }}
                        </div>
                        <p class="text-xs font-bold text-brand-text leading-tight">{{ $game->homeTeam->name_fa ?? $game->homeTeam->name }}</p>
                    </div>

                    <div class="flex flex-col items-center gap-1 px-2">
                        @if($done)
                            <div class="px-3 py-1.5 rounded-xl" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
                                <span class="font-black text-lg font-heading text-brand-text leading-none">
                                    {{ $game->home_score }}<span class="text-brand-subtle mx-1 text-sm">–</span>{{ $game->away_score }}
                                </span>
                            </div>
                            <span class="text-xs text-brand-subtle font-semibold">نهایی</span>
                        @else
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                                 style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                                <span class="text-xs font-bold text-brand-subtle">vs</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 text-center">
                        <div class="w-11 h-11 rounded-xl mx-auto mb-1.5 flex items-center justify-center text-xs font-black font-heading"
                             style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#F0F4FF;">
                            {{ $game->awayTeam->code }}
                        </div>
                        <p class="text-xs font-bold text-brand-text leading-tight">{{ $game->awayTeam->name_fa ?? $game->awayTeam->name }}</p>
                    </div>
                </div>

                {{-- Venue --}}
                @if($game->venue)
                <p class="text-xs text-brand-subtle text-center mb-3 truncate">{{ $game->venue }}</p>
                @endif

                {{-- Prediction area --}}
                <div style="border-top:1px solid rgba(255,255,255,0.06);padding-top:12px;">
                    @if($pred)
                    <div class="flex items-center gap-2">
                        <div class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-xl"
                             style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                            <span class="text-xs text-brand-muted">پیش‌بینی:</span>
                            <span class="font-black text-sm font-heading text-brand-text">{{ $pred->home_score }}–{{ $pred->away_score }}</span>
                            @if($pred->points_earned !== null)
                                @php $pc = $pred->points_earned >= 7 ? 'badge-green' : ($pred->points_earned >= 5 ? 'badge-blue' : ($pred->points_earned >= 2 ? 'badge-gray' : 'badge-red')); @endphp
                                <span class="badge {{ $pc }} text-xs">+{{ $pred->points_earned }}</span>
                            @endif
                        </div>
                        @if(!$locked && !$done)
                        <form method="POST" action="{{ route('games.predict.update', $game) }}" class="flex items-center gap-1 flex-shrink-0">
                            @csrf @method('PUT')
                            <input type="number" name="home_score" value="{{ $pred->home_score }}" min="0" max="99"
                                   class="w-10 py-2 rounded-lg text-center text-sm font-black font-heading text-brand-text"
                                   style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
                            <span class="text-brand-subtle text-xs">–</span>
                            <input type="number" name="away_score" value="{{ $pred->away_score }}" min="0" max="99"
                                   class="w-10 py-2 rounded-lg text-center text-sm font-black font-heading text-brand-text"
                                   style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
                            <button type="submit" class="px-2.5 py-2 rounded-lg text-xs font-black cursor-pointer transition-all"
                                    style="background:rgba(245,166,35,0.15);border:1px solid rgba(245,166,35,0.3);color:#F5A623;"
                                    onmouseover="this.style.background='rgba(245,166,35,0.25)'"
                                    onmouseout="this.style.background='rgba(245,166,35,0.15)'">ویرایش</button>
                        </form>
                        @endif
                    </div>

                    @elseif(!$locked && !$done)
                    <form method="POST" action="{{ route('games.predict', $game) }}" class="flex items-center gap-2">
                        @csrf
                        <input type="number" name="home_score" value="0" min="0" max="99"
                               class="flex-1 py-2.5 rounded-xl text-center text-sm font-black font-heading text-brand-text"
                               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
                        <span class="font-bold text-brand-subtle">–</span>
                        <input type="number" name="away_score" value="0" min="0" max="99"
                               class="flex-1 py-2.5 rounded-xl text-center text-sm font-black font-heading text-brand-text"
                               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
                        <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-black cursor-pointer transition-all flex-shrink-0"
                                style="background:linear-gradient(135deg,#D4890A,#F5A623);color:#0a0a0a;"
                                onmouseover="this.style.boxShadow='0 0 20px rgba(245,166,35,0.4)'"
                                onmouseout="this.style.boxShadow=''">ثبت</button>
                    </form>

                    @else
                    <p class="text-xs text-center py-2 text-brand-subtle font-semibold">
                        {{ $locked ? 'زمان پیش‌بینی پایان یافته' : 'بدون پیش‌بینی' }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endforeach

@endsection
