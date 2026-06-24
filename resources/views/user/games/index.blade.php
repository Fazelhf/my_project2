@extends('layouts.app')

@section('title', 'پیش‌بینی بازی‌ها')
@section('page-title', 'پیش‌بینی بازی‌ها')

@section('content')

@php
    $stageLabels = [
        'group'         => 'مرحله گروهی',
        'round_of_16'   => 'جام شانزدهم',
        'quarter_final' => 'ربع‌نهایی',
        'semi_final'    => 'نیمه‌نهایی',
        'third_place'   => 'رده‌بندی سوم',
        'final'         => 'فینال',
    ];
@endphp

@if($games->isEmpty())
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-12 text-center">
        <p class="text-sm text-brand-subtle">هیچ بازی‌ای ثبت نشده است.</p>
    </div>
@endif

@foreach($stageLabels as $stage => $label)
    @if($games->has($stage))
        <div class="mb-8">
            <h2 class="text-base font-bold font-heading text-brand-text mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-brand-green inline-block"></span>
                {{ $label }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($games[$stage] as $game)
                    @php
                        $pred   = $game->predictions->first();
                        $locked = $game->isPredictionLocked();
                        $done   = $game->status === 'finished';
                    @endphp

                    <div @class([
                        'rounded-2xl border overflow-hidden',
                        'border-brand-green/30 bg-brand-surface' => !$done && $pred,
                        'border-brand-border bg-brand-surface'   => $done || !$pred,
                    ])>

                        {{-- Card Header --}}
                        <div class="px-4 py-3 border-b border-brand-border bg-brand-bg/40 flex items-center justify-between">
                            <span class="text-xs text-brand-muted">
                                {{ $game->scheduled_at?->format('j M Y — H:i') }}
                            </span>
                            @if($done)
                                <span class="text-xs px-2 py-0.5 rounded-md font-medium bg-brand-card text-brand-muted">پایان یافته</span>
                            @elseif($locked)
                                <span class="text-xs px-2 py-0.5 rounded-md font-medium bg-red-950/50 text-red-300">قفل شده</span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-md font-medium bg-green-950/50 text-green-300">باز</span>
                            @endif
                        </div>

                        {{-- Teams --}}
                        <div class="px-4 py-4">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex-1 text-center">
                                    <p class="font-bold text-sm text-brand-text leading-tight">{{ $game->homeTeam->name }}</p>
                                    <p class="text-xs mt-0.5 text-brand-subtle">{{ $game->homeTeam->code }}</p>
                                </div>
                                <div class="flex flex-col items-center gap-1 px-3">
                                    @if($done)
                                        <span class="font-bold text-xl px-3 py-1 rounded-lg bg-brand-card text-brand-text font-heading">
                                            {{ $game->home_score }}–{{ $game->away_score }}
                                        </span>
                                    @else
                                        <span class="text-sm font-medium text-brand-subtle">vs</span>
                                    @endif
                                </div>
                                <div class="flex-1 text-center">
                                    <p class="font-bold text-sm text-brand-text leading-tight">{{ $game->awayTeam->name }}</p>
                                    <p class="text-xs mt-0.5 text-brand-subtle">{{ $game->awayTeam->code }}</p>
                                </div>
                            </div>

                            {{-- Prediction --}}
                            <div class="mt-4">
                                @if($pred)
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex-1 flex items-center gap-2 px-3 py-2 rounded-xl bg-brand-card">
                                            <span class="text-xs text-brand-muted">پیش‌بینی:</span>
                                            <span class="font-bold text-sm text-brand-text">
                                                {{ $pred->home_score }}–{{ $pred->away_score }}
                                            </span>
                                            @if($pred->points_earned !== null)
                                                @php
                                                    $ptsCls = $pred->points_earned >= 7
                                                        ? 'bg-green-950/60 text-green-300'
                                                        : ($pred->points_earned >= 5
                                                            ? 'bg-blue-950/60 text-blue-300'
                                                            : 'bg-brand-card text-brand-subtle');
                                                @endphp
                                                <span class="mr-auto text-xs font-bold px-2 py-0.5 rounded-md {{ $ptsCls }}">
                                                    +{{ $pred->points_earned }}
                                                </span>
                                            @endif
                                        </div>
                                        @if(!$locked && !$done)
                                            <form method="POST" action="{{ route('games.predict.update', $game) }}" class="flex items-center gap-1.5">
                                                @csrf @method('PUT')
                                                <input type="number" name="home_score" value="{{ $pred->home_score }}" min="0" max="99"
                                                       class="w-11 px-1.5 py-1.5 rounded-lg text-center text-sm bg-brand-card border border-brand-border text-brand-text
                                                              outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green/30 transition-all">
                                                <span class="text-brand-subtle">–</span>
                                                <input type="number" name="away_score" value="{{ $pred->away_score }}" min="0" max="99"
                                                       class="w-11 px-1.5 py-1.5 rounded-lg text-center text-sm bg-brand-card border border-brand-border text-brand-text
                                                              outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green/30 transition-all">
                                                <button type="submit"
                                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold cursor-pointer
                                                               bg-brand-green hover:bg-brand-green-dim text-black transition-colors">
                                                    ویرایش
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @elseif(!$locked && !$done)
                                    <form method="POST" action="{{ route('games.predict', $game) }}" class="flex items-center gap-2">
                                        @csrf
                                        <input type="number" name="home_score" value="0" min="0" max="99"
                                               class="flex-1 px-3 py-2 rounded-xl text-center text-sm font-bold
                                                      bg-brand-card border border-brand-border text-brand-text
                                                      outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green/30 transition-all">
                                        <span class="font-bold text-sm text-brand-subtle">–</span>
                                        <input type="number" name="away_score" value="0" min="0" max="99"
                                               class="flex-1 px-3 py-2 rounded-xl text-center text-sm font-bold
                                                      bg-brand-card border border-brand-border text-brand-text
                                                      outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green/30 transition-all">
                                        <button type="submit"
                                                class="px-4 py-2 rounded-xl text-sm font-bold cursor-pointer
                                                       bg-brand-green hover:bg-brand-green-dim text-black transition-colors">
                                            ثبت
                                        </button>
                                    </form>
                                @else
                                    <p class="text-xs text-center py-2 text-brand-subtle">
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
