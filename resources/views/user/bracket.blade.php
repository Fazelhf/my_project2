@extends('layouts.app')
@section('title', 'نمودار حذفی')

@section('content')
@php
    $stageOrder = ['round_of_32', 'round_of_16', 'quarter_final', 'semi_final', 'final'];
    $stageLabels = [
        'round_of_32'   => 'دور ۳۲',
        'round_of_16'   => 'دور ۱۶',
        'quarter_final' => 'ربع‌نهایی',
        'semi_final'    => 'نیمه‌نهایی',
        'third_place'   => 'رده‌بندی سوم',
        'final'         => 'فینال',
    ];
    $finalGame = $knockoutGames->get('final')?->first();
    $thirdGame = $knockoutGames->get('third_place')?->first();
@endphp

<div class="mb-5 flex items-center justify-between">
    <h1 class="text-2xl font-black font-heading text-white flex items-center gap-3">
        <span class="material-symbols-outlined text-3xl" style="color:#00e476;">account_tree</span>
        نمودار حذفی
    </h1>
    <a href="{{ route('tournament.prediction') }}" class="btn-primary text-sm px-4 py-2">
        <span class="material-symbols-outlined text-base">emoji_events</span>
        پیش‌بینی قهرمان
    </a>
</div>

{{-- Champion display --}}
@if($finalGame && $finalGame->status === 'finished' && $finalGame->winnerTeam)
<div class="glass-card rounded-3xl p-6 mb-6 text-center animate-slide-up" style="border:1px solid rgba(255,215,0,0.3);background:linear-gradient(135deg,rgba(255,215,0,0.05),rgba(14,20,29,0.9));">
    <div class="text-4xl mb-2">🏆</div>
    <p class="text-sm font-bold mb-2" style="color:rgba(185,203,185,0.6);">قهرمان جهان ۲۰۲۶</p>
    <div class="flex items-center justify-center gap-3">
        @if($finalGame->winnerTeam->flag_url)
            <img src="{{ $finalGame->winnerTeam->flag_url }}" alt="{{ $finalGame->winnerTeam->code }}" class="w-12 h-8 object-cover rounded" style="border:1px solid rgba(255,255,255,0.2);" onerror="this.style.display='none'">
        @endif
        <span class="text-2xl font-black font-heading" style="color:#FFD700;">{{ $finalGame->winnerTeam->name_fa ?? $finalGame->winnerTeam->name }}</span>
    </div>
</div>
@endif

{{-- Bracket horizontal scroll --}}
<div class="glass-card rounded-3xl p-4 md:p-6 overflow-x-auto animate-slide-up" style="animation-delay:.1s">
    <div class="bracket-container" style="min-width:900px;">

        @php
            $stages = ['round_of_32', 'round_of_16', 'quarter_final', 'semi_final', 'final'];
            $visibleStages = collect($stages)->filter(fn($s) => $knockoutGames->has($s))->values();
        @endphp

        <div class="flex items-stretch gap-0" style="min-height:500px;">

            @foreach($visibleStages as $stageIndex => $stage)
            @php
                $games = $knockoutGames->get($stage, collect());
                $isLast = $stageIndex === $visibleStages->count() - 1;
                $colGames = $games->count();
            @endphp

            <div class="flex flex-col flex-1" style="min-width:160px;">
                {{-- Stage header --}}
                <div class="text-center mb-4 px-2">
                    <span class="text-xs font-bold px-3 py-1 rounded-full" style="background:rgba(0,228,118,0.1);color:#00e476;border:1px solid rgba(0,228,118,0.2);">{{ $stageLabels[$stage] ?? $stage }}</span>
                </div>

                {{-- Games --}}
                <div class="flex flex-col justify-around flex-1 gap-2 px-2">
                    @foreach($games as $game)
                    @php
                        $locked = $game->isPredictionLocked();
                        $isFinished = $game->status === 'finished';
                        $winner = $game->winnerTeam ?? ($isFinished
                            ? ($game->home_score > $game->away_score ? $game->homeTeam : ($game->away_score > $game->home_score ? $game->awayTeam : null))
                            : null);
                    @endphp
                    <a href="{{ route('games.show', $game) }}" class="bracket-match group cursor-pointer" style="text-decoration:none;">
                        <div class="rounded-xl overflow-hidden transition-all duration-200 group-hover:scale-105"
                             style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">

                            {{-- Home team --}}
                            @php
                                $homeWon = $winner && $winner->id === $game->home_team_id;
                                $awayWon = $winner && $winner->id === $game->away_team_id;
                            @endphp
                            <div class="flex items-center gap-2 px-3 py-2" style="{{ $homeWon ? 'background:rgba(0,228,118,0.1);' : '' }}">
                                @if($game->homeTeam->flag_url)
                                    <img src="{{ $game->homeTeam->flag_url }}" alt="" class="w-6 h-4 object-cover rounded flex-shrink-0" onerror="this.style.display='none'">
                                @else
                                    <div class="w-6 h-4 rounded flex-shrink-0 flex items-center justify-center text-[8px] font-black" style="background:rgba(255,255,255,0.1);color:#F0F4FF;">{{ $game->homeTeam->code }}</div>
                                @endif
                                <span class="text-xs font-semibold flex-1 truncate" style="{{ $homeWon ? 'color:#00e476;' : 'color:rgba(185,203,185,0.8);' }}">
                                    {{ $game->homeTeam->name_fa ?? $game->homeTeam->name }}
                                </span>
                                @if($isFinished)
                                <span class="text-sm font-black flex-shrink-0" style="{{ $homeWon ? 'color:#00e476;' : 'color:rgba(185,203,185,0.5);' }}">{{ $game->home_score }}</span>
                                @endif
                                @if($homeWon)
                                    <span class="material-symbols-outlined text-xs flex-shrink-0" style="color:#00e476;font-size:14px;">check_circle</span>
                                @endif
                            </div>

                            <div style="height:1px;background:rgba(255,255,255,0.07);"></div>

                            {{-- Away team --}}
                            <div class="flex items-center gap-2 px-3 py-2" style="{{ $awayWon ? 'background:rgba(0,228,118,0.1);' : '' }}">
                                @if($game->awayTeam->flag_url)
                                    <img src="{{ $game->awayTeam->flag_url }}" alt="" class="w-6 h-4 object-cover rounded flex-shrink-0" onerror="this.style.display='none'">
                                @else
                                    <div class="w-6 h-4 rounded flex-shrink-0 flex items-center justify-center text-[8px] font-black" style="background:rgba(255,255,255,0.1);color:#F0F4FF;">{{ $game->awayTeam->code }}</div>
                                @endif
                                <span class="text-xs font-semibold flex-1 truncate" style="{{ $awayWon ? 'color:#00e476;' : 'color:rgba(185,203,185,0.8);' }}">
                                    {{ $game->awayTeam->name_fa ?? $game->awayTeam->name }}
                                </span>
                                @if($isFinished)
                                <span class="text-sm font-black flex-shrink-0" style="{{ $awayWon ? 'color:#00e476;' : 'color:rgba(185,203,185,0.5);' }}">{{ $game->away_score }}</span>
                                @endif
                                @if($awayWon)
                                    <span class="material-symbols-outlined text-xs flex-shrink-0" style="color:#00e476;font-size:14px;">check_circle</span>
                                @endif
                            </div>

                            @if(!$isFinished && !$locked)
                            <div class="px-3 py-1.5" style="border-top:1px solid rgba(255,255,255,0.05);background:rgba(0,228,118,0.04);">
                                <span class="text-[10px] font-bold" style="color:rgba(0,228,118,0.7);">
                                    {{ $game->scheduled_at?->timezone('Asia/Tehran')->format('j M • H:i') }}
                                </span>
                            </div>
                            @elseif($locked && !$isFinished)
                            <div class="px-3 py-1.5" style="border-top:1px solid rgba(255,255,255,0.05);">
                                <span class="text-[10px]" style="color:rgba(185,203,185,0.4);">در حال برگزاری</span>
                            </div>
                            @endif

                        </div>
                    </a>
                    @endforeach

                    @if($games->isEmpty())
                    <div class="flex-1 flex items-center justify-center">
                        <div class="rounded-xl px-3 py-4 text-center" style="background:rgba(255,255,255,0.02);border:1px dashed rgba(255,255,255,0.1);">
                            <span class="text-xs" style="color:rgba(185,203,185,0.3);">مشخص نشده</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Connector lines --}}
            @if(!$isLast)
            <div class="flex items-center justify-center" style="width:20px;flex-shrink:0;">
                <div style="width:100%;height:2px;background:linear-gradient(90deg,rgba(0,228,118,0.3),rgba(0,228,118,0.1));"></div>
            </div>
            @endif

            @endforeach

        </div>

    </div>
</div>

{{-- Third place --}}
@if($knockoutGames->has('third_place') && $knockoutGames->get('third_place')->isNotEmpty())
@php $tg = $knockoutGames->get('third_place')->first(); @endphp
<div class="glass-card rounded-3xl p-5 mt-5 animate-slide-up" style="animation-delay:.2s">
    <h3 class="text-sm font-black font-heading text-white mb-4 flex items-center gap-2">
        <span class="text-lg">🥉</span>
        رده‌بندی سوم و چهارم
    </h3>
    <a href="{{ route('games.show', $tg) }}" class="flex items-center justify-between gap-4 py-2">
        <div class="flex items-center gap-3">
            @if($tg->homeTeam->flag_url)
                <img src="{{ $tg->homeTeam->flag_url }}" alt="" class="w-8 h-5 object-cover rounded" onerror="this.style.display='none'">
            @endif
            <span class="font-bold text-white">{{ $tg->homeTeam->name_fa ?? $tg->homeTeam->name }}</span>
        </div>
        <div class="text-lg font-black font-heading" style="color:{{ $tg->status === 'finished' ? '#F5A623' : 'rgba(185,203,185,0.4)' }};">
            {{ $tg->status === 'finished' ? $tg->home_score . ' – ' . $tg->away_score : 'vs' }}
        </div>
        <div class="flex items-center gap-3">
            <span class="font-bold text-white">{{ $tg->awayTeam->name_fa ?? $tg->awayTeam->name }}</span>
            @if($tg->awayTeam->flag_url)
                <img src="{{ $tg->awayTeam->flag_url }}" alt="" class="w-8 h-5 object-cover rounded" onerror="this.style.display='none'">
            @endif
        </div>
    </a>
</div>
@endif

@endsection
