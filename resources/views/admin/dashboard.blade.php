@extends('layouts.admin')

@section('title', 'داشبورد مدیریت')
@section('page-title', 'داشبورد')

@section('content')

{{-- ── Stats Grid ──────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $statCards = [
            ['val' => $stats['total_users'],       'label' => 'کاربران',              'color' => '#00e476', 'icon' => 'group'],
            ['val' => $stats['total_teams'],        'label' => 'تیم‌ها',               'color' => '#4D9FFF', 'icon' => 'flag'],
            ['val' => $stats['total_games'],        'label' => 'بازی‌ها',              'color' => '#A78BFA', 'icon' => 'sports_soccer'],
            ['val' => $stats['total_predictions'],  'label' => 'پیش‌بینی‌ها',          'color' => '#F59E0B', 'icon' => 'analytics'],
        ];
    @endphp

    @foreach($statCards as $s)
    <div class="liquid-glass rounded-2xl p-5" style="border-right:3px solid {{ $s['color'] }}40;">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-bold uppercase tracking-wider" style="color:rgba(185,203,185,0.7);">{{ $s['label'] }}</p>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:{{ $s['color'] }}15;">
                <span class="material-symbols-outlined text-base" style="color:{{ $s['color'] }};">{{ $s['icon'] }}</span>
            </div>
        </div>
        <p class="text-3xl font-black font-heading" style="color:{{ $s['color'] }};">{{ $s['val'] }}</p>
    </div>
    @endforeach
</div>

{{-- ── Sub Stats ────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    @php
        $subStats = [
            ['val' => $stats['finished_games'],      'label' => 'بازی‌های پایان یافته',     'icon' => 'check_circle'],
            ['val' => $stats['upcoming_games'],      'label' => 'بازی‌های پیش‌رو',           'icon' => 'schedule'],
            ['val' => $stats['scored_predictions'],  'label' => 'پیش‌بینی‌های ارزیابی‌شده', 'icon' => 'verified'],
        ];
    @endphp
    @foreach($subStats as $s)
    <div class="liquid-glass rounded-2xl p-5 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(0,228,118,0.1);">
            <span class="material-symbols-outlined" style="color:#00e476;">{{ $s['icon'] }}</span>
        </div>
        <div>
            <p class="text-xs" style="color:rgba(185,203,185,0.7);">{{ $s['label'] }}</p>
            <p class="text-2xl font-black font-heading text-white">{{ $s['val'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Recalculate ─────────────────────────────────────────── --}}
<div class="liquid-glass rounded-2xl p-5 mb-6 flex items-center justify-between gap-4">
    <div>
        <h3 class="font-bold text-sm font-heading text-white flex items-center gap-2">
            <span class="material-symbols-outlined text-base" style="color:#00e476;">sync</span>
            بازمحاسبه همه امتیازات
        </h3>
        <p class="text-xs mt-1" style="color:rgba(185,203,185,0.6);">در صورت ویرایش نتایج، این دکمه را بزنید تا همه امتیازها از نو حساب شوند.</p>
    </div>
    <form method="POST" action="{{ route('admin.recalculate') }}">
        @csrf
        <button type="submit"
                class="px-5 py-2.5 rounded-xl text-sm font-bold cursor-pointer transition-all whitespace-nowrap flex items-center gap-2"
                style="background:#00e476;color:#003919;"
                onmouseover="this.style.boxShadow='0 0 20px rgba(0,228,118,0.4)'"
                onmouseout="this.style.boxShadow=''">
            <span class="material-symbols-outlined text-base">refresh</span>
            بازمحاسبه
        </button>
    </form>
</div>

{{-- ── Recent + Upcoming ───────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    <div class="liquid-glass rounded-2xl overflow-hidden">
        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.08);">
            <h3 class="font-bold text-sm font-heading text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-base" style="color:#00e476;">history</span>
                آخرین نتایج
            </h3>
            <a href="{{ route('admin.games.index') }}" class="text-xs font-bold transition-colors" style="color:#00e476;">همه بازی‌ها</a>
        </div>
        @forelse($recentGames as $g)
            <div class="px-5 py-3 flex items-center gap-3 last:border-0"
                 style="border-bottom:1px solid rgba(255,255,255,0.05);"
                 onmouseover="this.style.background='rgba(0,228,118,0.03)'"
                 onmouseout="this.style.background=''">
                <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:#00e476;"></div>
                <span class="text-xs flex-1 truncate text-white">
                    {{ $g->homeTeam->name }} <span style="color:#00e476;font-weight:bold;">{{ $g->home_score }}–{{ $g->away_score }}</span> {{ $g->awayTeam->name }}
                </span>
                <span class="text-xs px-2 py-0.5 rounded-md font-mono" style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.7);">{{ $g->stage_label }}</span>
            </div>
        @empty
            <p class="px-5 py-8 text-sm text-center" style="color:rgba(185,203,185,0.5);">هیچ بازی‌ای پایان نیافته است.</p>
        @endforelse
    </div>

    <div class="liquid-glass rounded-2xl overflow-hidden">
        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.08);">
            <h3 class="font-bold text-sm font-heading text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-base" style="color:#00e476;">schedule</span>
                بازی‌های پیش‌رو
            </h3>
            <a href="{{ route('admin.games.create') }}" class="text-xs font-bold transition-colors" style="color:#00e476;">+ جدید</a>
        </div>
        @forelse($upcomingGames as $g)
            <div class="px-5 py-3 flex items-center gap-3 last:border-0"
                 style="border-bottom:1px solid rgba(255,255,255,0.05);"
                 onmouseover="this.style.background='rgba(0,228,118,0.03)'"
                 onmouseout="this.style.background=''">
                <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:rgba(0,228,118,0.4);"></div>
                <span class="text-xs flex-1 truncate text-white">
                    {{ $g->homeTeam->name }} <span style="color:rgba(185,203,185,0.5);">vs</span> {{ $g->awayTeam->name }}
                </span>
                <span class="text-xs font-mono" style="color:rgba(185,203,185,0.6);">{{ $g->scheduled_at?->format('j M') }}</span>
            </div>
        @empty
            <p class="px-5 py-8 text-sm text-center" style="color:rgba(185,203,185,0.5);">بازی پیش‌رویی تعریف نشده است.</p>
        @endforelse
    </div>

</div>

@endsection
