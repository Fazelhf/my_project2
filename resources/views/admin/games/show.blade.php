@extends('layouts.admin')

@section('title', 'ثبت نتیجه')
@section('page-title', $game->homeTeam->name . ' vs ' . $game->awayTeam->name)

@section('content')

<div class="max-w-xl">

    {{-- Game Info --}}
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-5 mb-5">
        <div class="flex items-center justify-between gap-4">
            <div class="text-center flex-1">
                <p class="font-bold text-lg text-brand-text">{{ $game->homeTeam->name }}</p>
                <p class="text-xs mt-0.5 text-brand-muted">{{ $game->homeTeam->code }}</p>
            </div>
            <div class="text-center px-4">
                @if($game->status === 'finished')
                    <p class="text-3xl font-bold font-heading text-brand-green">
                        {{ $game->home_score }}–{{ $game->away_score }}
                    </p>
                    <p class="text-xs mt-1 text-brand-muted">نتیجه نهایی ۹۰ دقیقه</p>
                @else
                    <p class="text-lg font-bold text-brand-subtle">vs</p>
                @endif
            </div>
            <div class="text-center flex-1">
                <p class="font-bold text-lg text-brand-text">{{ $game->awayTeam->name }}</p>
                <p class="text-xs mt-0.5 text-brand-muted">{{ $game->awayTeam->code }}</p>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-brand-border flex flex-wrap items-center gap-4 text-xs text-brand-muted">
            <span>{{ $game->stage_label }}</span>
            @if($game->scheduled_at)
                <span>{{ $game->scheduled_at->format('j M Y — H:i') }}</span>
            @endif
            @if($game->venue)
                <span>{{ $game->venue }}</span>
            @endif
            @if($game->is_disciplinary)
                <span class="px-2 py-0.5 rounded-md bg-red-950/50 text-red-300">انضباطی</span>
            @endif
        </div>
    </div>

    @if($game->status !== 'finished')
        {{-- Submit Result Form --}}
        <div class="rounded-2xl border border-brand-border bg-brand-surface p-6 mb-5">
            <h2 class="font-semibold text-sm font-heading text-brand-text mb-5">ثبت نتیجه ۹۰ دقیقه</h2>

            <form method="POST" action="{{ route('admin.games.result', $game) }}" class="space-y-4">
                @csrf

                @if($errors->any())
                    <div class="px-4 py-3 rounded-xl text-sm bg-red-950/50 border border-red-800/50 text-red-300 space-y-1">
                        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                    </div>
                @endif

                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2 text-center">
                            {{ $game->homeTeam->name }}
                        </label>
                        <input type="number" name="home_score" value="{{ old('home_score', 0) }}" min="0" max="99" required
                               class="w-full px-4 py-3 rounded-xl text-center text-xl font-bold bg-brand-card border border-brand-border text-brand-text
                                      outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all">
                    </div>
                    <span class="text-2xl font-bold flex-shrink-0 text-brand-subtle">–</span>
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2 text-center">
                            {{ $game->awayTeam->name }}
                        </label>
                        <input type="number" name="away_score" value="{{ old('away_score', 0) }}" min="0" max="99" required
                               class="w-full px-4 py-3 rounded-xl text-center text-xl font-bold bg-brand-card border border-brand-border text-brand-text
                                      outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all">
                    </div>
                </div>

                @if($game->isKnockout())
                    <div class="p-3 rounded-xl text-xs bg-brand-card text-brand-muted">
                        برای مسابقه حذفی، فقط نتیجه ۹۰ دقیقه را ثبت کنید. نتیجه وقت اضافه/پنالتی در فیلد نهایی ذخیره می‌شود ولی برای امتیازدهی مهم نیست.
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2 text-center">
                                نتیجه نهایی (اختیاری)
                            </label>
                            <input type="number" name="home_score_final" value="{{ old('home_score_final') }}" min="0" max="99"
                                   placeholder="–"
                                   class="w-full px-4 py-2.5 rounded-xl text-center text-sm font-bold bg-brand-card border border-brand-border text-brand-muted
                                          outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all placeholder:text-brand-subtle">
                        </div>
                        <span class="text-xl font-bold flex-shrink-0 text-brand-subtle">–</span>
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2 text-center">&nbsp;</label>
                            <input type="number" name="away_score_final" value="{{ old('away_score_final') }}" min="0" max="99"
                                   placeholder="–"
                                   class="w-full px-4 py-2.5 rounded-xl text-center text-sm font-bold bg-brand-card border border-brand-border text-brand-muted
                                          outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all placeholder:text-brand-subtle">
                        </div>
                    </div>
                @endif

                <button type="submit"
                        class="w-full py-3 rounded-xl text-sm font-bold cursor-pointer transition-colors
                               bg-brand-green hover:bg-brand-green-dim text-black">
                    ثبت نتیجه و محاسبه امتیازات
                </button>
            </form>
        </div>
    @else
        {{-- Scoring Stats --}}
        @php
            $stats = app(\App\Services\PredictionScoringService::class)->getGameStats($game);
        @endphp
        <div class="rounded-2xl border border-brand-border bg-brand-surface p-5 mb-5">
            <h2 class="font-semibold text-sm font-heading text-brand-text mb-4">آمار امتیازدهی</h2>
            <div class="grid grid-cols-5 gap-2 text-center">
                <div class="rounded-xl p-3 bg-brand-card">
                    <p class="text-xl font-bold text-brand-green">{{ $stats['breakdown'][10] }}</p>
                    <p class="text-xs mt-1 text-brand-subtle">+10</p>
                </div>
                <div class="rounded-xl p-3 bg-brand-card">
                    <p class="text-xl font-bold text-brand-blue">{{ $stats['breakdown'][7] }}</p>
                    <p class="text-xs mt-1 text-brand-subtle">+7</p>
                </div>
                <div class="rounded-xl p-3 bg-brand-card">
                    <p class="text-xl font-bold text-purple-400">{{ $stats['breakdown'][5] }}</p>
                    <p class="text-xs mt-1 text-brand-subtle">+5</p>
                </div>
                <div class="rounded-xl p-3 bg-brand-card">
                    <p class="text-xl font-bold text-brand-muted">{{ $stats['breakdown'][2] }}</p>
                    <p class="text-xs mt-1 text-brand-subtle">+2</p>
                </div>
                <div class="rounded-xl p-3 bg-brand-card">
                    <p class="text-xl font-bold text-brand-subtle">{{ $stats['breakdown'][0] }}</p>
                    <p class="text-xs mt-1 text-brand-subtle">+0</p>
                </div>
            </div>
            <p class="text-xs mt-3 text-center text-brand-muted">
                {{ $stats['scored'] }} از {{ $stats['total'] }} پیش‌بینی ارزیابی شد
            </p>
        </div>
    @endif

    <a href="{{ route('admin.games.index') }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-brand-muted hover:text-brand-text transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        بازگشت به لیست بازی‌ها
    </a>
</div>

@endsection
