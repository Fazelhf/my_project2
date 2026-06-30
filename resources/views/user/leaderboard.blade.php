@extends('layouts.app')
@section('title', 'جدول رده‌بندی')

@section('content')

@php
$me = auth()->user();
$myRank = $users->search(fn($u) => $u->id === $me->id);
$myRank = $myRank !== false ? $myRank + 1 : null;
$myPreds = $predictions->get($me->id, collect());
$myScore = $users->firstWhere('id', $me->id)?->live_score ?? 0;
$totalGames = $finishedGames->count();
@endphp

{{-- ── Page Header ── --}}
<div class="flex items-center justify-between mb-5 reveal animate-slide-up">
    <div>
        <h1 class="text-2xl font-black font-heading text-white flex items-center gap-3">
            <span class="material-symbols-outlined text-3xl" style="color:#F5A623;font-variation-settings:'FILL' 1;">emoji_events</span>
            جدول رده‌بندی
        </h1>
        <p class="text-xs mt-1" style="color:rgba(185,203,185,0.6);">جام جهانی ۲۰۲۶ · {{ $users->count() }} شرکت‌کننده · {{ $totalGames }} بازی پایان‌یافته</p>
    </div>
    @if($myRank)
    <div class="text-center liquid-glass rounded-2xl px-5 py-3 hidden sm:block" style="border-color:rgba(0,228,118,0.25);">
        <p class="text-xs mb-0.5" style="color:rgba(185,203,185,0.6);">رتبه شما</p>
        <p class="text-2xl font-black font-heading" style="color:#00e476;">#{{ $myRank }}</p>
    </div>
    @endif
</div>

{{-- ── Tabs ── --}}
<div class="flex gap-1 mb-6 p-1 rounded-2xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);" x-data="{ tab: 'leaderboard' }">
    <button @click="tab='leaderboard'" :class="tab==='leaderboard' ? 'bg-white/10 text-white shadow' : 'text-white/50 hover:text-white/80'"
            class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 cursor-pointer">
        <span class="material-symbols-outlined text-base">leaderboard</span>
        جدول رده‌بندی
    </button>
    <button @click="tab='bracket'" :class="tab==='bracket' ? 'bg-white/10 text-white shadow' : 'text-white/50 hover:text-white/80'"
            class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 cursor-pointer">
        <span class="material-symbols-outlined text-base">account_tree</span>
        جدول حذفی
    </button>
</div>

{{-- ══════════════════════════════════════════════ LEADERBOARD TAB ══ --}}
<div x-show="tab==='leaderboard'" x-cloak>

{{-- ── My Stats Bar ── --}}
@php
$myCorrect = $myPreds->where('points_earned', '>=', 5)->count();
$myExact   = $myPreds->where('points_earned', 10)->count();
$myTotal   = $myPreds->count();
$maxScore  = $users->first()?->live_score ?? 1;
$myPct     = $maxScore > 0 ? round(($myScore / $maxScore) * 100) : 0;
@endphp
<div class="liquid-glass rounded-2xl p-5 mb-6 reveal animate-slide-up stagger-1" style="border-color:rgba(0,228,118,0.2);background:linear-gradient(135deg,rgba(0,228,118,0.04),rgba(14,20,29,0.8));">
    <div class="flex items-center gap-4 mb-4">
        <div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-black font-heading flex-shrink-0"
             style="background:linear-gradient(135deg,#00b85e,#00e476);color:#003919;">
            {{ mb_strtoupper(mb_substr($me->name, 0, 1, 'UTF-8')) }}
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="font-black text-white">{{ $me->name }}</span>
                @if($myRank) <span class="text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(0,228,118,0.15);color:#00e476;">رتبه #{{ $myRank }}</span> @endif
            </div>
            <div class="flex items-center gap-1 mt-1.5">
                <div class="flex-1 h-2 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08);">
                    <div class="h-full rounded-full transition-all duration-1000 score-bar" style="width:0%;background:linear-gradient(90deg,#00b85e,#00e476);box-shadow:0 0 8px rgba(0,228,118,0.4);" data-width="{{ $myPct }}"></div>
                </div>
                <span class="text-xs font-mono font-bold flex-shrink-0" style="color:#00e476;">{{ $myScore }} pt</span>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-3">
        <div class="text-center p-3 rounded-xl" style="background:rgba(255,255,255,0.04);">
            <p class="text-xl font-black font-heading" style="color:rgba(185,203,185,0.9);">{{ $myTotal }}</p>
            <p class="text-[10px] mt-0.5" style="color:rgba(185,203,185,0.5);">کل پیش‌بینی</p>
        </div>
        <div class="text-center p-3 rounded-xl" style="background:rgba(0,228,118,0.05);border:1px solid rgba(0,228,118,0.15);">
            <p class="text-xl font-black font-heading" style="color:#00e476;">{{ $myCorrect }}</p>
            <p class="text-[10px] mt-0.5" style="color:rgba(0,228,118,0.6);">پیش‌بینی درست</p>
        </div>
        <div class="text-center p-3 rounded-xl" style="background:rgba(245,166,35,0.05);border:1px solid rgba(245,166,35,0.15);">
            <p class="text-xl font-black font-heading" style="color:#F5A623;">{{ $myExact }}</p>
            <p class="text-[10px] mt-0.5" style="color:rgba(245,166,35,0.6);">پیش‌بینی دقیق</p>
        </div>
    </div>
</div>

{{-- ── Top 3 Podium ── --}}
@if($users->count() >= 3)
<div class="grid grid-cols-3 gap-3 mb-8 items-end reveal animate-slide-up stagger-2">

    {{-- 2nd --}}
    <div class="liquid-glass rounded-2xl p-4 text-center cursor-pointer transition-all duration-300 hover:-translate-y-1"
         onclick="openH2H({{ $users[1]->id }}, '{{ addslashes($users[1]->name) }}')"
         style="margin-top:32px;border-color:rgba(148,163,184,0.2);">
        <div class="w-12 h-12 rounded-full mx-auto mb-2 flex items-center justify-center text-lg font-black font-heading"
             style="background:linear-gradient(135deg,#64748B,#94A3B8);color:#0a0a0a;">
            {{ mb_strtoupper(mb_substr($users[1]->name,0,1,'UTF-8')) }}
        </div>
        <div class="w-7 h-7 rounded-full mx-auto mb-2 flex items-center justify-center text-xs font-black"
             style="background:rgba(148,163,184,0.15);border:1px solid rgba(148,163,184,0.3);color:#CBD5E1;">2</div>
        <p class="text-xs font-bold text-white truncate">{{ $users[1]->name }}</p>
        <p class="text-[10px] truncate mt-0.5" style="color:rgba(185,203,185,0.5);">{{ $users[1]->department ?: '—' }}</p>
        <p class="text-2xl font-black font-heading mt-2 score-counter" data-score="{{ $users[1]->live_score }}" style="color:#94A3B8;">0</p>
        @php $u1p = $predictions->get($users[1]->id, collect()); @endphp
        <div class="flex items-center justify-center gap-2 mt-1 text-[10px] font-mono">
            <span style="color:#00e476;">{{ $u1p->where('points_earned','>=',5)->count() }} درست</span>
            <span style="color:rgba(255,255,255,0.2);">·</span>
            <span style="color:#F5A623;">{{ $u1p->where('points_earned',10)->count() }} دقیق</span>
        </div>
    </div>

    {{-- 1st --}}
    <div class="rounded-2xl p-5 text-center cursor-pointer relative overflow-hidden transition-all duration-300 hover:-translate-y-2"
         onclick="openH2H({{ $users[0]->id }}, '{{ addslashes($users[0]->name) }}')"
         style="background:linear-gradient(180deg,rgba(245,166,35,0.1),rgba(14,20,29,0.95));border:1px solid rgba(245,166,35,0.35);box-shadow:0 0 40px rgba(245,166,35,0.12);">
        <div class="absolute top-0 inset-x-0 h-20 pointer-events-none"
             style="background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(245,166,35,0.18),transparent);"></div>
        <span class="material-symbols-outlined text-2xl mb-1 block relative animate-pulse"
              style="color:#F5A623;font-variation-settings:'FILL' 1,'wght' 700,'GRAD' 0,'opsz' 24;">emoji_events</span>
        <div class="w-16 h-16 rounded-full mx-auto mb-2 flex items-center justify-center text-xl font-black font-heading relative"
             style="background:linear-gradient(135deg,#92400E,#D97706,#F59E0B);color:#0a0a0a;box-shadow:0 0 30px rgba(245,166,35,0.45);">
            {{ mb_strtoupper(mb_substr($users[0]->name,0,1,'UTF-8')) }}
        </div>
        <div class="w-8 h-8 rounded-full mx-auto mb-2 flex items-center justify-center text-sm font-black"
             style="background:linear-gradient(135deg,#D97706,#F5A623);color:#0a0a0a;box-shadow:0 0 14px rgba(245,166,35,0.4);">1</div>
        <p class="text-sm font-black text-white truncate">{{ $users[0]->name }}</p>
        <p class="text-[10px] truncate mt-0.5" style="color:rgba(185,203,185,0.6);">{{ $users[0]->department ?: '—' }}</p>
        <p class="text-3xl font-black font-heading mt-2 score-counter" data-score="{{ $users[0]->live_score }}"
           style="background:linear-gradient(90deg,#F5A623,#FFD700);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">0</p>
        @php $u0p = $predictions->get($users[0]->id, collect()); @endphp
        <div class="flex items-center justify-center gap-2 mt-1 text-[10px] font-mono">
            <span style="color:#00e476;">{{ $u0p->where('points_earned','>=',5)->count() }} درست</span>
            <span style="color:rgba(245,166,35,0.3);">·</span>
            <span style="color:#F5A623;">{{ $u0p->where('points_earned',10)->count() }} دقیق</span>
        </div>
    </div>

    {{-- 3rd --}}
    <div class="liquid-glass rounded-2xl p-4 text-center cursor-pointer transition-all duration-300 hover:-translate-y-1"
         onclick="openH2H({{ $users[2]->id }}, '{{ addslashes($users[2]->name) }}')"
         style="margin-top:48px;border-color:rgba(194,65,12,0.2);">
        <div class="w-12 h-12 rounded-full mx-auto mb-2 flex items-center justify-center text-lg font-black font-heading"
             style="background:linear-gradient(135deg,#7C2D12,#C2410C);color:#FED7AA;">
            {{ mb_strtoupper(mb_substr($users[2]->name,0,1,'UTF-8')) }}
        </div>
        <div class="w-7 h-7 rounded-full mx-auto mb-2 flex items-center justify-center text-xs font-black"
             style="background:rgba(194,65,12,0.15);border:1px solid rgba(194,65,12,0.3);color:#FDBA74;">3</div>
        <p class="text-xs font-bold text-white truncate">{{ $users[2]->name }}</p>
        <p class="text-[10px] truncate mt-0.5" style="color:rgba(185,203,185,0.5);">{{ $users[2]->department ?: '—' }}</p>
        <p class="text-2xl font-black font-heading mt-2 score-counter" data-score="{{ $users[2]->live_score }}" style="color:#FB923C;">0</p>
        @php $u2p = $predictions->get($users[2]->id, collect()); @endphp
        <div class="flex items-center justify-center gap-2 mt-1 text-[10px] font-mono">
            <span style="color:#00e476;">{{ $u2p->where('points_earned','>=',5)->count() }} درست</span>
            <span style="color:rgba(255,255,255,0.2);">·</span>
            <span style="color:#F5A623;">{{ $u2p->where('points_earned',10)->count() }} دقیق</span>
        </div>
    </div>
</div>
@endif

{{-- ── Score Trend Chart ── --}}
@if($finishedGames->count() > 0)
<div class="liquid-glass rounded-2xl p-5 mb-6 reveal animate-slide-up stagger-3" style="border-color:rgba(77,159,255,0.15);">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="font-black text-sm font-heading text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-base" style="color:#FF6B6B;font-variation-settings:'FILL' 1;">trending_up</span>
                نبض امتیازات شرکت‌کنندگان در مسابقات گذشته
            </h3>
            <p class="text-[11px] mt-0.5" style="color:rgba(185,203,185,0.5);">با کلیک روی پروفایل چپ، می‌توانید روند امتیازی لحظه‌ای خود را در نمودار زنده زوم کنید.</p>
        </div>
    </div>
    <div style="position:relative;height:220px;">
        <canvas id="trend-chart"></canvas>
    </div>
    <div class="flex flex-wrap gap-x-4 gap-y-1.5 mt-4" id="chart-legend"></div>
</div>
@endif

{{-- ── Full Rankings Table ── --}}
<div class="liquid-glass rounded-2xl overflow-hidden reveal animate-slide-up stagger-3">

    <div class="px-5 py-4 flex items-center justify-between gap-4"
         style="border-bottom:1px solid rgba(255,255,255,0.08);">
        <div class="flex items-center gap-3">
            <div class="w-2 h-5 rounded-full" style="background:linear-gradient(180deg,#00e476,#4D9FFF);"></div>
            <h3 class="font-black text-sm font-heading text-white">ردبندی کامل</h3>
            <span class="text-xs font-mono px-2 py-0.5 rounded-full" style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.6);">{{ $users->count() }} نفر</span>
        </div>
        <div class="relative">
            <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 pointer-events-none"
                  style="right:10px;font-size:16px;color:rgba(185,203,185,0.4);">search</span>
            <input type="text" id="search-input" placeholder="جستجو..."
                   class="stitch-input text-sm w-36"
                   style="height:36px;padding-right:34px;padding-left:10px;">
        </div>
    </div>

    <table class="w-full text-sm">
        <thead>
            <tr style="border-bottom:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);">
                <th class="px-4 py-3 text-right text-xs font-bold font-mono w-12" style="color:rgba(185,203,185,0.5);">رتبه</th>
                <th class="px-4 py-3 text-right text-xs font-bold font-mono" style="color:rgba(185,203,185,0.5);">کاربر</th>
                <th class="px-4 py-3 text-center text-xs font-bold font-mono hidden md:table-cell" style="color:rgba(185,203,185,0.5);">کل / درست / دقیق</th>
                <th class="px-4 py-3 text-center text-xs font-bold font-mono hidden sm:table-cell" style="color:rgba(185,203,185,0.5);">پیشرفت</th>
                <th class="px-4 py-3 text-center text-xs font-bold font-mono" style="color:rgba(185,203,185,0.5);">امتیاز</th>
            </tr>
        </thead>
        <tbody id="leaderboard-body">
        @forelse($users as $i => $u)
        @php
            $isMe    = $u->id === $me->id;
            $upreds  = $predictions->get($u->id, collect());
            $total   = $upreds->count();
            $correct = $upreds->where('points_earned', '>=', 5)->count();
            $exact   = $upreds->where('points_earned', 10)->count();
            $pct     = $maxScore > 0 ? round(($u->live_score / $maxScore) * 100) : 0;
        @endphp
        <tr class="user-row cursor-pointer transition-all duration-150"
            data-name="{{ strtolower($u->name) }} {{ strtolower($u->department ?? '') }}"
            onclick="openH2H({{ $u->id }}, '{{ addslashes($u->name) }}')"
            onmouseover="this.style.background='rgba(255,255,255,0.035)'"
            onmouseout="this.style.background='{{ $isMe ? 'rgba(0,228,118,0.04)' : '' }}'"
            style="{{ $isMe ? 'background:rgba(0,228,118,0.04);border-right:2px solid #00e476;' : '' }}">

            <td class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                @if($i===0)
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black"
                         style="background:linear-gradient(135deg,#D97706,#F5A623);color:#0a0a0a;box-shadow:0 0 10px rgba(245,166,35,0.3);">1</div>
                @elseif($i===1)
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black"
                         style="background:linear-gradient(135deg,#64748B,#94A3B8);color:#0a0a0a;">2</div>
                @elseif($i===2)
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black"
                         style="background:linear-gradient(135deg,#7C2D12,#C2410C);color:#FED7AA;">3</div>
                @else
                    <span class="text-xs font-mono font-semibold" style="color:rgba(185,203,185,0.4);">{{ $i+1 }}</span>
                @endif
            </td>

            <td class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-black font-heading flex-shrink-0"
                         style="{{ $isMe ? 'background:linear-gradient(135deg,#00b85e,#00e476);color:#003919;' : 'background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#dde2f0;' }}">
                        {{ mb_strtoupper(mb_substr($u->name,0,1,'UTF-8')) }}
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-semibold truncate" style="{{ $isMe ? 'color:#00e476;' : 'color:#dde2f0;' }}">{{ $u->name }}</span>
                            @if($isMe)
                            <span class="text-[10px] px-1.5 py-0.5 rounded font-bold" style="background:rgba(0,228,118,0.15);color:#00e476;">شما</span>
                            @endif
                        </div>
                        @if($u->department)
                        <p class="text-[11px] truncate mt-0.5" style="color:rgba(185,203,185,0.5);">{{ $u->department }}</p>
                        @endif
                    </div>
                </div>
            </td>

            <td class="px-4 py-3 text-center hidden md:table-cell" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                <div class="flex items-center justify-center gap-2 text-xs font-mono">
                    <span style="color:rgba(185,203,185,0.6);">{{ $total }}</span>
                    <span style="color:rgba(255,255,255,0.15);">/</span>
                    <span style="color:#00e476;">{{ $correct }}</span>
                    <span style="color:rgba(255,255,255,0.15);">/</span>
                    <span style="color:#F5A623;">{{ $exact }}</span>
                </div>
            </td>

            <td class="px-4 py-3 hidden sm:table-cell" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                <div class="flex items-center gap-2">
                    <div class="flex-1 h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.07);">
                        <div class="h-full rounded-full row-bar" data-pct="{{ $pct }}" style="width:0%;background:{{ $i===0 ? 'linear-gradient(90deg,#D97706,#F5A623)' : ($isMe ? 'linear-gradient(90deg,#00b85e,#00e476)' : 'rgba(77,159,255,0.6)') }};transition:width 1s ease {{ ($i * 0.05) }}s;"></div>
                    </div>
                    <span class="text-[10px] font-mono w-7 text-left" style="color:rgba(185,203,185,0.4);">{{ $pct }}%</span>
                </div>
            </td>

            <td class="px-4 py-3 text-center" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                @if($i===0)
                    <span class="text-xl font-black font-heading"
                          style="background:linear-gradient(90deg,#F5A623,#FFD700);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">{{ $u->live_score }}</span>
                @elseif($i<3)
                    <span class="text-lg font-black font-heading" style="color:#94A3B8;">{{ $u->live_score }}</span>
                @else
                    <span class="font-bold" style="color:{{ $isMe ? '#00e476' : '#dde2f0' }}">{{ $u->live_score }}</span>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="px-4 py-16 text-center text-sm" style="color:rgba(185,203,185,0.4);">هیچ کاربری ثبت‌نام نکرده است.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- ── H2H Modal ── --}}
<div id="h2h-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,0.8);backdrop-filter:blur(12px);">
    <div class="liquid-glass rounded-3xl w-full max-w-2xl max-h-[88vh] flex flex-col"
         style="border-color:rgba(0,228,118,0.2);box-shadow:0 0 80px rgba(0,228,118,0.08);">

        {{-- Modal header --}}
        <div class="flex items-center justify-between px-6 py-4"
             style="border-bottom:1px solid rgba(255,255,255,0.08);">
            <div>
                <h2 class="font-black text-base font-heading text-white" id="h2h-title">مقایسه رو‌در‌رو</h2>
                <p class="text-xs mt-0.5" id="h2h-subtitle" style="color:rgba(185,203,185,0.6);"></p>
            </div>
            <button onclick="closeH2H()"
                    class="w-9 h-9 rounded-xl flex items-center justify-center cursor-pointer transition-colors"
                    style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);"
                    onmouseover="this.style.background='rgba(255,255,255,0.12)'" onmouseout="this.style.background='rgba(255,255,255,0.06)'">
                <span class="material-symbols-outlined text-base" style="color:rgba(185,203,185,0.7);">close</span>
            </button>
        </div>

        {{-- Score comparison --}}
        <div class="px-6 py-5 grid grid-cols-3 gap-4 text-center"
             style="border-bottom:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);">
            <div>
                <div class="w-12 h-12 rounded-full mx-auto mb-2 flex items-center justify-center text-lg font-black font-heading"
                     style="background:linear-gradient(135deg,#00b85e,#00e476);color:#003919;">
                    {{ mb_strtoupper(mb_substr($me->name, 0, 1, 'UTF-8')) }}
                </div>
                <p class="text-xs mb-1 font-semibold" style="color:#00e476;">{{ $me->name }}</p>
                <p class="text-3xl font-black font-heading" id="my-score-display" style="color:#00e476;">—</p>
                <p class="text-[10px] font-mono mt-1" id="my-stats-display" style="color:rgba(185,203,185,0.5);">—</p>
            </div>
            <div class="flex flex-col items-center justify-center gap-2">
                <div class="text-xs font-bold px-3 py-1 rounded-full" id="h2h-result"
                     style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.6);">در حال محاسبه...</div>
                <span class="text-xs font-mono px-2 py-0.5 rounded-full" id="games-count"
                      style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.5);">۰ بازی</span>
                <div class="w-full flex items-center gap-1 px-2" id="h2h-bar-wrap" style="display:none!important;">
                    <div class="flex-1 h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08);">
                        <div id="h2h-bar-me" class="h-full rounded-full" style="width:50%;background:linear-gradient(90deg,#00b85e,#00e476);transition:width .6s ease;"></div>
                    </div>
                </div>
            </div>
            <div>
                <div class="w-12 h-12 rounded-full mx-auto mb-2 flex items-center justify-center text-lg font-black font-heading"
                     id="opp-avatar" style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.1);color:#dde2f0;">?</div>
                <p class="text-xs mb-1 font-semibold" id="opp-name-display" style="color:#F5A623;">—</p>
                <p class="text-3xl font-black font-heading" id="opp-score-display" style="color:#F5A623;">—</p>
                <p class="text-[10px] font-mono mt-1" id="opp-stats-display" style="color:rgba(185,203,185,0.5);">—</p>
            </div>
        </div>

        {{-- Game rows --}}
        <div class="overflow-y-auto flex-1 p-4" id="h2h-body">
            <p class="text-center text-sm py-8" style="color:rgba(185,203,185,0.5);">در حال بارگذاری...</p>
        </div>
    </div>
</div>

{{-- /leaderboard tab --}}
</div>

{{-- ══════════════════════════════════════════════ BRACKET TAB ══ --}}
@php
    $stageOrder  = ['round_of_32','round_of_16','quarter_final','semi_final','final'];
    $stageLabels = ['round_of_32'=>'دور ۳۲','round_of_16'=>'دور ۱۶','quarter_final'=>'ربع‌نهایی','semi_final'=>'نیمه‌نهایی','final'=>'فینال'];
    $bStages     = collect($stageOrder)->filter(fn($s)=>$knockoutGames->has($s))->values();
    $finalGame   = $knockoutGames->get('final')?->first();
    $thirdGame   = $knockoutGames->get('third_place')?->first();
@endphp
<div x-show="tab==='bracket'" x-cloak>

    {{-- Champion Banner --}}
    @if($finalGame && $finalGame->status==='finished' && $finalGame->winnerTeam)
    <div class="liquid-glass rounded-3xl p-5 mb-5 flex items-center justify-center gap-4" style="border:1px solid rgba(255,215,0,0.3);background:linear-gradient(135deg,rgba(255,215,0,0.06),rgba(14,20,29,0.9));">
        <span class="text-3xl">🏆</span>
        @if($finalGame->winnerTeam->flag_url)
            <img src="{{ $finalGame->winnerTeam->flag_url }}" alt="" class="w-10 h-7 object-cover rounded" onerror="this.style.display='none'">
        @endif
        <div>
            <p class="text-[11px] font-bold" style="color:rgba(185,203,185,0.5);">قهرمان جهان ۲۰۲۶</p>
            <p class="text-xl font-black font-heading" style="color:#FFD700;">{{ $finalGame->winnerTeam->name_fa ?? $finalGame->winnerTeam->name }}</p>
        </div>
    </div>
    @endif

    {{-- Bracket --}}
    <div class="glass-card rounded-3xl p-4 md:p-6 overflow-x-auto">
    <div style="min-width:{{ max($bStages->count()*170, 600) }}px;">
    <div class="flex items-stretch gap-0" style="min-height:520px;">

        @foreach($bStages as $si => $stage)
        @php
            $games   = $knockoutGames->get($stage, collect());
            $isLast  = $si === $bStages->count()-1;
            $count   = $games->count();
        @endphp
        <div class="flex flex-col flex-1" style="min-width:155px;">

            {{-- Stage label --}}
            <div class="text-center mb-3 px-1">
                <span class="text-[11px] font-bold px-3 py-1 rounded-full"
                      style="background:rgba(0,228,118,0.1);color:#00e476;border:1px solid rgba(0,228,118,0.2);">
                    {{ $stageLabels[$stage] ?? $stage }}
                </span>
            </div>

            {{-- Games column --}}
            <div class="flex flex-col justify-around flex-1 gap-2 px-1.5">
                @foreach($games as $game)
                @php
                    $fin   = $game->status==='finished';
                    $win   = $game->winnerTeam ?? null;
                    $homeW = $win && $win->id===$game->home_team_id;
                    $awayW = $win && $win->id===$game->away_team_id;
                @endphp
                <a href="{{ route('games.show', $game) }}"
                   class="block group transition-transform duration-200 hover:scale-[1.02]"
                   style="text-decoration:none;">
                    <div class="rounded-2xl overflow-hidden"
                         style="background:rgba(255,255,255,0.04);border:1px solid {{ $fin ? 'rgba(0,228,118,0.2)' : 'rgba(255,255,255,0.08)' }};">

                        {{-- Home --}}
                        <div class="flex items-center gap-2 px-2.5 py-2"
                             style="{{ $homeW ? 'background:rgba(0,228,118,0.12);' : '' }}">
                            @if($game->homeTeam->flag_url)
                                <img src="{{ $game->homeTeam->flag_url }}" alt="" class="w-6 h-4 object-cover rounded flex-shrink-0" onerror="this.style.display='none'">
                            @else
                                <span class="text-[9px] font-black w-6 text-center flex-shrink-0" style="color:rgba(185,203,185,0.6);">{{ $game->homeTeam->code }}</span>
                            @endif
                            <span class="text-[11px] font-semibold flex-1 truncate" style="{{ $homeW ? 'color:#00e476;font-weight:800;' : 'color:rgba(185,203,185,0.85);' }}">
                                {{ $game->homeTeam->name_fa ?? $game->homeTeam->code }}
                            </span>
                            @if($fin)
                            <span class="text-sm font-black flex-shrink-0 ml-1" style="{{ $homeW ? 'color:#00e476;' : 'color:rgba(185,203,185,0.5);' }}">{{ $game->home_score }}</span>
                            @endif
                        </div>

                        {{-- Divider --}}
                        <div style="height:1px;background:rgba(255,255,255,0.06);margin:0 8px;"></div>

                        {{-- Away --}}
                        <div class="flex items-center gap-2 px-2.5 py-2"
                             style="{{ $awayW ? 'background:rgba(0,228,118,0.12);' : '' }}">
                            @if($game->awayTeam->flag_url)
                                <img src="{{ $game->awayTeam->flag_url }}" alt="" class="w-6 h-4 object-cover rounded flex-shrink-0" onerror="this.style.display='none'">
                            @else
                                <span class="text-[9px] font-black w-6 text-center flex-shrink-0" style="color:rgba(185,203,185,0.6);">{{ $game->awayTeam->code }}</span>
                            @endif
                            <span class="text-[11px] font-semibold flex-1 truncate" style="{{ $awayW ? 'color:#00e476;font-weight:800;' : 'color:rgba(185,203,185,0.85);' }}">
                                {{ $game->awayTeam->name_fa ?? $game->awayTeam->code }}
                            </span>
                            @if($fin)
                            <span class="text-sm font-black flex-shrink-0 ml-1" style="{{ $awayW ? 'color:#00e476;' : 'color:rgba(185,203,185,0.5);' }}">{{ $game->away_score }}</span>
                            @endif
                        </div>

                        {{-- Time/Status footer --}}
                        @if(!$fin)
                        <div class="px-2.5 py-1" style="border-top:1px solid rgba(255,255,255,0.05);background:rgba(0,0,0,0.15);">
                            <span class="text-[10px] font-mono" style="color:rgba(185,203,185,0.4);">
                                {{ $game->scheduled_at?->timezone('Asia/Tehran')->format('j M · H:i') ?? '—' }}
                            </span>
                        </div>
                        @endif

                    </div>
                </a>
                @endforeach

                @if($games->isEmpty())
                <div class="flex-1 flex items-center justify-center px-1.5">
                    <div class="w-full rounded-2xl py-6 text-center" style="background:rgba(255,255,255,0.02);border:1px dashed rgba(255,255,255,0.08);">
                        <span class="text-[11px]" style="color:rgba(185,203,185,0.25);">مشخص نشده</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Connector --}}
        @if(!$isLast)
        <div class="flex items-center self-stretch" style="width:18px;flex-shrink:0;">
            <div style="width:100%;height:2px;background:linear-gradient(90deg,rgba(0,228,118,0.25),rgba(0,228,118,0.05));"></div>
        </div>
        @endif

        @endforeach
    </div>
    </div>
    </div>

    {{-- Third place --}}
    @if($thirdGame)
    @php $tg=$thirdGame; $tgFin=$tg->status==='finished'; @endphp
    <a href="{{ route('games.show', $tg) }}"
       class="glass-card rounded-3xl p-4 mt-4 flex items-center justify-between gap-4 hover:scale-[1.01] transition-transform duration-200"
       style="text-decoration:none;border:1px solid rgba(205,127,50,0.2);background:linear-gradient(135deg,rgba(205,127,50,0.04),rgba(14,20,29,0.9));">
        <div class="flex items-center gap-3">
            <span class="text-xl">🥉</span>
            @if($tg->homeTeam->flag_url)
                <img src="{{ $tg->homeTeam->flag_url }}" alt="" class="w-8 h-5 object-cover rounded" onerror="this.style.display='none'">
            @endif
            <span class="font-bold text-sm text-white">{{ $tg->homeTeam->name_fa ?? $tg->homeTeam->name }}</span>
        </div>
        <div class="text-base font-black font-heading" style="color:{{ $tgFin ? '#CD7F32' : 'rgba(185,203,185,0.4)' }};">
            {{ $tgFin ? $tg->home_score.' – '.$tg->away_score : 'رده‌بندی سوم' }}
        </div>
        <div class="flex items-center gap-3">
            <span class="font-bold text-sm text-white">{{ $tg->awayTeam->name_fa ?? $tg->awayTeam->name }}</span>
            @if($tg->awayTeam->flag_url)
                <img src="{{ $tg->awayTeam->flag_url }}" alt="" class="w-8 h-5 object-cover rounded" onerror="this.style.display='none'">
            @endif
        </div>
    </a>
    @endif

    <div class="mt-4 text-center">
        <a href="{{ route('tournament.prediction') }}" class="btn-primary inline-flex items-center gap-2 px-6 py-3 text-sm">
            <span class="material-symbols-outlined text-base">emoji_events</span>
            پیش‌بینی قهرمان
        </a>
    </div>

</div>
{{-- /bracket tab --}}

@php
$jsPreds = [];
foreach($predictions as $userId => $gamePreds) {
    foreach($gamePreds as $pred) {
        $jsPreds[$userId][$pred->game_id] = [
            'h'   => $pred->home_score,
            'a'   => $pred->away_score,
            'pts' => $pred->points_earned ?? 0,
        ];
    }
}
$jsGames = $finishedGames->map(fn($g) => [
    'id'   => $g->id,
    'home' => $g->homeTeam?->code ?? '?',
    'away' => $g->awayTeam?->code ?? '?',
    'rh'   => $g->home_score,
    'ra'   => $g->away_score,
    'date' => $g->scheduled_at?->format('j M'),
]);
@endphp

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Trend Chart ──────────────────────────────────────────────────────────────
(function() {
    const canvas = document.getElementById('trend-chart');
    if (!canvas) return;

    const CHART_DATA   = @json($chartData);
    const CHART_LABELS = @json($chartLabels);
    const ME_ID_CHART  = {{ $me->id }};

    const PALETTE = [
        '#FF6B6B','#4D9FFF','#F5A623','#A78BFA','#34D399','#FB923C',
        '#F472B6','#60A5FA','#FBBF24','#4ADE80','#E879F9','#38BDF8',
    ];

    const datasets = CHART_DATA.map((u, i) => {
        const isMe = u.id === ME_ID_CHART;
        const color = isMe ? '#00e476' : PALETTE[i % PALETTE.length];
        return {
            label: u.name,
            data: u.scores,
            borderColor: color,
            backgroundColor: color + '18',
            borderWidth: isMe ? 3 : 1.5,
            pointRadius: isMe ? 4 : 2,
            pointHoverRadius: isMe ? 6 : 4,
            tension: 0.4,
            fill: false,
            order: isMe ? 0 : 1,
        };
    });

    new Chart(canvas, {
        type: 'line',
        data: { labels: CHART_LABELS, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(14,20,29,0.95)',
                    borderColor: 'rgba(255,255,255,0.08)',
                    borderWidth: 1,
                    titleColor: 'rgba(185,203,185,0.7)',
                    bodyColor: '#dde2f0',
                    callbacks: {
                        label: ctx => ' ' + ctx.dataset.label + ': ' + ctx.parsed.y + ' pt',
                    },
                },
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: { color: 'rgba(185,203,185,0.5)', font: { size: 11 } },
                },
                y: {
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: { color: 'rgba(185,203,185,0.5)', font: { size: 11 } },
                    beginAtZero: true,
                },
            },
        },
    });

    // Custom legend
    const legend = document.getElementById('chart-legend');
    if (legend) {
        CHART_DATA.forEach((u, i) => {
            const isMe = u.id === ME_ID_CHART;
            const color = isMe ? '#00e476' : PALETTE[i % PALETTE.length];
            const el = document.createElement('div');
            el.style.cssText = 'display:flex;align-items:center;gap:5px;cursor:pointer;';
            el.innerHTML = `<span style="width:20px;height:3px;border-radius:2px;background:${color};display:inline-block;flex-shrink:0;"></span><span style="font-size:11px;color:rgba(185,203,185,0.7);">${u.name}</span>`;
            legend.appendChild(el);
        });
    }
})();

// ── Main JS ──────────────────────────────────────────────────────────────────
const ME_ID     = {{ $me->id }};
const ME_NAME   = '{{ addslashes($me->name) }}';
const ALL_PREDS = @json($jsPreds);
const ALL_GAMES = @json($jsGames);

// ── Score counter animation ──────────────────────────────────────────────
function animateCount(el, target, duration = 900) {
    const start = performance.now();
    const update = (now) => {
        const p = Math.min((now - start) / duration, 1);
        const ease = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.round(ease * target);
        if (p < 1) requestAnimationFrame(update);
    };
    requestAnimationFrame(update);
}

// ── Intersection observer for reveal animations ──────────────────────────
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.querySelectorAll('.score-counter').forEach(el => {
            animateCount(el, +el.dataset.score);
        });
        entry.target.querySelectorAll('.row-bar').forEach(el => {
            el.style.width = el.dataset.pct + '%';
        });
        entry.target.querySelectorAll('.score-bar').forEach(el => {
            setTimeout(() => el.style.width = el.dataset.width + '%', 100);
        });
        observer.unobserve(entry.target);
    });
}, { threshold: 0.1 });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// Run score bar immediately if already visible
document.querySelectorAll('.score-bar').forEach(el => {
    setTimeout(() => el.style.width = el.dataset.width + '%', 300);
});

// ── Search ───────────────────────────────────────────────────────────────
document.getElementById('search-input').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('#leaderboard-body .user-row').forEach(row => {
        row.style.display = (!q || row.dataset.name.includes(q)) ? '' : 'none';
    });
});

// ── H2H ─────────────────────────────────────────────────────────────────
function ptColor(pts) {
    if (pts >= 10) return '#00e476';
    if (pts >= 7)  return '#4D9FFF';
    if (pts >= 5)  return '#F5A623';
    if (pts >= 2)  return '#b9cbb9';
    return '#FF8A8A';
}

function openH2H(oppId, oppName) {
    const isSelf = oppId === ME_ID;
    document.getElementById('h2h-title').textContent    = isSelf ? 'پیش‌بینی‌های من' : 'مقایسه رو‌در‌رو';
    document.getElementById('h2h-subtitle').textContent = isSelf ? '' : ME_NAME + ' در برابر ' + oppName;
    document.getElementById('opp-name-display').textContent = oppName;
    document.getElementById('opp-avatar').textContent   = oppName.charAt(0).toUpperCase();
    const modal = document.getElementById('h2h-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    renderH2H(oppId, oppName);
}

function closeH2H() {
    document.getElementById('h2h-modal').classList.replace('flex', 'hidden');
}

function renderH2H(oppId) {
    const myP  = ALL_PREDS[ME_ID]  || {};
    const oppP = ALL_PREDS[oppId]  || {};
    let myTotal = 0, oppTotal = 0, rows = '', count = 0;
    let myCorrect = 0, oppCorrect = 0, myExact = 0, oppExact = 0;

    ALL_GAMES.forEach(g => {
        const m = myP[g.id], o = oppP[g.id];
        if (!m && !o) return;
        myTotal  += m?.pts ?? 0;
        oppTotal += o?.pts ?? 0;
        if ((m?.pts ?? 0) >= 5) myCorrect++;
        if ((m?.pts ?? 0) >= 10) myExact++;
        if ((o?.pts ?? 0) >= 5) oppCorrect++;
        if ((o?.pts ?? 0) >= 10) oppExact++;
        count++;

        const mScore = m ? `<span style="font-size:18px;font-weight:900;color:${ptColor(m.pts)};font-family:inherit;">${m.h}–${m.a}</span><div style="font-size:10px;color:${ptColor(m.pts)};font-family:monospace;">+${m.pts}pt</div>` : '<span style="color:rgba(185,203,185,0.3);font-size:13px;">—</span>';
        const oScore = o ? `<span style="font-size:18px;font-weight:900;color:${ptColor(o.pts)};font-family:inherit;">${o.h}–${o.a}</span><div style="font-size:10px;color:${ptColor(o.pts)};font-family:monospace;">+${o.pts}pt</div>` : '<span style="color:rgba(185,203,185,0.3);font-size:13px;">—</span>';

        rows += `
        <div style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:10px 12px;margin-bottom:8px;display:flex;align-items:center;gap:8px;transition:background .15s;" onmouseover="this.style.background='rgba(255,255,255,0.045)'" onmouseout="this.style.background='rgba(255,255,255,0.025)'">
            <div style="flex:1;text-align:center;">${mScore}</div>
            <div style="text-align:center;flex-shrink:0;padding:0 8px;min-width:80px;">
                <div style="font-size:10px;color:rgba(185,203,185,0.4);margin-bottom:2px;">${g.date}</div>
                <div style="font-size:12px;font-weight:700;color:#dde2f0;">${g.home} <span style="color:rgba(255,255,255,0.25);">vs</span> ${g.away}</div>
                <div style="font-size:11px;color:rgba(185,203,185,0.5);margin-top:2px;"><b style="color:#dde2f0;">${g.rh}–${g.ra}</b></div>
            </div>
            <div style="flex:1;text-align:center;">${oScore}</div>
        </div>`;
    });

    document.getElementById('my-score-display').textContent  = myTotal;
    document.getElementById('opp-score-display').textContent = oppTotal;
    document.getElementById('my-stats-display').textContent  = myCorrect + ' درست · ' + myExact + ' دقیق';
    document.getElementById('opp-stats-display').textContent = oppCorrect + ' درست · ' + oppExact + ' دقیق';
    document.getElementById('games-count').textContent = count + ' بازی مشترک';

    const resultEl = document.getElementById('h2h-result');
    if (oppId === ME_ID) {
        resultEl.textContent = 'آمار شخصی';
        resultEl.style.cssText = 'background:rgba(0,228,118,0.1);color:#00e476;font-size:12px;font-weight:700;padding:4px 12px;border-radius:999px;';
    } else if (myTotal > oppTotal) {
        resultEl.textContent = 'شما پیش هستید 🏆';
        resultEl.style.cssText = 'background:rgba(0,228,118,0.12);color:#00e476;font-size:12px;font-weight:700;padding:4px 12px;border-radius:999px;';
    } else if (myTotal < oppTotal) {
        resultEl.textContent = oppId === ME_ID ? '' : 'رقیب جلوتر است';
        resultEl.style.cssText = 'background:rgba(245,166,35,0.12);color:#F5A623;font-size:12px;font-weight:700;padding:4px 12px;border-radius:999px;';
    } else {
        resultEl.textContent = 'مساوی';
        resultEl.style.cssText = 'background:rgba(77,159,255,0.12);color:#4D9FFF;font-size:12px;font-weight:700;padding:4px 12px;border-radius:999px;';
    }

    document.getElementById('h2h-body').innerHTML = rows ||
        '<p style="text-align:center;color:rgba(185,203,185,0.4);padding:32px 0;font-size:13px;">هنوز پیش‌بینی مشترکی وجود ندارد</p>';
}

document.getElementById('h2h-modal').addEventListener('click', function(e) {
    if (e.target === this) closeH2H();
});
</script>
@endpush
@endsection
