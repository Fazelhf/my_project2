@extends('layouts.admin')

@section('title', 'داشبورد مدیریت')
@section('page-title', 'داشبورد')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label' => 'کاربران',         'value' => $stats['total_users'],        'color' => '#22C55E'],
        ['label' => 'تیم‌ها',          'value' => $stats['total_teams'],         'color' => '#60a5fa'],
        ['label' => 'بازی‌ها',         'value' => $stats['total_games'],         'color' => '#a78bfa'],
        ['label' => 'پیش‌بینی‌ها',     'value' => $stats['total_predictions'],   'color' => '#fb923c'],
    ] as $stat)
        <div class="rounded-2xl border p-5" style="background-color:#0F172A; border-color:#334155;">
            <p class="text-xs uppercase tracking-wider mb-2" style="color:#94A3B8;">{{ $stat['label'] }}</p>
            <p class="text-3xl font-bold" style="color:{{ $stat['color'] }}; font-family:'Poppins',sans-serif;">{{ $stat['value'] }}</p>
        </div>
    @endforeach
</div>

{{-- Sub stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="rounded-2xl border p-5" style="background-color:#0F172A; border-color:#334155;">
        <p class="text-xs uppercase tracking-wider mb-2" style="color:#94A3B8;">بازی‌های پایان یافته</p>
        <p class="text-2xl font-bold" style="color:#F8FAFC;">{{ $stats['finished_games'] }}</p>
    </div>
    <div class="rounded-2xl border p-5" style="background-color:#0F172A; border-color:#334155;">
        <p class="text-xs uppercase tracking-wider mb-2" style="color:#94A3B8;">بازی‌های پیش‌رو</p>
        <p class="text-2xl font-bold" style="color:#F8FAFC;">{{ $stats['upcoming_games'] }}</p>
    </div>
    <div class="rounded-2xl border p-5" style="background-color:#0F172A; border-color:#334155;">
        <p class="text-xs uppercase tracking-wider mb-2" style="color:#94A3B8;">پیش‌بینی‌های ارزیابی شده</p>
        <p class="text-2xl font-bold" style="color:#F8FAFC;">{{ $stats['scored_predictions'] }}</p>
    </div>
</div>

{{-- Recalculate --}}
<div class="rounded-2xl border p-5 mb-6 flex items-center justify-between gap-4"
     style="background-color:#0F172A; border-color:#334155;">
    <div>
        <h3 class="font-semibold text-sm" style="color:#F8FAFC;">بازمحاسبه همه امتیازات</h3>
        <p class="text-xs mt-1" style="color:#94A3B8;">در صورت ویرایش نتایج، این دکمه را بزنید تا همه امتیازها از نو حساب شوند.</p>
    </div>
    <form method="POST" action="{{ route('admin.recalculate') }}">
        @csrf
        <button type="submit"
                class="px-4 py-2.5 rounded-xl text-sm font-semibold cursor-pointer transition-colors whitespace-nowrap"
                style="background-color:#22C55E; color:#020617;"
                onmouseover="this.style.backgroundColor='#16A34A';"
                onmouseout="this.style.backgroundColor='#22C55E';">
            بازمحاسبه
        </button>
    </form>
</div>

{{-- Recent + Upcoming --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    {{-- Recent finished games --}}
    <div class="rounded-2xl border overflow-hidden" style="background-color:#0F172A; border-color:#334155;">
        <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color:#334155;">
            <h3 class="font-semibold text-sm" style="color:#F8FAFC;">آخرین نتایج</h3>
            <a href="{{ route('admin.games.index') }}" class="text-xs" style="color:#22C55E;">همه بازی‌ها</a>
        </div>
        @forelse($recentGames as $g)
            <div class="px-5 py-3 border-b flex items-center gap-3" style="border-color:#334155;">
                <span class="text-xs flex-1 truncate" style="color:#F8FAFC;">
                    {{ $g->homeTeam->name }} {{ $g->home_score }}–{{ $g->away_score }} {{ $g->awayTeam->name }}
                </span>
                <span class="text-xs px-2 py-0.5 rounded-md" style="background-color:#1E293B; color:#94A3B8;">{{ $g->stage_label }}</span>
            </div>
        @empty
            <p class="px-5 py-6 text-sm text-center" style="color:#475569;">هیچ بازی‌ای پایان نیافته است.</p>
        @endforelse
    </div>

    {{-- Upcoming games --}}
    <div class="rounded-2xl border overflow-hidden" style="background-color:#0F172A; border-color:#334155;">
        <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color:#334155;">
            <h3 class="font-semibold text-sm" style="color:#F8FAFC;">بازی‌های پیش‌رو</h3>
            <a href="{{ route('admin.games.create') }}" class="text-xs" style="color:#22C55E;">+ جدید</a>
        </div>
        @forelse($upcomingGames as $g)
            <div class="px-5 py-3 border-b flex items-center gap-3" style="border-color:#334155;">
                <span class="text-xs flex-1 truncate" style="color:#F8FAFC;">
                    {{ $g->homeTeam->name }} vs {{ $g->awayTeam->name }}
                </span>
                <span class="text-xs" style="color:#94A3B8;">{{ $g->scheduled_at?->format('j M') }}</span>
            </div>
        @empty
            <p class="px-5 py-6 text-sm text-center" style="color:#475569;">بازی پیش‌رویی تعریف نشده است.</p>
        @endforelse
    </div>
</div>

@endsection
