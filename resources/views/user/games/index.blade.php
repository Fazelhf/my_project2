@extends('layouts.app')

@section('title', 'پیش‌بینی بازی‌ها')

@section('content')

@php
$stageLabels = [
    'group'         => ['label' => 'مرحله گروهی',       'color' => '#4D9FFF'],
    'round_of_32'   => ['label' => 'یک‌شانزدهم نهایی',  'color' => '#A78BFA'],
    'round_of_16'   => ['label' => 'یک‌هشتم نهایی',     'color' => '#A78BFA'],
    'quarter_final' => ['label' => 'ربع نهایی',          'color' => '#00e476'],
    'semi_final'    => ['label' => 'نیمه نهایی',         'color' => '#F59E0B'],
    'third_place'   => ['label' => 'رده‌بندی سوم',      'color' => '#FF8A8A'],
    'final'         => ['label' => 'فینال',              'color' => '#F59E0B'],
];
@endphp

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-black font-heading text-white flex items-center gap-3">
        <span class="material-symbols-outlined text-3xl" style="color:#00e476;">sports_soccer</span>
        لیست بازی‌ها و پیش‌بینی
    </h1>
</div>

@if($games->isEmpty())
<div class="liquid-glass rounded-2xl p-16 text-center">
    <span class="material-symbols-outlined text-5xl mb-4 block" style="color:rgba(0,228,118,0.3);">sports_soccer</span>
    <p class="text-sm" style="color:rgba(185,203,185,0.6);">هیچ بازی‌ای ثبت نشده است.</p>
</div>
@endif

@foreach($stageLabels as $stage => $info)
@if($games->has($stage))
@php $stageGames = $games[$stage]; @endphp

<div class="mb-10">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-3 h-3 rounded-full flex-shrink-0" style="background:{{ $info['color'] }};box-shadow:0 0 8px {{ $info['color'] }};"></div>
        <h2 class="text-base font-black font-heading text-white">{{ $info['label'] }}</h2>
        <div class="flex-1 h-px" style="background:linear-gradient(90deg,{{ $info['color'] }}30,transparent);"></div>
        <span class="text-xs font-mono" style="color:rgba(185,203,185,0.5);">{{ $stageGames->count() }} بازی</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($stageGames as $game)
        @php
            $pred   = $game->predictions->first();
            $locked = $game->isPredictionLocked();
            $done   = $game->status === 'finished';
            if ($done) {
                $borderColor = 'rgba(100,116,139,0.4)';
                $statusLabel = 'پایان یافته';
                $statusStyle = 'background:rgba(255,255,255,0.08);color:rgba(185,203,185,0.7);';
            } elseif ($pred) {
                $borderColor = 'rgba(0,228,118,0.35)';
                $statusLabel = 'ثبت شده';
                $statusStyle = 'background:rgba(0,228,118,0.1);color:#00e476;border:1px solid rgba(0,228,118,0.3);';
            } elseif ($locked) {
                $borderColor = 'rgba(255,90,90,0.3)';
                $statusLabel = 'قفل شده';
                $statusStyle = 'background:rgba(255,90,90,0.1);color:#FF8A8A;border:1px solid rgba(255,90,90,0.25);';
            } else {
                $borderColor = 'rgba(0,228,118,0.25)';
                $statusLabel = 'باز';
                $statusStyle = 'background:rgba(0,228,118,0.1);color:#00e476;border:1px solid rgba(0,228,118,0.3);';
            }
        @endphp

        <div class="liquid-glass rounded-2xl overflow-hidden" style="border-color:{{ $borderColor }};">

            {{-- Card header --}}
            <div class="px-4 py-2.5 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);">
                <span class="text-xs font-mono" style="color:rgba(185,203,185,0.6);">{{ $game->scheduled_at?->format('j M Y — H:i') }}</span>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold" style="{{ $statusStyle }}">{{ $statusLabel }}</span>
            </div>

            {{-- Teams --}}
            <div class="px-5 py-4">
                <div class="flex items-center justify-between gap-2 mb-4">

                    <div class="flex-1 text-center">
                        <div class="w-12 h-12 rounded-full mx-auto mb-1.5 flex items-center justify-center text-xs font-black font-heading text-white"
                             style="background:rgba(255,255,255,0.06);border:2px solid rgba(255,255,255,0.1);">
                            {{ $game->homeTeam->code }}
                        </div>
                        <p class="text-xs font-bold text-white leading-tight">{{ $game->homeTeam->name_fa ?? $game->homeTeam->name }}</p>
                    </div>

                    <div class="flex flex-col items-center gap-1 px-2">
                        @if($done)
                            <div class="px-4 py-2 rounded-xl" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
                                <span class="font-black text-xl font-heading text-white">
                                    {{ $game->home_score }}<span style="color:rgba(255,255,255,0.3);" class="mx-1 text-sm">–</span>{{ $game->away_score }}
                                </span>
                            </div>
                            <span class="text-xs font-mono" style="color:rgba(185,203,185,0.5);">نهایی</span>
                        @else
                            <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                 style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                                <span class="text-xs font-bold" style="color:rgba(255,255,255,0.3);">vs</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 text-center">
                        <div class="w-12 h-12 rounded-full mx-auto mb-1.5 flex items-center justify-center text-xs font-black font-heading text-white"
                             style="background:rgba(255,255,255,0.06);border:2px solid rgba(255,255,255,0.1);">
                            {{ $game->awayTeam->code }}
                        </div>
                        <p class="text-xs font-bold text-white leading-tight">{{ $game->awayTeam->name_fa ?? $game->awayTeam->name }}</p>
                    </div>
                </div>

                @if($game->venue)
                <p class="text-xs text-center mb-3 truncate" style="color:rgba(185,203,185,0.4);">{{ $game->venue }}</p>
                @endif

                {{-- Prediction area --}}
                <div style="border-top:1px solid rgba(255,255,255,0.06);padding-top:12px;">
                    @if($pred)
                    <div class="flex items-center gap-2">
                        <div class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-xl"
                             style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                            <span class="text-xs" style="color:rgba(185,203,185,0.6);">پیش‌بینی:</span>
                            <span class="font-black text-sm font-heading text-white">{{ $pred->home_score }}–{{ $pred->away_score }}</span>
                            @if($pred->points_earned !== null)
                                @php
                                    $pts = $pred->points_earned;
                                    $ptStyle = $pts >= 7 ? 'background:rgba(0,228,118,0.15);color:#00e476;border:1px solid rgba(0,228,118,0.3);'
                                        : ($pts >= 5 ? 'background:rgba(77,159,255,0.15);color:#4D9FFF;border:1px solid rgba(77,159,255,0.3);'
                                        : ($pts >= 2 ? 'background:rgba(255,255,255,0.08);color:#b9cbb9;border:1px solid rgba(255,255,255,0.15);'
                                        : 'background:rgba(255,90,90,0.15);color:#FF8A8A;border:1px solid rgba(255,90,90,0.3);'));
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold" style="{{ $ptStyle }}">+{{ $pts }}</span>
                            @endif
                        </div>
                        @if(!$locked && !$done)
                        <form method="POST" action="{{ route('games.predict.update', $game) }}" class="flex items-center gap-1 flex-shrink-0">
                            @csrf @method('PUT')
                            <input type="number" name="home_score" value="{{ $pred->home_score }}" min="0" max="99"
                                   class="w-10 py-2 rounded-lg text-center text-sm font-black font-heading text-white outline-none focus:border-[#00e476] transition-all"
                                   style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
                            <span style="color:rgba(255,255,255,0.3);" class="text-xs">–</span>
                            <input type="number" name="away_score" value="{{ $pred->away_score }}" min="0" max="99"
                                   class="w-10 py-2 rounded-lg text-center text-sm font-black font-heading text-white outline-none focus:border-[#00e476] transition-all"
                                   style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
                            <button type="submit" class="px-3 py-2 rounded-lg text-xs font-bold cursor-pointer transition-all"
                                    style="background:rgba(0,228,118,0.1);border:1px solid rgba(0,228,118,0.3);color:#00e476;"
                                    onmouseover="this.style.background='rgba(0,228,118,0.2)'"
                                    onmouseout="this.style.background='rgba(0,228,118,0.1)'">ویرایش</button>
                        </form>
                        @endif
                    </div>

                    @elseif(!$locked && !$done)
                    <form method="POST" action="{{ route('games.predict', $game) }}" class="flex items-center gap-2">
                        @csrf
                        <input type="number" name="home_score" value="0" min="0" max="99"
                               class="flex-1 py-3 rounded-xl text-center text-sm font-black font-heading text-white outline-none transition-all"
                               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);"
                               onfocus="this.style.borderColor='#00e476'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                        <span style="color:rgba(255,255,255,0.3);" class="font-bold text-sm">–</span>
                        <input type="number" name="away_score" value="0" min="0" max="99"
                               class="flex-1 py-3 rounded-xl text-center text-sm font-black font-heading text-white outline-none transition-all"
                               style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);"
                               onfocus="this.style.borderColor='#00e476'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                        <button type="submit" class="px-5 py-3 rounded-xl text-sm font-black cursor-pointer transition-all flex-shrink-0"
                                style="background:#00e476;color:#003919;"
                                onmouseover="this.style.boxShadow='0 0 20px rgba(0,228,118,0.4)'"
                                onmouseout="this.style.boxShadow=''">ثبت</button>
                    </form>

                    @else
                    <p class="text-xs text-center py-2 font-semibold" style="color:rgba(185,203,185,0.4);">
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
