@extends('layouts.admin')

@section('title', 'داشبورد مدیریت')
@section('page-title', 'داشبورد')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-5">
        <p class="text-xs font-semibold uppercase tracking-wider text-brand-muted mb-2">کاربران</p>
        <p class="text-3xl font-bold font-heading text-brand-green">{{ $stats['total_users'] }}</p>
    </div>
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-5">
        <p class="text-xs font-semibold uppercase tracking-wider text-brand-muted mb-2">تیم‌ها</p>
        <p class="text-3xl font-bold font-heading text-brand-blue">{{ $stats['total_teams'] }}</p>
    </div>
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-5">
        <p class="text-xs font-semibold uppercase tracking-wider text-brand-muted mb-2">بازی‌ها</p>
        <p class="text-3xl font-bold font-heading text-purple-400">{{ $stats['total_games'] }}</p>
    </div>
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-5">
        <p class="text-xs font-semibold uppercase tracking-wider text-brand-muted mb-2">پیش‌بینی‌ها</p>
        <p class="text-3xl font-bold font-heading text-brand-amber">{{ $stats['total_predictions'] }}</p>
    </div>
</div>

{{-- Sub stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-5">
        <p class="text-xs font-semibold uppercase tracking-wider text-brand-muted mb-2">بازی‌های پایان یافته</p>
        <p class="text-2xl font-bold font-heading text-brand-text">{{ $stats['finished_games'] }}</p>
    </div>
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-5">
        <p class="text-xs font-semibold uppercase tracking-wider text-brand-muted mb-2">بازی‌های پیش‌رو</p>
        <p class="text-2xl font-bold font-heading text-brand-text">{{ $stats['upcoming_games'] }}</p>
    </div>
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-5">
        <p class="text-xs font-semibold uppercase tracking-wider text-brand-muted mb-2">پیش‌بینی‌های ارزیابی شده</p>
        <p class="text-2xl font-bold font-heading text-brand-text">{{ $stats['scored_predictions'] }}</p>
    </div>
</div>

{{-- Recalculate --}}
<div class="rounded-2xl border border-brand-border bg-brand-surface p-5 mb-6 flex items-center justify-between gap-4">
    <div>
        <h3 class="font-semibold text-sm font-heading text-brand-text">بازمحاسبه همه امتیازات</h3>
        <p class="text-xs mt-1 text-brand-muted">در صورت ویرایش نتایج، این دکمه را بزنید تا همه امتیازها از نو حساب شوند.</p>
    </div>
    <form method="POST" action="{{ route('admin.recalculate') }}">
        @csrf
        <button type="submit"
                class="px-4 py-2.5 rounded-xl text-sm font-semibold cursor-pointer transition-colors whitespace-nowrap
                       bg-brand-green hover:bg-brand-green-dim text-black">
            بازمحاسبه
        </button>
    </form>
</div>

{{-- Recent + Upcoming --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    <div class="rounded-2xl border border-brand-border bg-brand-surface overflow-hidden">
        <div class="px-5 py-4 border-b border-brand-border flex items-center justify-between">
            <h3 class="font-semibold text-sm font-heading text-brand-text">آخرین نتایج</h3>
            <a href="{{ route('admin.games.index') }}" class="text-xs text-brand-green hover:text-green-400 transition-colors">همه بازی‌ها</a>
        </div>
        @forelse($recentGames as $g)
            <div class="px-5 py-3 border-b border-brand-border flex items-center gap-3 last:border-0">
                <span class="text-xs flex-1 truncate text-brand-text">
                    {{ $g->homeTeam->name }} {{ $g->home_score }}–{{ $g->away_score }} {{ $g->awayTeam->name }}
                </span>
                <span class="text-xs px-2 py-0.5 rounded-md bg-brand-card text-brand-muted">{{ $g->stage_label }}</span>
            </div>
        @empty
            <p class="px-5 py-8 text-sm text-center text-brand-subtle">هیچ بازی‌ای پایان نیافته است.</p>
        @endforelse
    </div>

    <div class="rounded-2xl border border-brand-border bg-brand-surface overflow-hidden">
        <div class="px-5 py-4 border-b border-brand-border flex items-center justify-between">
            <h3 class="font-semibold text-sm font-heading text-brand-text">بازی‌های پیش‌رو</h3>
            <a href="{{ route('admin.games.create') }}" class="text-xs text-brand-green hover:text-green-400 transition-colors">+ جدید</a>
        </div>
        @forelse($upcomingGames as $g)
            <div class="px-5 py-3 border-b border-brand-border flex items-center gap-3 last:border-0">
                <span class="text-xs flex-1 truncate text-brand-text">
                    {{ $g->homeTeam->name }} vs {{ $g->awayTeam->name }}
                </span>
                <span class="text-xs text-brand-muted">{{ $g->scheduled_at?->format('j M') }}</span>
            </div>
        @empty
            <p class="px-5 py-8 text-sm text-center text-brand-subtle">بازی پیش‌رویی تعریف نشده است.</p>
        @endforelse
    </div>
</div>

@endsection
