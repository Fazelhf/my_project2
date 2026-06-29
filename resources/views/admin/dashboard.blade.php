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


{{-- ── Quick Actions ────────────────────────────────────────── --}}
<div class="grid grid-cols-3 gap-3 mb-6">
    <a href="{{ route('admin.games.create') }}"
       class="liquid-glass rounded-2xl p-4 flex flex-col items-center gap-2 text-center cursor-pointer transition-all card-glow">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(0,228,118,0.12);">
            <span class="material-symbols-outlined" style="color:#00e476;">add_circle</span>
        </div>
        <p class="text-xs font-bold text-white">بازی جدید</p>
    </a>
    <a href="{{ route('admin.teams.create') }}"
       class="liquid-glass rounded-2xl p-4 flex flex-col items-center gap-2 text-center cursor-pointer transition-all card-glow">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(77,159,255,0.12);">
            <span class="material-symbols-outlined" style="color:#4D9FFF;">flag</span>
        </div>
        <p class="text-xs font-bold text-white">تیم جدید</p>
    </a>
    <form method="POST" action="{{ route('admin.recalculate') }}" class="contents">
        @csrf
        <button type="submit"
                class="liquid-glass rounded-2xl p-4 flex flex-col items-center gap-2 text-center cursor-pointer transition-all card-glow w-full"
                style="background:none;">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(245,158,11,0.12);">
                <span class="material-symbols-outlined" style="color:#F59E0B;">sync</span>
            </div>
            <p class="text-xs font-bold text-white">بازمحاسبه امتیاز</p>
        </button>
    </form>
</div>

{{-- ── Activity Log ─────────────────────────────────────────── --}}
@if($recentActivity->count())
<div class="liquid-glass rounded-2xl overflow-hidden mb-6">
    <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.08);">
        <h3 class="font-bold text-sm font-heading text-white flex items-center gap-2">
            <span class="material-symbols-outlined text-base" style="color:#00e476;">timeline</span>
            فعالیت‌های اخیر
        </h3>
        <span class="text-xs font-mono px-2 py-0.5 rounded-full" style="background:rgba(0,228,118,0.1);color:#00e476;">{{ $recentActivity->count() }} پیش‌بینی</span>
    </div>
    @foreach($recentActivity as $act)
    <div class="px-5 py-3 flex items-center gap-4 text-xs"
         style="border-bottom:1px solid rgba(255,255,255,0.04);"
         onmouseover="this.style.background='rgba(0,228,118,0.02)'"
         onmouseout="this.style.background=''">
        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0"
             style="background:rgba(0,228,118,0.1);color:#00e476;">
            {{ mb_strtoupper(mb_substr($act->user?->name ?? '?', 0, 1, 'UTF-8')) }}
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-white font-semibold truncate">{{ $act->user?->name }}</p>
            <p class="truncate mt-0.5" style="color:rgba(185,203,185,0.6);">
                {{ $act->game?->homeTeam?->code }} vs {{ $act->game?->awayTeam?->code }}
                — پیش‌بینی:
                <span class="font-mono font-bold text-white">{{ $act->home_score }}–{{ $act->away_score }}</span>
            </p>
        </div>
        @if($act->points_earned !== null)
            <span class="font-mono font-bold flex-shrink-0" style="color:#00e476;">+{{ $act->points_earned }}pt</span>
        @else
            <span class="text-[10px] px-1.5 py-0.5 rounded flex-shrink-0" style="background:rgba(255,255,255,0.05);color:rgba(185,203,185,0.5);">ارزیابی نشده</span>
        @endif
        <span class="font-mono flex-shrink-0 text-[11px]" style="color:rgba(185,203,185,0.4);">
            {{ $act->created_at->diffForHumans() }}
        </span>
    </div>
    @endforeach
</div>
@endif

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

{{-- ── Import / Export shortcut ─────────────────────────────── --}}
<div class="liquid-glass rounded-2xl overflow-hidden mt-4">
    <div class="px-5 py-4 flex items-center justify-between">
        <h3 class="font-bold text-sm font-heading text-white flex items-center gap-2">
            <span class="material-symbols-outlined text-base" style="color:#00e476;">import_export</span>
            ایمپورت / اکسپورت
        </h3>
        <a href="{{ route('admin.import-export') }}"
           class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5"
           style="background:rgba(0,228,118,0.12);color:#00e476;border:1px solid rgba(0,228,118,0.25);">
            <span class="material-symbols-outlined text-sm">open_in_new</span>
            رفتن به صفحه ایمپورت/اکسپورت
        </a>
    </div>
</div>

@endsection
