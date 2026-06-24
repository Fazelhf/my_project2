@extends('layouts.app')

@section('title', 'پیش‌بینی بازی‌ها')
@section('page-title', 'پیش‌بینی بازی‌ها')

@section('content')

@php
    $stageLabels = [
        'group'         => ['label' => 'مرحله گروهی',     'icon' => 'G'],
        'round_of_16'   => ['label' => 'جام شانزدهم',     'icon' => '16'],
        'quarter_final' => ['label' => 'ربع نهایی',        'icon' => 'QF'],
        'semi_final'    => ['label' => 'نیمه نهایی',       'icon' => 'SF'],
        'third_place'   => ['label' => 'رده‌بندی سوم',    'icon' => '3P'],
        'final'         => ['label' => 'فینال',            'icon' => 'F'],
    ];
@endphp

@if($games->isEmpty())
    <div class="rounded-2xl p-16 text-center" style="background: #0d1525; border: 1px solid #1E2D45;">
        <p class="text-sm text-brand-subtle">هیچ بازی‌ای ثبت نشده است.</p>
    </div>
@endif

@foreach($stageLabels as $stage => $info)
    @if($games->has($stage))
        @php $stageGames = $games[$stage]; @endphp
        <div class="mb-10 animate-[slide-up_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">

            {{-- Stage header --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black font-heading flex-shrink-0"
                     style="background: linear-gradient(135deg, rgba(245,158,11,0.15), rgba(16,185,129,0.1)); border: 1px solid rgba(245,158,11,0.25); color: #F59E0B;">
                    {{ $info['icon'] }}
                </div>
                <h2 class="text-lg font-black font-heading text-brand-text tracking-wide">{{ $info['label'] }}</h2>
                <div class="flex-1 h-px" style="background: linear-gradient(90deg, #1E2D45, transparent);"></div>
                <span class="text-xs text-brand-subtle font-semibold">{{ $stageGames->count() }} بازی</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($stageGames as $game)
                    @php
                        $pred   = $game->predictions->first();
                        $locked = $game->isPredictionLocked();
                        $done   = $game->status === 'finished';

                        if ($done) {
                            $borderColor = 'rgba(100,116,139,0.4)';
                            $headerBg = 'rgba(15,23,42,0.6)';
                        } elseif ($pred) {
                            $borderColor = 'rgba(16,185,129,0.4)';
                            $headerBg = 'rgba(16,185,129,0.04)';
                        } elseif ($locked) {
                            $borderColor = 'rgba(239,68,68,0.3)';
                            $headerBg = 'rgba(239,68,68,0.04)';
                        } else {
                            $borderColor = 'rgba(245,158,11,0.3)';
                            $headerBg = 'rgba(245,158,11,0.04)';
                        }
                    @endphp

                    <div class="rounded-2xl overflow-hidden transition-all duration-300 group"
                         style="background: #0d1525; border: 1px solid {{ $borderColor }};"
                         onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.4)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">

                        {{-- Card Header --}}
                        <div class="px-4 py-3 flex items-center justify-between" style="background: {{ $headerBg }}; border-bottom: 1px solid #1a2234;">
                            <span class="text-xs text-brand-muted font-medium">
                                {{ $game->scheduled_at?->format('j M Y — H:i') }}
                            </span>

                            @if($done)
                                <span class="text-xs px-2.5 py-1 rounded-lg font-bold score-gray">پایان یافته</span>
                            @elseif($locked)
                                <span class="text-xs px-2.5 py-1 rounded-lg font-bold score-red">قفل شده</span>
                            @elseif($pred)
                                <span class="text-xs px-2.5 py-1 rounded-lg font-bold score-green">ثبت شده</span>
                            @else
                                <span class="text-xs px-2.5 py-1 rounded-lg font-bold score-gold animate-pulse">باز</span>
                            @endif
                        </div>

                        {{-- Teams Row --}}
                        <div class="px-5 py-5">
                            <div class="flex items-center justify-between gap-2">

                                {{-- Home Team --}}
                                <div class="flex-1 text-center">
                                    <div class="w-12 h-12 rounded-xl mx-auto mb-2 flex items-center justify-center text-sm font-black font-heading"
                                         style="background: rgba(255,255,255,0.06); border: 1px solid #1E2D45; color: #F1F5F9;">
                                        {{ $game->homeTeam->code }}
                                    </div>
                                    <p class="font-bold text-xs text-brand-text leading-tight">{{ $game->homeTeam->name }}</p>
                                </div>

                                {{-- Score / VS --}}
                                <div class="flex flex-col items-center gap-1.5 px-2">
                                    @if($done)
                                        <div class="px-4 py-2 rounded-xl text-center"
                                             style="background: rgba(255,255,255,0.06); border: 1px solid #1E2D45;">
                                            <span class="font-black text-xl font-heading text-brand-text leading-none">
                                                {{ $game->home_score }}
                                                <span class="text-brand-subtle mx-1 text-base">–</span>
                                                {{ $game->away_score }}
                                            </span>
                                        </div>
                                        <span class="text-[10px] text-brand-subtle font-semibold uppercase tracking-widest">نهایی</span>
                                    @else
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                             style="background: rgba(255,255,255,0.04); border: 1px solid #1E2D45;">
                                            <span class="text-xs font-bold text-brand-subtle">vs</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Away Team --}}
                                <div class="flex-1 text-center">
                                    <div class="w-12 h-12 rounded-xl mx-auto mb-2 flex items-center justify-center text-sm font-black font-heading"
                                         style="background: rgba(255,255,255,0.06); border: 1px solid #1E2D45; color: #F1F5F9;">
                                        {{ $game->awayTeam->code }}
                                    </div>
                                    <p class="font-bold text-xs text-brand-text leading-tight">{{ $game->awayTeam->name }}</p>
                                </div>
                            </div>

                            {{-- Prediction area --}}
                            <div class="mt-4 pt-4" style="border-top: 1px solid #1a2234;">

                                @if($pred)
                                    <div class="flex items-center gap-2">

                                        {{-- Current prediction badge --}}
                                        <div class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-xl"
                                             style="background: rgba(255,255,255,0.04); border: 1px solid #1E2D45;">
                                            <span class="text-xs text-brand-muted">پیش‌بینی:</span>
                                            <span class="font-black text-sm font-heading text-brand-text">
                                                {{ $pred->home_score }}–{{ $pred->away_score }}
                                            </span>
                                            @if($pred->points_earned !== null)
                                                @php
                                                    $pClass = $pred->points_earned >= 7 ? 'score-green'
                                                        : ($pred->points_earned >= 5 ? 'score-blue'
                                                        : ($pred->points_earned >= 2 ? 'score-gray' : 'score-red'));
                                                @endphp
                                                <span class="text-xs font-black font-heading px-2 py-0.5 rounded-lg {{ $pClass }}">
                                                    +{{ $pred->points_earned }}
                                                </span>
                                            @endif
                                        </div>

                                        @if(!$locked && !$done)
                                            <form method="POST" action="{{ route('games.predict.update', $game) }}"
                                                  class="flex items-center gap-1.5 flex-shrink-0">
                                                @csrf @method('PUT')
                                                <input type="number" name="home_score" value="{{ $pred->home_score }}"
                                                       min="0" max="99"
                                                       class="w-10 px-1 py-2 rounded-lg text-center text-sm font-black font-heading text-brand-text outline-none transition-all duration-150"
                                                       style="background: rgba(255,255,255,0.06); border: 1px solid #1E2D45;"
                                                       onfocus="this.style.borderColor='#F59E0B'"
                                                       onblur="this.style.borderColor='#1E2D45'">
                                                <span class="text-brand-subtle text-xs">–</span>
                                                <input type="number" name="away_score" value="{{ $pred->away_score }}"
                                                       min="0" max="99"
                                                       class="w-10 px-1 py-2 rounded-lg text-center text-sm font-black font-heading text-brand-text outline-none transition-all duration-150"
                                                       style="background: rgba(255,255,255,0.06); border: 1px solid #1E2D45;"
                                                       onfocus="this.style.borderColor='#F59E0B'"
                                                       onblur="this.style.borderColor='#1E2D45'">
                                                <button type="submit"
                                                        class="px-3 py-2 rounded-lg text-xs font-black font-heading cursor-pointer transition-all duration-150"
                                                        style="background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.3); color: #F59E0B;"
                                                        onmouseover="this.style.background='rgba(245,158,11,0.25)'"
                                                        onmouseout="this.style.background='rgba(245,158,11,0.15)'">
                                                    ویرایش
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                @elseif(!$locked && !$done)
                                    <form method="POST" action="{{ route('games.predict', $game) }}"
                                          class="flex items-center gap-2">
                                        @csrf
                                        <input type="number" name="home_score" value="0" min="0" max="99"
                                               class="flex-1 py-2.5 rounded-xl text-center text-sm font-black font-heading text-brand-text outline-none transition-all duration-150"
                                               style="background: rgba(255,255,255,0.06); border: 1px solid #1E2D45;"
                                               onfocus="this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.1)'"
                                               onblur="this.style.borderColor='#1E2D45'; this.style.boxShadow='none'">
                                        <span class="font-bold text-brand-subtle text-lg">–</span>
                                        <input type="number" name="away_score" value="0" min="0" max="99"
                                               class="flex-1 py-2.5 rounded-xl text-center text-sm font-black font-heading text-brand-text outline-none transition-all duration-150"
                                               style="background: rgba(255,255,255,0.06); border: 1px solid #1E2D45;"
                                               onfocus="this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.1)'"
                                               onblur="this.style.borderColor='#1E2D45'; this.style.boxShadow='none'">
                                        <button type="submit"
                                                class="px-4 py-2.5 rounded-xl text-sm font-black font-heading cursor-pointer transition-all duration-200 flex-shrink-0"
                                                style="background: linear-gradient(135deg, #D97706, #F59E0B); color: #0a0a0a; box-shadow: 0 0 15px rgba(245,158,11,0.2);"
                                                onmouseover="this.style.boxShadow='0 0 25px rgba(245,158,11,0.4)'; this.style.transform='scale(1.03)'"
                                                onmouseout="this.style.boxShadow='0 0 15px rgba(245,158,11,0.2)'; this.style.transform='scale(1)'">
                                            ثبت
                                        </button>
                                    </form>

                                @else
                                    <p class="text-xs text-center py-2 font-semibold text-brand-subtle">
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
