@extends('layouts.app')

@section('title', 'داشبورد')
@section('page-title', 'داشبورد')

@section('content')

{{-- Greeting --}}
<div class="mb-6">
    <h2 class="text-xl font-bold font-heading text-brand-text">
        سلام، {{ auth()->user()->name }}
    </h2>
    <p class="text-sm mt-1 text-brand-muted">خلاصه عملکرد شما در پیش‌بینی جام جهانی ۲۰۲۶</p>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="rounded-2xl p-5 bg-brand-surface border border-brand-border">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-4 h-4 text-brand-amber flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">امتیاز کل</p>
        </div>
        <p class="text-3xl font-bold font-heading text-brand-green">{{ auth()->user()->total_score }}</p>
        <p class="text-xs mt-2 text-brand-subtle">از مجموع پیش‌بینی‌ها</p>
    </div>

    <div class="rounded-2xl p-5 bg-brand-surface border border-brand-border">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-4 h-4 text-brand-blue flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">رتبه</p>
        </div>
        <p class="text-3xl font-bold font-heading text-brand-text">#{{ $rank }}</p>
        <p class="text-xs mt-2 text-brand-subtle">از {{ $totalUsers }} نفر</p>
    </div>

    <div class="rounded-2xl p-5 bg-brand-surface border border-brand-border">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-4 h-4 text-brand-green flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">پیش‌بینی‌ها</p>
        </div>
        <p class="text-3xl font-bold font-heading text-brand-text">{{ $predictionsMade }}</p>
        <p class="text-xs mt-2 text-brand-subtle">{{ $exactPredictions }} نتیجه دقیق</p>
    </div>

    <div class="rounded-2xl p-5 bg-brand-surface border border-brand-border">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-4 h-4 text-brand-muted flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">دقت</p>
        </div>
        <p class="text-3xl font-bold font-heading text-brand-text">{{ $accuracy }}%</p>
        <p class="text-xs mt-2 text-brand-subtle">{{ $correctPredictions }} از {{ $scoredPredictions }} ارزیابی شده</p>
    </div>
</div>

{{-- Recent Predictions + Scoring Guide --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Recent Predictions --}}
    <div class="lg:col-span-2 rounded-2xl border border-brand-border bg-brand-surface overflow-hidden">
        <div class="px-5 py-4 border-b border-brand-border flex items-center justify-between">
            <h3 class="font-semibold text-sm font-heading text-brand-text">آخرین پیش‌بینی‌های شما</h3>
            <a href="{{ route('games.index') }}"
               class="text-xs font-medium text-brand-green hover:text-green-400 transition-colors">
                مشاهده همه
            </a>
        </div>

        @if($recentPredictions->isEmpty())
            <div class="px-5 py-12 text-center">
                <svg class="w-10 h-10 text-brand-subtle mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-brand-subtle mb-4">هنوز پیش‌بینی‌ای ثبت نشده است.</p>
                <a href="{{ route('games.index') }}"
                   class="inline-block px-4 py-2 rounded-xl text-sm font-semibold bg-brand-green hover:bg-brand-green-dim text-black transition-colors">
                    پیش‌بینی بازی‌ها
                </a>
            </div>
        @else
            <div class="divide-y divide-brand-border">
                @foreach($recentPredictions as $pred)
                    <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-brand-text truncate">
                                {{ $pred->game->homeTeam->name }} vs {{ $pred->game->awayTeam->name }}
                            </p>
                            <p class="text-xs mt-0.5 text-brand-muted">
                                پیش‌بینی: {{ $pred->home_score }}–{{ $pred->away_score }}
                                @if($pred->game->status === 'finished')
                                    &nbsp;·&nbsp; نتیجه: {{ $pred->game->home_score }}–{{ $pred->game->away_score }}
                                @endif
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            @if($pred->points_earned !== null)
                                @php
                                    $cls = $pred->points_earned >= 7
                                        ? 'bg-green-950/60 text-green-300'
                                        : ($pred->points_earned >= 5
                                            ? 'bg-blue-950/60 text-blue-300'
                                            : ($pred->points_earned >= 2
                                                ? 'bg-brand-card text-brand-muted'
                                                : 'bg-brand-card text-brand-subtle'));
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $cls }}">
                                    +{{ $pred->points_earned }}
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-brand-card text-brand-subtle">
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
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-5">
        <h3 class="font-semibold text-sm font-heading text-brand-text mb-4">سیستم امتیازدهی</h3>
        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0 bg-green-950/60 text-green-300">10</span>
                <span class="text-sm text-brand-muted">نتیجه دقیق</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0 bg-blue-950/60 text-blue-300">7</span>
                <span class="text-sm text-brand-muted">تفاضل گل یکسان</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0 bg-purple-950/60 text-purple-300">5</span>
                <span class="text-sm text-brand-muted">برنده درست</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0 bg-brand-card text-brand-muted">2</span>
                <span class="text-sm text-brand-muted">شرکت در پیش‌بینی</span>
            </div>
        </div>

        <div class="mt-5 pt-4 border-t border-brand-border">
            <a href="{{ route('games.index') }}"
               class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-semibold
                      bg-brand-green hover:bg-brand-green-dim text-black transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                پیش‌بینی بازی‌ها
            </a>
        </div>
    </div>

</div>

@endsection
